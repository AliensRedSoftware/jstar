<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    main();
});

function main () {
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        $api->sendArrayText_id($api->getChatid() , [
            0 => '[Echo 0.0.2v] - вывод сообщение',
            1 => '/echo msg текст | Отправить сообщение',
            2 => '/echo help | Список команд',
        ]);
    } elseif (str::startsWith($txt[1] , 'm') || $txt[1] == 'msg') {
        msg($api , $txt);
    } else {
        $api->sendArrayText_id($api->getChatid() , [
            0 => '[Echo 0.0.2v] - вывод сообщение',
            1 => '/echo msg текст | Отправить сообщение',
            2 => '/echo help | Список команд',
        ]);
    }
}

//Отправить сообщение
function msg ($api , $txt) {
    if ($txt[1] == 'msg') {
        array_shift($txt);
        array_shift($txt);
        foreach ($txt as $text) {
            $s .= $text . ' ';
        }
        $api->sendmessage_id($api->getChatid() , trim($s));
    } else {
        $api->sendmessage_id($api->getChatid() , "[Echo 0.0.2v]После команды должно быть echo а не =>" . $txt[1]);
    }
}