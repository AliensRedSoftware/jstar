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
            0 => '[sticker 0.0.1v] - Настройка стикера )',
            1 => '/sticker send id | Отправить стикер',
        2 => '/sticker get | Получение стикера id',
            3 => '/sticker help | Список команд',
        ]);
    } elseif (str::startsWith($txt[1] , 's') || $txt[1] == 'send') {
        id($api , $txt);
    } else if (str::startsWith($txt[1] , 'g') || $txt[1] == 'get') {
        getid($api , $txt);
    }else {
        $api->sendArrayText_id($api->getChatid() , [
            0 => '[sticker 0.0.1v] - Настройка стикера )',
            1 => '/sticker send id | Отправить стикер',
        2 => '/sticker get | Получение стикера id',
            3 => '/sticker help | Список команд',
        ]);
    }
}

//Отправить стикер id
function id ($api , $txt) {
    if ($txt[1] == 'send') { 
        $api->sendSticker_id($api->getChatid() , $txt[2]);
    } else {
        $api->sendmessage_id($api->getChatid() , "[sticker 0.0.1v]После команды должно быть send а не =>" . $txt[1]);
    }
}

//Получение id стикера
function getid ($api , $txt) {
    if ($txt[1] == 'get') { 
        $id = $api->getstickerid();
        $api->sendMessage_id($api->getChatid() , "[sticker 0.0.1v]id стикера =>$id");
    } else {
        $api->sendmessage_id($api->getChatid() , "[sticker 0.0.1v]После команды должно быть get а не =>" . $txt[1]);
    }

}