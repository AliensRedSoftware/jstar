<?php
namespace app\forms;

use app\forms\profile;
use php\util\LauncherClassLoader;
use php\io\FileStream;
use facade\Json;
use bundle\http\HttpClient;
use std, gui, framework, app;

class auth extends AbstractForm {

    /**
     * @event register.action 
     */
    function doRegisterAction(UXEvent $e = null) {
        $this->loadForm('register');
        app()->getForm(store)->showPreloader('Ожидание...');
    }

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {
        $this->checkauth(true);
    }

    /**
     * @event showing 
     */
    function doShowing(UXWindowEvent $e = null) {    
        $this->Menu(true);
        app()->getForm(store)->showPreloader('Ожидание...');
    }
    
    /**
     * @event auth.action 
     */
    function doAuthAction(UXEvent $e = null) {
        if (trim($this->password->text) != null) {
            $pass = $this->password->text;
            $httpClient = new HttpClient();
            $request = "http://f0259540.xsph.ru/bot/profile/getlistuser.php";
            $this->showPreloader('Идет обработка данных пожалуйста подождите...');
            $httpClient->postAsync($request , [] , function ($data) use ($pass) {
                $response = explode("\n" , $data->body());
                foreach ($response as $v) {
                    $resp = explode("?" , $v);
                    foreach ($resp as $val) {
                        if ($val != null) {
                            if (str::contains($resp[1] , '@')) {
                                $email = substr($resp[1] , 0 , -5);
                            }
                            if (str::contains($this->email->text , '@')) {
                                if (str::contains($val , '@')) {
                                    $itog = substr($val , 0 , -5);
                                    if ($this->email->text == $itog) {
                                        $httpemailclient = new HttpClient();
                                        $httpemailclient->postAsync('http://f0259540.xsph.ru/bot/profile/getpassuser.php/getpass/' . $login . "?" . $itog , [] , function ($data) use ($login , $itog , $pass) {
                                            //РРРРРРРРРРРРРРРРР ))
                                            if ($pass == Json::decode($data->body())['pass']) {
                                                Json::toFile('./tdata.json' , [
                                                    'login' => $login ,
                                                    'email' => $itog ,
                                                    'pass' => $pass ,
                                                    'img_hash' => md5_file('./data/avatar.png') ,
                                                ]);
                                                UXDialog::showAndWait('Успешно вошли!' , 'INFORMATION');
                                                $this->hide();
                                            } else {
                                               UXDialog::showAndWait('Пароль неверный!' , 'ERROR'); 
                                               fs::delete('./tdata.json');
                                            }
                                        });
                                        $this->hidePreloader();
                                        return ;
                                    } else {
                                        $error_email = true;
                                    }
                                } else {
                                    $login = $val;
                                }
                            } else {
                                if ($this->email->text == $val && $val != null && $email != null) {
                                    $httploginclient = new HttpClient();
                                    $httploginclient->postAsync('http://f0259540.xsph.ru/bot/profile/getpassuser.php/getpass/' . $val . "?" . $email , [] , function ($data) use ($val , $email , $pass) {
                                        //
                                        if ($pass == Json::decode($data->body())['pass']) {
                                            Json::toFile('./tdata.json' , [
                                                'login' => $val ,
                                                'email' => $email ,
                                                'pass' => $pass ,
                                                'img_hash' => md5_file('./data/avatar.png') ,
                                            ]);
                                            UXDialog::showAndWait('Успешно вошли!' , 'INFORMATION');
                                            $this->hide();
                                        } else {
                                           UXDialog::showAndWait('Пароль неверный!' , 'ERROR');
                                           fs::delete('./tdata.json');
                                        }
                                    });
                                    $this->hidePreloader();
                                    return ;
                                }
                                 else {
                                    $login_message = true;
                                }
                            }
                        }
                    }
                }
                $this->hidePreloader();
                if ($error_email == true) {
                    UXDialog::showAndWait('Email адрес неверный!' , 'ERROR');
                    fs::delete('./tdata.json');
                } else {
                    //
                }
                if ($login_message == true) {
                    UXDialog::showAndWait('Логин неверный!' , 'ERROR');
                    fs::delete('./tdata.json');
                } else {
                    //
                }
            });
        } else {
            UXDialog::showAndWait('Пожалуйста введите пароль' , 'ERROR');
        }
    }
    /**
     * Проверка авторизован пользователь или нет
     */
    public function checkauth ($type = true) {
        Logger::info('Проверка аккаунта на подлиность');
        $store = app()->form(store);
        $store->showPreloader('Идет проверка аккаунта :/');
        if (!fs::exists('./tdata.json')) {
            if ($type = false) {
                $store->login->text = 'Войти в аккаунт';
                $store->login->graphic = new UXImageView(new UXImage('res://.data/img/action.png'));
                $store->hidePreloader();
                $store->newuser->toFront();
                $store->toast('Успешно вышли из аккаунта :)');
                return ;
            } else {
                $store->login->text = 'Войти в аккаунт';
                $store->login->graphic = new UXImageView(new UXImage('res://.data/img/action.png'));
                $store->hidePreloader();
                $store->newuser->toFront();
                return ;
            }
        }
        $data = Json::fromFile('./tdata.json');
        $login = $data['login'];
        $email = $data['email'];
        $pass = $data['pass'];
        $img = $data['img_hash'];
        $request = "http://f0259540.xsph.ru/bot/profile/getpassuser.php/getpass/$login?$email";
        $httpclient = new HttpClient();
        $httpclient->postAsync($request , [] , function ($data) use ($type , $store , $data , $email , $pass , $img) {
            if ($data->body() == true) {
                $login_server = Json::decode($data->body())['login'];
                $email_server = Json::decode($data->body())['email'];
                $pass_server = Json::decode($data->body())['pass'];
                $img_server = Json::decode($data->body())['img_hash'];
                if ($pass_server == $pass && $img_server == $img || $img_server == null) {
                    if($type == true) {
                        if ($img_server != null) {
                            $store->avatar->image = new UXImage ('./data/avatar.png');
                        }
                        $store->toast('Успешно вошли в аккаунт!');
                        $store->login->text = 'Выйти из аккаунта';
                        $store->login->graphic = new UXImageView(new UXImage('res://.data/img/Exit.png'));
                        $store->hidePreloader();
                        $store->newuser->toBack();
                        return ;  
                    } else {
                        app()->getForm(profile)->showAndWait();
                        app()->getForm(profile)->loadprofile();
                        app()->getForm(profile)->hidePreloader(); 
                    }
                } else {
                    $store->avatar->image = new UXImage ('res://.data/img/user.png');
                    
                    Logger::error('Ошибка неверный пароль!');
                    $store->toast('Неверный пароль' , 'ERROR');
                    $store->login->text = 'Войти в аккаунт';
                    $store->login->graphic = new UXImageView(new UXImage('res://.data/img/action.png'));
                    $store->hidePreloader();
                    $store->newuser->toFront();
                    fs::delete('./tdata.json');
                    return ;
                }
                
                
                /**
                    if ($type == true) {
                        if ($pass_server == $pass) {
                        /**
                            if(Json::decode($data->body())['pass'] == $pass) {
                                if ($type == true) {
                                    $store->toast('Успешно вошли в аккаунт!');
                                    $store->login->text = 'Выйти из аккаунта';
                                    $store->login->graphic = new UXImageView(new UXImage('res://.data/img/Exit.png'));
                                    $store->hidePreloader();
                                    $store->newuser->toBack();
                                    return ;
                                } else {
                                    app()->getForm(profile)->showAndWait();
                                    app()->getForm(profile)->loadprofile();
                                    app()->getForm(profile)->hidePreloader();
                                }
                            } else {
                                if ($type == true) {
                                    $store->toast('Неверный пароль' , 'ERROR');
                                    $store->login->text = 'Войти в аккаунт';
                                    $store->login->graphic = new UXImageView(new UXImage('res://.data/img/action.png'));
                                    $store->hidePreloader();
                                    $store->newuser->toFront();
                                    return ;
                                } else {
                                    app()->getForm(profile)->hide();
                                    $store->login->text = 'Войти в аккаунт';
                                    $store->login->graphic = new UXImageView(new UXImage('res://.data/img/action.png'));
                                    $store->newuser->toFront();
                                    return;
                                }
                                
                                
                            if (!str::contains($email , '@') && $login_server == $login) { //login
                                $store->toast('Успешно вошли в аккаунт!');
                                $store->login->text = 'Выйти из аккаунта';
                                $store->login->graphic = new UXImageView(new UXImage('res://.data/img/Exit.png'));
                                $store->hidePreloader();
                                $store->newuser->toBack();
                                return ;
                            } elseif (str::contains($email , '@') && $email_server == $email) { //email
                                $store->toast('Успешно вошли в аккаунт!');
                                $store->login->text = 'Выйти из аккаунта';
                                $store->login->graphic = new UXImageView(new UXImage('res://.data/img/Exit.png'));
                                $store->hidePreloader();
                                $store->newuser->toBack();
                                return ;
                            }
                        } else {
                              if (!str::contains($email , '@') && $login_server == $login) { //login
                                $store->login->text = 'Выйти из аккаунта';
                                $store->login->graphic = new UXImageView(new UXImage('res://.data/img/Exit.png'));
                                $store->hidePreloader();
                                $store->newuser->toBack();
                                return ;
                            } elseif (str::contains($email , '@') && $email_server == $email) { //email
                                $store->login->text = 'Выйти из аккаунта';
                                $store->login->graphic = new UXImageView(new UXImage('res://.data/img/Exit.png'));
                                $store->hidePreloader();
                                $store->newuser->toBack();
                                return ;
                            }    else {
                                    Logger::error('Ошибка неверный пароль!');
                                    $store->toast('Неверный пароль' , 'ERROR');
                                    $store->login->text = 'Войти в аккаунт';
                                    $store->login->graphic = new UXImageView(new UXImage('res://.data/img/action.png'));
                                    $store->hidePreloader();
                                    $store->newuser->toFront();
                                    return ;
                                }
                            }
                        }
            } else {
                if ($type == true) {
                    $store->toast('Неверный пароль' , 'ERROR');
                    $store->login->text = 'Войти в аккаунт';
                    $store->login->graphic = new UXImageView(new UXImage('res://.data/img/action.png'));
                    $store->hidePreloader();
                    $store->newuser->toFront();
                    return ;
                } else {
                    app()->getForm(profile)->hide();
                    $store->login->text = 'Войти в аккаунт';
                    $store->login->graphic = new UXImageView(new UXImage('res://.data/img/action.png'));
                    $store->newuser->toFront();
                    return;
                }
            }
            */
            } else {
                if ($type == true) {
                    $store->avatar->image = new UXImage ('res://.data/img/user.png');
                    Logger::error('Ошибка неверный пароль!');
                    $store->toast('Неверный пароль' , 'ERROR');
                    $store->login->text = 'Войти в аккаунт';
                    $store->login->graphic = new UXImageView(new UXImage('res://.data/img/action.png'));
                    $store->hidePreloader();
                    $store->newuser->toFront();
                    return ;
                } else {
                    $store->avatar->image = new UXImage ('res://.data/img/user.png');
                    Logger::error('Ошибка неверный пароль!');
                    $store->login->text = 'Войти в аккаунт';
                    $store->login->graphic = new UXImageView(new UXImage('res://.data/img/action.png'));
                    $store->hidePreloader();
                    $store->newuser->toFront();
                    //delete file bug fix
                    fs::delete('./tdata.json');
                    return ;
                }
            }
        });
    }
    /**
     * Выход из аккаунта
     */
    public function exitAuth () {
        $store = app()->getForm(store);
        $store->showPreloader('Ожидание запроса ...');
        if(uiConfirm('Вы точно хотите выйти из аккаунта ?')){
            fs::delete('./tdata.json');
            $store->avatar->image = new UXImage ('res://.data/img/user.png');
            Logger::info('Успешно вышли из аккаунта!');
            $store->hidePreloader();
            $this->checkauth();
        } else {
            $store->hidePreloader();
        }
    }
}
