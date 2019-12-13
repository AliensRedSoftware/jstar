<?php
use app, std, framework, gui;

function main () {
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        $api->sendArrayText_id($api->getChatid() , [
        0 => '[version 0.0.1v] - Версия модуля',
        1 => '/ver | Вывести список модулей и версия jphp',
        2 => '/ver help | Список команд',
        ]);
    }else {
        $api->SendMessage_id($api->getChatid() , $api->ArrayeachLine(getVersions()));
    }
}

function getVersions () {
    $api = new app\classes\jTelegramApi;
    $list = [
        "[jppm => " . constant('JPHP_VERSION') . ']' ,
        "[Установлена всего модулей => " . count($api->getListModules()) . ']' . '[Telegram_api]' ,
    ];
    $i = 0;
    foreach ($api->getListModules() as $value) {
        $i++;
        array_push($list, "->[$i]" . "[$value]");
    }
    array_push($list, "[Установлена модулей => " . 0 . ']' . '[vk_api]');
    return $list;
}
