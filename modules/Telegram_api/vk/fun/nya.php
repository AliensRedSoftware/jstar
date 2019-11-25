<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    $ver = '0.0.2v';
    main($ver);
});

function main ($ver) {
    $vk = new app\modules\vkapi;
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        help($api , $ver);
    } 
    elseif ($txt[1] == '3d') {
        getRandomNya3d($api , $vk , $txt , $ver);
    }    
    elseif ($txt[1] == '2d') {
        getRandomNya2d($api , $vk , $txt , $ver);
    }
    else {
        help($api , $ver);
    }
}

/**
 * Возвращает подсказку 
 */
function help($api , $ver) {
    $api->sendArrayText_id($api->getChatid() , [
        0 => "[nya $ver] - Ня :)",
        1 => '/nya 3d | Кидает рандомную ня3д',
        2 => '/nya 2d | Кидает рандомную ня 2д',
        3 => '/nya help | Список команд',
    ]);
}

/**
 * Выводит рандомный ня 3д
 */
function getRandomNya3d ($api , $vk , array $txt , $ver) {
    if ($txt[1] == '3d') {
        $count = $vk->getCountPhoto(-168921295 , 'wall') - 1;
        $img = $vk->getRandomPicturesGroup(-168921295 , 1000 , 'wall' , rand(0 , $count));
        $api->sendPhotoByUrl($api->getChatid() , $img);
    } else {
        $api->sendMessage_id($api->getChatid() , "[legs $ver]После команды должно быть 3d а не =>" . $txt[1]);
    }
}

/**
 * Выводит рандомный ня 2д
 */
function getRandomNya2d ($api , $vk , array $txt , $ver) {
    if ($txt[1] == '2d') {
        $count = $vk->getCountPhoto(-119400628 , 'wall') - 1;
        $img = $vk->getRandomPicturesGroup(-119400628 , 50 , 'wall' , rand(0 , $count));
        $api->sendPhotoByUrl($api->getChatid() , $img);
    } else {
        $api->sendMessage_id($api->getChatid() , "[legs $ver]После команды должно быть 2d а не =>" . $txt[1]);
    }
}