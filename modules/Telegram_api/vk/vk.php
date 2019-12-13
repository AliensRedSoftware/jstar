<?php
use app, std, framework, gui;

function main () {
    $ver = '0.0.5v';//Версия модуля
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        help($api, $ver);
    } elseif ($txt[1] == 'getgroupById') {
        getGroupById($api, $txt, $ver, 'getgroupById');
    } elseif ($txt[1] == 'getCountPhoto') {
        getCountPhoto($api, $txt, $ver, 'getCountPhoto');
    } elseif ($txt[1] == 'getRandomPicturesGroup') {
        getRandomPicturesGroup($api, $txt, $ver, 'getRandomPicturesGroup');
    } else {
        help($api, $ver);
    }
}

/**
 * Возвращает подсказку 
 */
function help($api, $ver) {
    $api->sendArrayText_id($api->getChatid(), [
        0 => "[vk $ver] - Работа с vk api",
        1 => '[Группа]',
        2 => '/vk getgroupById url | Возвращает id группы',
        3 => '/vk getCountPhoto id type -> wall , profile , saved | Получить кол-во пикч в группе',
        4 => '/vk getRandomPicturesGroup id count type offset -> wall , profile , saved | Получить рандомную пикчу с группы',
        5 => '/vk help | Список команд',
    ]);
}


/**
 * Возвращает id группы 
 */
function getGroupById ($api , array $txt , $ver , $cmd) {
    if ($txt[1] == 'getgroupById') {
        $vk = new app\modules\vkapi;
        $vk->getByIdAsync ($txt[2], function ($group) use ($api, $ver) {
            if ($group == false) {
                $api->sendMessage_id($api->getChatid(), "[/vk $ver] Ошибка неверный запрос!");
                return ;
            }
            $api->sendMessage_id($api->getChatid(), "[/vk $ver] [Успешно!]-$group");
        });
    } else {
        $api->sendMessage_id($api->getChatid(), "[/vk $ver] [Ошибка] После команды должно быть $cmd а не =>" . $txt[1]);
    }
}

/**
 * Возвращает кол-во пикч в группе
 */
function getCountPhoto ($api, array $txt, $ver, $cmd) {
    if ($txt[1] == 'getCountPhoto') {
        $vk = new app\modules\vkapi;
        $id = $txt[2];//Кол-во
        $album_id = $txt[3];//wall , profile , saved
        switch ($album_id) {
            case 'wall':
                $vk->getCountPhotoAsync($id, $album_id, function ($r) use ($api, $ver) {
                    $api->sendMessage_id($api->getChatid() , "[/vk $ver] [Успешно!] пикч всего на стене => $r");
                });
            break;
            case 'profile':
                $vk->getCountPhotoAsync($id, $album_id, function ($r) use ($api, $ver) {
                    $api->sendMessage_id($api->getChatid() , "[/vk $ver] [Успешно!] пикч всего на стене => $r");
                });
            break;
            case 'saved':
                $vk->getCountPhotoAsync($id, $album_id, function ($r) use ($api, $ver) {
                    $api->sendMessage_id($api->getChatid() , "[/vk $ver] [Успешно!] пикч всего на стене => $r");
                });
            break;
            default:
                $api->sendMessage_id($api->getChatid() , "[/vk $ver] [Ошибка!] wall , profile , saved , => Не найдено!");
                return; 
            break;
        }
    } else {
        $api->sendMessage_id($api->getChatid() , "[/vk $ver] [Ошибка] После команды должно быть $cmd а не =>" . $txt[1]);
    }
}

/**
 * Возвращает рандомную пикчу с группы
 */
function getRandomPicturesGroup ($api , array $txt , $ver , $cmd) {
    if ($txt[1] == 'getRandomPicturesGroup') {
        $vk = new app\modules\vkapi;
        $id = $txt[2];
        $count = $txt[3];
        $album_id = $txt[4];
        $offset = $txt[5];
        $url = $txt[6];
        if (str::isNumber($count) == true) {
            if ($count < 1 || $count < 1000) {
                if ($offset > 0 || !$offset) {
                    if (!$offset || $offset < 0) {
                        $offset = 0;
                    }
                    switch ($album_id) {
                        case 'wall':
                            $vk->getRandomPicturesGroupAsync($id, $count, $album_id, $offset, function ($r) use ($api, $ver) {
                                if ($r == false) {
                                    $api->sendMessage_id($api->getChatid(), "[/vk $ver] Ошибка неверный запрос!");
                                } else {
                                    $api->sendPhotoByUrl($api->getChatid(), $r);
                                }
                            });
                        break;
                        case 'profile':
                            $vk->getRandomPicturesGroupAsync($id, $count, $album_id, $offset, function ($r) use ($api, $ver) {
                                if ($r == false) {
                                    $api->sendMessage_id($api->getChatid(), "[/vk $ver] Ошибка неверный запрос!");
                                } else {
                                    $api->sendPhotoByUrl($api->getChatid(), $r);
                                }
                            });
                        break;
                        case 'saved':
                            $vk->getRandomPicturesGroupAsync($id, $count, $album_id, $offset, function ($r) use ($api, $ver) {
                                if ($r == false) {
                                    $api->sendMessage_id($api->getChatid(), "[/vk $ver] Ошибка неверный запрос!");
                                } else {
                                    $api->sendPhotoByUrl($api->getChatid(), $r);
                                }
                            });
                        break;
                        default:
                            $api->sendMessage_id($api->getChatid(), "[/vk $ver] [Ошибка!] wall , profile , saved , => not found!");
                            return; 
                        break;
                    }
                } else {
                    $api->sendMessage_id($api->getChatid() , "[/vk $ver] [Ошибка!] offset не должно быть меньше 1!");
                    return ;
                }
            } else {
                if ($count > 1000) {
                    $api->sendMessage_id($api->getChatid() , "[/vk $ver] [Ошибка!] count не должно быть больше 1000!");
                } else {
                    $api->sendMessage_id($api->getChatid() , "[/vk $ver] [Ошибка!] count не должно быть меньше 1!");
                }
                return ;
            }
        } else {
            $api->sendMessage_id($api->getChatid() , "[/vk $ver] [Ошибка!] => count");
            return;
        }
    } else {
        $api->sendMessage_id($api->getChatid() , "[/vk $ver] [Ошибка] После команды должно быть $cmd а не =>" . $txt[1]);
    }
}