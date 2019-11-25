<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    $ver = '0.0.1v';
    main($ver);
});

function main ($ver) {
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    $name = $GLOBALS['name'];
    if ($txt[1] == 'help') {
        help($api , $ver);
    } elseif ($txt[1] == 'add') {
        $api->sendMessage_id($api->getChatid() , "[SystemBalance $ver][$name][Пополнен]" . addValue($txt[2]));
    } else {
        $api->sendMessage_id($api->getChatid() , "[SystemBalance $ver][$name] Ваш баланс =>"  . getBalance() . ' кристалов');
    }
}

/**
 * Проверка баланса 
 */
function getBalance () {
    $iduser = $GLOBALS['name'];
    if ($GLOBALS[$iduser . '_balance'] == null) {
        return 0;
    } else {
        return $GLOBALS[$iduser . '_balance'];
    }
}

/**
 * Добавить кристалов кол-во
 */
function addValue ($value) {
    if(str::length($value) < 15) {
        if(str::isNumber($value)) {
            $iduser = $GLOBALS['name'];
            $val = $GLOBALS[$iduser . '_balance'] + $value;
            $GLOBALS[$iduser . '_balance'] = $val;
            return "[OK]" . " => " . $value;
        } else {
            return "[ERROR]" . " => " . 'Можно только цифры';
        }
    } else {
        return "[ERROR]" . " => " . 'Нельзя больше чем 15 символов';
    }
}

/**
 * Возвращает подсказку 
 */
function help($api , $ver) {
    $api->sendArrayText_id($api->getChatid() , [
        0 => "[SystemBalance $ver] - система баланса",
        1 => '/balance | Проверить баланс' ,
        2 => '/balance add кол-во | Добавить баланс',
        3 => '/balance help | Список команд',
    ]);
}