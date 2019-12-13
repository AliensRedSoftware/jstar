<?php
use app, std, framework, gui;

function main () {
    $api = new app\classes\jTelegramApi;
    UXClipboard::setText(' ');
    $api->sendMessage_id($api->getChatid() , "Пробел успешно скопировался в буфер обмана!");
    
}
