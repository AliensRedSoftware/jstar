<?php
use app, std, framework, gui;

function main () {
    $ver = '0.0.2v';
    $vk = new app\modules\vkapi;
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ', $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        help($api, $ver);
    } 
    elseif ($txt[1] == '3d') {
        getRandomLegs3d($api, $vk, $txt, $ver);
    }    
    elseif ($txt[1] == '2d') {
        getRandomLegs2d($api, $vk, $txt, $ver);
    }
    else {
        help($api, $ver);
    }
}

/**
 * Возвращает подсказку 
 */
function help($api , $ver) {
    $api->sendArrayText_id($api->getChatid(), [
        0 => "[legs $ver] - Работа с чулочками",
        1 => '/legs 3d | Кидает рандомную чулочку 3д',
        2 => '/legs 2d | Кидает рандомную чулочку 2д',
        3 => '/legs help | Список команд',
    ]);
}

/**
 * Выводит рандомный чулочек 3д
 */
function getRandomLegs3d ($api, $vk, array $txt, $ver) {
    if ($txt[1] == '3d') {
        $vk->getCountPhotoAsync(-102853758, 'wall', function ($count) use ($api, $vk) {
            $vk->getRandomPicturesGroupAsync(-102853758, 1000, 'wall', rand(0, $count - 1), function ($img) use ($api) {
                $api->sendPhotoByUrl($api->getChatid(), $img);
            });
        });
    } else {
        $api->sendMessage_id($api->getChatid(), "[legs $ver]После команды должно быть 3d а не =>" . $txt[1]);
    }
}

/**
 * Выводит рандомный чулочек 2д
 */
function getRandomLegs2d ($api, $vk, array $txt, $ver) {
    if ($txt[1] == '2d') {
        $vk->getCountPhotoAsync(-154897245, 'wall', function ($count) use ($api, $vk) {
            $vk->getRandomPicturesGroupAsync(-154897245, 1000, 'wall', rand(0, $count - 1), function ($img) use ($api) {
                $api->sendPhotoByUrl($api->getChatid(), $img);
            });
        });
    } else {
        $api->sendMessage_id($api->getChatid(), "[legs $ver]После команды должно быть 2d а не =>" . $txt[1]);
    }
}