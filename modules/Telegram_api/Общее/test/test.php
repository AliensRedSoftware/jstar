<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    main();
});

function main () {
    $api = new app\classes\jTelegramApi;
    $api->sendMessage_id($api->getChatid() , "Привет :)");
}