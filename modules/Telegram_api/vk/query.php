<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    main();
});

function main () {
    $vk = new app\modules\vkapi;
    $api = new app\classes\jTelegramApi;
    $count = $vk->getQuery('photos.get' , ['owner_id' => -1 , 'album_id' => 'wall'])['response']['count'];
    $api->sendMessage_id($api->getChatid() , $count);
}