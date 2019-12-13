<?php
use app, std, framework, gui;

function main () {
    $ver = '0.0.4v';//Версия модуля
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if (str::startsWith ($txt[1] , 'h') || $txt[1] == 'help') {
        getHelp($api , $txt[1] , $ver);
    } elseif (str::startsWith(txt[1] , 'g') || $txt[1] == 'get') {
        getModules($api , $txt[1] , $ver);
    } else {
        getHelp($api , 'help' , $ver);
    }
    
}

/**
 * Получение модулей
 */
function getModules ($api , $txt , $ver) {
    if ($txt[1] == 'get') {
        $f[] = null;
        $i = 0;
        foreach ($api->getListModules() as $val) {
            $i++;
            $s = explode('/', $val);
            if ($s[count($s) - 1] == "balance.php") {
                array_push($f , "[$i] [Cистема баланса][/balance help | узнать стэк команд]");
            }if ($s[count($s) - 1] == "echo.php") {
                array_push($f , "[$i] [Вывод сообщение][/echo help | узнать стэк команд]");
            }if ($s[count($s) - 1] == "version.php") {
                array_push($f , "[$i] [Вывод версии][/ver help | узнать стэк команд]");
            }if ($s[count($s) - 1] == "modules.php") {
                array_push($f , "[$i] [Описание каждого модуля][/modules help | узнать стэк команд]");
            }if ($s[count($s) - 1] == "str.php") {
                array_push($f , "[$i] [str - Работа со строкой][/str help | узнать стэк команд]");
            }if ($s[count($s) - 1] == "3d.php") {
                array_push($f , "[$i] [Кинуть 3d тяночку][/3d | узнать стэк команд]");
            }if ($s[count($s) - 1] == "anime.php") {
                array_push($f , "[$i] [Кинуть 2d тяночку][/anime | узнать стэк команд]");
            }if ($s[count($s) - 1] == "bashparser.php") {
                array_push($f , "[$i] [Вывод команд bash][/bash | узнать стэк команд]");
            }if ($s[count($s) - 1] == "url.php") {
                array_push($f , "[$i] [Работа со ссылками][/url | узнать стэк команд]");
            }if ($s[count($s) - 1] == "svg.php") {
                array_push($f , "[$i] [Нарисовать svg рисунок][/svg | узнать стэк команд]");
            }
        }
        $api->sendArrayText_id($api->getChatid() , $f);
    } else {
        $api->sendMessage_id($api->getChatid() , "[/modules $ver]После команды должно быть get а не =>" . $txt[1]);
    }
}

/**
 * Вызывать помощь 
 */
function getHelp($api , $txt , $ver) {
    if ($txt == 'help') {
        $api->sendArrayText_id($api->getChatid() , [
            0 => "[/modules $ver] - Описание каждого модуля" ,
            1 => '/modules get | Вывести описание каждого модуля и команду help' ,
            2 => '/modules help | Список команд' ,
        ]);
    } else {
        $api->sendMessage_id($api->getChatid() , "[/modules $ver]После команды должно быть help а не =>" . $txt[1]);
    }
}