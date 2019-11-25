<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    main();
    $api = new app\modules\vkapi;
    $api->sendmessage_id(452437849, 'test123');
});

function main () {
    $form = new app\modules\Telegram_api;
    $api = new app\classes\jTelegramApi;
    $api->sendmessage_id($form->getChatid() , 'test123');
}