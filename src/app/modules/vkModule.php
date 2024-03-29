<?php
namespace app\modules;

use app;
use framework;
use php\framework\Logger;
use php\format\JsonProcessor;
use app\forms\vkCaptcha;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\io\Stream;
use php\lang\Thread;
use php\lib\Str;

class vkModule {
    
    const LOG = false;
    
    private static    // Если есть своё приложение, поменяйте параметры $appID и $appSecret
                    $appID = '5119526',
                    $appSecret = 'QFWVrezg1DAypE6vCqFj',
                    
                    // Файл, в котором хранится access_token
                    $tokenFile = './cache.vk',
                    $accessToken = 'false',

                    // Конфиги для загрузки фото
                    $uploadImageConfig = [
                        'photos.save' => ['method' => 'photos.getUploadServer', 'upload' => ['album_id', 'group_id'], 'POST' => 'file1'],
                        'photos.saveWallPhoto' => ['method' => 'photos.getWallUploadServer', 'upload' => ['group_id'], 'POST' => 'photo'],
                        'photos.saveOwnerPhoto' => ['method' => 'photos.getOwnerPhotoUploadServer', 'upload' => ['owner_id'], 'POST' => 'photo'], // * //
                        'photos.saveMessagesPhoto' => ['method' => 'photos.getMessagesUploadServer', 'upload' => [], 'POST' => 'photo'],
                        'messages.setChatPhoto' => ['method' => 'photos.getChatUploadServer', 'upload' => ['chat_id'], 'POST' => 'file'], // * //
                    ];                       
    private function Log(){
        if(self::LOG) Logger::Debug('[VK] ' . var_export(func_get_args(), true));
    }
    
    private static $longPoll, $lpAbort = false;

    /**
     * Возвращаем версию api 
     */
    public static function getApiVersion() {
        $ver = app()->getForm(Settings)->versionapivk->selected;
        if (!$ver) {
            $ver = '5.103';
        }
        return $ver;
    }
    
    /**
     * Подключение к long-poll серверу
     *
     * @param callable $callback - функция, которая будет вызвана при каком-либо событии
     */
    public static function longPollConnect($callback, $params = false){
        if(!$params) return self::Query('messages.getLongPollServer', ['use_ssl' => 1, 'need_pts' => 1], function($answer) use ($callback){
            self::$lpAbort = false;
            return self::longPollConnect($callback, $answer['response']);
        });

        self::log(['longPollConnect' => $params]);

        $func = function() use ($params, $callback){
            
            self::Query(null, [], function($answer) use ($params, $callback){
                if(self::$lpAbort === true){
                    self::$lpAbort = false;
                    return;
                } 

                if(isset($answer['failed'])) return self::longPollConnect($callback, false); 

                UXApplication::runLater(function() use ($callback, $answer){
                    $callback($answer['updates']);
                });

                $params['ts'] = $answer['ts'];
                return self::longPollConnect($callback, $params);
            }, 
            [
                'url' => 'https://'.$params['server'].'?act=a_check&key='.$params['key'].'&ts='.$params['ts'].'&wait=25&mode=2',
                'connectTimeout' => 10000,
                'readTimeout' => 35000
            ]);
        };
        self::$longPoll = new Thread($func);
        self::$longPoll->start();
    }

    /**
     * Отключение от long-poll сервера
     **/
    public static function longPollDisconnect(){
        if(self::$longPoll instanceof Thread and !self::$longPoll->isInterrupted()) self::$longPoll->interrupt();
        self::$longPoll = null;
        self::$lpAbort = true;
    }
    /**
     * Загрузка и сохранение изображения на сервере ВК
     * (Если передан параметр $callback, запрос будет выполнен асинхронно)
     *
     * @param string $method - метод вк (photos.save, photos.saveWallPhoto, photos.saveOwnerPhoto, photos.saveMessagesPhoto, messages.setChatPhoto)
     * @param string $file - путь к загружаемому изображению
     * @param string $filepath - путь к загружаемому файлу
     * @param array $params - параметры для загрузки
     * @param callable $callback - функция, которая будет вызвана по окончанию запроса
     **/
    public static function uploadImage($method, $file, $params = [], $callback = false, $jParams = []){
        if(!isset(self::$uploadImageConfig[$method])) return false;
        $config = self::$uploadImageConfig[$method];

        $thread = new Thread(function() use ($config, $method, $file, $params, $callback, $jParams){
            // Step 1 - get upload server
            $usMethod = $config['method'];
            $usParams = [];
            
            foreach($params as $k=>$v){
                if(in_array($k, $config['upload'])){
                    $usParams[$k] = $v;
                }
            }

            $server = self::Query($usMethod, $usParams)['response'];

            // Step 2 - upload file
            $uResult = self::Upload($server['upload_url'], $config['POST'], $file);

            // Step 3 - save uploaded file
            $save = self::Query($method, array_merge($params, $uResult));

            if(is_callable($callback))$callback($save);
        });

        $thread->start();
    }

    /**
     * Загрузка файла на сервер ВК
     *
     * @param string $server - сервер, куда будет загружен файл
     * @param string $field - имя поля
     * @param string $filepath - путь к загружаемому файлу
     * @param callable $callback - функция, которая будет вызвана по окончанию запроса
     **/
    public static function Upload($server, $field, $filepath, $callback = false){
        $uploadParams = [
            'url' => $server,
            'postFiles' => [$field => $filepath]
        ];
        return self::Query('none', [], $callback, $uploadParams);
    }

    /**
     * Выполнение запроса 
     * (Если передан параметр $callback, запрос будет выполнен асинхронно)
     *
     * @param string $method - метод VK API https://vk.com/dev/methods
     * @param array $params - массив с параметрами
     * @param callable $callback=false - функция, которая будет вызвана по окончанию запроса
     * 
     * @example vkModule::Query('users.get', ['fields'=>'photo_100'], function($answer){ });
     **/
    public static function Query($method, $params = [], $callback = false, $jParams = []) {
        $form = app()->getForm(Settings);
        $params['v'] = self::getApiVersion();
        if(self::$accessToken) {
            $params['access_token'] = self::$accessToken;
        }
                        
        $url = 'https://api.vk.com/method/'.$method.'?'.http_build_query($params);

        $connect = new jURL($url);
        $connect->setOpts($jParams);
        if (app()->getForm(Settings)->proxyVkEnable->selected) {
            $connect->setProxy(app()->getForm(Settings)->proxyVk->text);
            $connect->setProxyType(app()->getForm(Settings)->typeProxyVk->selected);
        }
        if(is_callable($callback)){
            $connect->asyncExec(function($content, $connect) use ($method, $params, $callback, $jParams){
                $result = self::processResult($content, $connect, $method, $params, $callback, $jParams);
                if($result !== false) $callback($result);
            });
        } else {
            $content = $connect->exec();
            return self::processResult($content, $connect, $method, $params, $callback, $jParams);
        }
    }

    private static function processResult($content, $connect, $method, $params, $callback, $jParams) {
        try {
            $errors = $connect->getError();
            if($errors !== false){
                throw new vkException('Невозможно совершить запрос', -1, $errors);
            }
            $json = new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS);
            $data = $json->parse($content);

            self::log([$url=>$data]);
                 
                        
            if(isset($data['error'])){
                throw new vkException($data['error']['error_msg'], $data['error']['error_code'], $data);
                return false;
            }
            
            return $data;
            
        }catch(vkException $e){
            UXApplication::runLater(function () use ($e, $method, $params, $callback, $jParams) {
                switch($e->getCode()){
                    //api.vk.com недоступен, обычно из-за частых запросов
                    case -2:
                        wait(500);
                    break;    
                    case 5://Просроченный access_token
                    case 10://Ошибка авторизации
                        UXDialog::show('Вам необходимо повторно авторизоваться', 'ERROR');
                        self::logout();
                        return self::checkAuth(function(){
                            self::Query($method, $params, $callback, $jParams);
                        });
                    break;    
                        //Нужно ввести капчу
                    case 14:
                        $result = $e->getData();

                        $vkCaptcha = app()->getForm('vkCaptcha');
                        $vkCaptcha->setUrl($result['error']['captcha_img']);
                        $vkCaptcha->showAndWait();

                        $params['captcha_sid'] = $result['error']['captcha_sid'];
                        $params['captcha_key'] = $vkCaptcha->input->text;
                    break;    

                    default:
                        return false;
                        //return UXDialog::show('Ошибка VK API: '.$e->getMessage().' (code='.$e->getCode().')' . "\n\n\nDebug: " . var_export($e->getData(), true), 'ERROR');
                }
                return self::Query($method, $params, $callback, $jParams);
            });
        }
        return false;
    }
    
    /**
     * Проверяет, авторизован ли текущий пользователь (есть ли сохраненный access_token)
     * + автоматически "подбирает" сохранённый access_token
     *
     * @return bool
     **/     
    public static function isAuth() {
        if(file_exists(self::$tokenFile) and $t = file_get_contents(self::$tokenFile) and Str::Length($t) > 85){
            $token = str::sub($t, 0, 85);
            $hash = str::sub($t, 85);

            if(self::getHash($token) == $hash){
                self::$accessToken = $token;
                return true;
            } else {
                self::log('invalid hash', [self::getHash($token), $t, $token, $hash]);
            }
        }
        //log('invalid', [file_exists($tokenFile), $t = file_get_contents($tokenFile), Str::Length($t) > 85]);
        return false;
    }

    /**
     * Проверяет, авторизован ли пользователь, если нет - покажет форму авторизации
     **/
    public static function checkAuth($callback = false) {
        $callback = is_callable($callback) ? $callback : function(){};

        if(!self::isAuth()){
            app()->getForm('vkAuth')->setCallback($callback);
            app()->getForm('vkAuth')->showAndWait();
        }
        else $callback();
    }

    /**
     * Деавторизирует пользователя, удаляет access_token
     */
    public static function logout(){
        self::$accessToken = false;
        unlink(self::$tokenFile);
    }
    
    /**
     * Возвращает ID приложения
     * @return string
     */
    public static function getAppID(){
        return self::$appID;
    }

    /**
     * Возвращает версию API
     * @return string
     */
    public static function getApiVersion(){
        $ver = app()->getForm(Settings)->versionapivk->selected;
        if (!$ver) {
            $ver = '5.103';
        }
        return $ver;
    }

    /**
     * Устанавливает access_token и сохраняет его в файл
     */
    public static function setAccessToken($aToken){
        self::$accessToken = $aToken;
        file_put_contents(self::$tokenFile, $aToken . self::getHash($aToken));
    }

    private static function getHash($str){
        return str::hash($str . self::$appID . self::$appSecret, 'SHA-1');
    }


        // На случай, если модуль подключён к форме, чтоб не было ошибки
        public function getScript(){
            return null;
        }

        public function apply(){
            return null;
        }
}


class vkException extends \Exception{
    private $data;
    public function getData(){
        return $this->data;
    }
        
    public function __construct($message = null, $code = 0, $data = []){
        $this->data = $data;
        return parent::__construct($message, $code, null);
    }
    
}

if(!function_exists('http_build_query')){
    function http_build_query($a,$b='',$c=0)
     {
            if (!is_array($a)) return false;
            foreach ((array)$a as $k=>$v)
            {
                if ($c)
                {
                    if( is_numeric($k) )
                        $k=$b."[]";
                    else
                        $k=$b."[$k]";
                }
                else
                {   if (is_int($k))
                        $k=$b.$k;
                }

                if (is_array($v)||is_object($v))
                {
                    $r[]=http_build_query($v,$k,1);
                        continue;
                }
                $r[]=urlencode($k)."=".urlencode($v);
            }
            return implode("&",$r);
            }
}
