<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    main();
});

function main () {
    $api = new app\classes\jTelegramApi;
    $api->setToken($api->getToken());
    $api->sendMessage_id($api->getChatid() , '[' . $GLOBALS['getname'] . ']' . '[OK] => ' . $GLOBALS['getgroupid']);
}