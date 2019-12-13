<?php
use app, std, framework, gui;

function main () {
    $api = new app\classes\jTelegramApi;
    $api->setToken($api->getToken());
    $api->sendMessage_id($api->getChatid() , '[' . $GLOBALS['getname'] . ']' . '[OK] => ' . $GLOBALS['getgroupid']);
}