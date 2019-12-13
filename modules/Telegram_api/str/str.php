<?php
use app, std, framework, gui;

function main () {
    $ver = '0.0.5v';//Версия модуля
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        $api->sendArrayText_id($api->getChatid() , getHelp($ver));
    } elseif (str::startsWith($txt[1],'l') || $txt[1] == 'length'){
        StrLength($api, $txt, $ver);
    } elseif ($txt[1] == 'random') {
        StrRandom($api, $txt, $ver);
    } elseif ($txt[1] == 'reverse') {
        StrReverse($api, $txt, $ver);
    } elseif (str::startsWith($txt[1],'u') || $txt[1] == 'uuid') {
        StrUUID($api, $txt, $ver);
    } elseif (str::startsWith($txt[1],'r') || $txt[1] == 'rand') {
        StrRand($api, $txt, $ver);
    } else {
        $api->sendArrayText_id($api->getChatid(), getHelp($ver));
    }
}

//Вывести кол-во символов
function StrLength ($api , $txt , $ver) {
    if ($txt[1] == 'length') {
        array_shift($txt);
        array_shift($txt);
        foreach ($txt as $ms) {
            $a .= $ms . ' ';
        }
        $text = trim($a);
        $api->sendmessage_id($api->getChatid() , "[str $ver] В [$text] Символов нуу ->" . str::length($text));
    } else {
        $api->sendmessage_id($api->getChatid() , "[str $ver]После команды должно быть length а не ->" . $txt[1]);
    }
}

//Сгенерировать пароль из символов
function StrRandom ($api , $txt , $ver) {
    if ($txt[1] == 'random') {
        if ($txt[2] > 2000) {
            $api->sendMessage_id($api->getChatid() , "[str $ver]Ошибка , число больше 2к");
            return;
        }
        if (str::isNumber($txt[2]) == false) {
            $api->sendMessage_id($api->getChatid() , "[str $ver]Ошибка , после random должно быть кол-во генераций");
            return;
        }
        if (str::length($txt[3]) > 32) {
            $api->sendMessage_id($api->getChatid() , "[str $ver]Ошибка , string больше 32 символаааа");
            return;
        }
        $value = str::random($txt[2], $txt[3]);
        $api->sendMessage_id($api->getChatid() , $value);
    } else {
        $api->sendMessage_id($api->getChatid() , "[str $ver]После команды должно быть random а не =>" . $txt[1]);
    }
}

//Перевернуть строку
function StrReverse ($api , $txt , $ver) {
    if ($txt[1] == 'reverse') {
        array_shift($txt);
        array_shift($txt);
        foreach ($txt as $ms) {
            $a .= $ms . ' ';
        }
        $text = trim($a);
        $api->sendmessage_id($api->getChatid() , "[str $ver]Перевернулась => [$text] в " . str::reverse($text));
    } else {
        $api->sendMessage_id($api->getChatid() , '[str $ver]После команды должно быть reverse а не =>' . $txt[1]);
    }
}

//Сгенерировать uuid
function StrUUID ($api , $txt , $ver) {
    if ($txt[1] == 'uuid') {
        $api->sendMessage_id($api->getChatid() , "[str $ver] uuid =>" . str::uuid());
    } else {
        $api->sendMessage_id($api->getChatid() , "[str $ver]После команды должно быть uuid а не =>" . $txt[1]);
    }
}

/**
 * Рандомное число 
 */
function StrRand ($api , $txt , $ver) {
    if ($txt[1] == 'rand') {
        if (str::isNumber($txt[2]) == false) {
            $api->sendMessage_id($api->getChatid() , "[str $ver]Ошибка , после \"rand\" должно быть от");
            return;
        }
        if (str::isNumber($txt[3]) == false) {
            $api->sendMessage_id($api->getChatid() , "[str $ver]Ошибка , после \"rand\" должно быть до");
            return;
        }
        if ($txt[2] < 0) {
            $api->sendMessage_id($api->getChatid() , "[str $ver]Ошибка , \"от\" не должно быть меньше нуля");
            return ;
        }
        if ($txt[3] > 99999999999) {
            $api->sendMessage_id($api->getChatid() , "[str $ver]Ошибка , \"до\" не должно быть больше 99999999999");
            return ; 
        }
        $api->sendMessage_id($api->getChatid() , "[str $ver] rand =>" . rand($txt[2] , $txt[3]));
    } else {
        $api->sendMessage_id($api->getChatid() , "[str $ver]После команды должно быть rand а не =>" . $txt[1]);
    }
}

/**
 * Возвращает помощь текст
 * @return string 
 */
function getHelp ($ver) {
    return [
        0 => "[/str $ver] - Работа со строкой",
        1 => '/str length кол-во | Вывести кол-во символов',
        2 => '/str random кол-во текст | Сгенерировать пароль из символов',
        3 => '/str reverse кол-во | Перевернуть строку',
        4 => '/str uuid | Сгенерировать uuid',
        5 => '/str rand от до| Рандомное число 0 до 10',
    ];
}
