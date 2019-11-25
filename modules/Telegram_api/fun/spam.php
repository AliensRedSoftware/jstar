<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    $ver = '0.0.1v';
    main($ver);
});

function main ($ver) {
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        $api->sendArrayText_id($api->getChatid() , getHelp($ver));
    } elseif (str::startsWith($txt[1],'o') || $txt[1] == 'on'){
        spam($txt , $api , $ver);
    } elseif (str::startsWith($txt[1],'o') || $txt[1] == 'off') {
        spam($txt , $api , $ver);
    } else {
        $api->sendArrayText_id($api->getChatid() , getHelp($ver));
    }
}

/**
 * Спам функция 
 */
function spam ($txt , $api , $ver) {
    $timer = new TimerScript();
    if ($txt [1] == 'on') {
        $timer->interval = 4000;
        $timer->repeatable = true;
        $timer->on('action' , function () use ($api , $ver , $txt , $timer) {
            if ($GLOBALS['durachok'] == true) {
                $api->sendSticker_id($api->getChatid() , 'CAADAgADAQADMYo0IOZyUJGEFcCgAg');
                Logger::info('[/spam $ver] => БУУМ');
            } else {
                $timer->free();
            }
        });
        $GLOBALS['durachok'] = true;
        $timer->start();
        Logger::info("[/spam $ver] => запустился");
    } elseif ($txt [1] == 'off') {
        $GLOBALS['durachok'] = false;
        $api->sendMessage_id($api->getChatid() , "/spam $ver] => остоновился дурачок");
    } else  {
        $api->sendMessage_id($api->getChatid() , "[/spam $ver] => Я не понял что блять ?");
    }  
}
/**
 * Возвращает помощь текст
 * @return string 
 */
function getHelp ($ver) {
    return [
        0 => "[/spam $ver] - Работа со строкой",
        1 => '/spam on | Включить спам',
        2 => '/spam off | Отключить спам',
        3 => '/spam help | Список команд'
    ];
}