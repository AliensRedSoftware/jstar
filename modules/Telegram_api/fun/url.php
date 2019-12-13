<?php
use app, std, framework, gui;

function main () {
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        $api->sendArrayText_id($api->getChatid(), [
            0 => "[url 0.0.1v] - Работа с url",
            1 => '/url clck ссылка | укорочение url адрес',
            2 => '/url help | Вывести стэк команд',
        ]); 
    } elseif (str::startsWith($txt[1], 'c') || $txt[1] == 'clck') {  
        clck($api, $txt);
    } else {
        $api->sendArrayText_id($api->getChatid(), [
            0 => "[url 0.0.1v] - Работа с url",
            1 => '/url clck ссылка | укорочение url адрес',
            2 => '/url help | Вывести стэк команд',
        ]);
    }
}

function clck($api, $txt) {
    if ($txt[1] == 'clck') {
        (new Thread(function () use ($api, $txt) {
            uiLater(function () use ($api, $txt) {
                    $api->sendMessage_id($api->getChatid(), file_get_contents('https://clck.ru/--?url=' . $txt[2]));
            });
        }))->start();
    } else {
        $api->sendMessage_id($api->getChatid(), "[/url 0.0.1v]После команды должно быть clck а не =>" . $txt[1]);
    }
}