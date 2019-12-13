<?php
use app, std, framework, gui;

function main () {
    $ver = '0.0.2v';
    $api = new app\classes\jTelegramApi;
    if (str::contains($GLOBALS['telegram_text'] , '@')) {
        $txt = explode('@' , $GLOBALS['telegram_text']);
    } else {
        $txt = explode(' ' , $GLOBALS['telegram_text']);
    }
    if($txt[0] == '/lang') {
        if ($txt[1] == 'help') {
            help($api , $ver);
        } elseif (strlen($txt[1]) == 2) {
            langauto($api, $txt, $ver);
        } elseif ($txt[1] == 'default' && strlen($txt[2]) == 2) {
            setDefault($api, $txt[2]);
        } else {
            help($api , $ver);
        }
    } else {
        foreach ($txt as $key) {
            $a .= $key . ' ';
        }
        $yandex = new bundle\yandextranslate\YandexTranslate($api->getTokenYandex());
        $text = $yandex->translate($a, getDefault()); // Вернет переведенный текст или произойдет иключение
        $api->sendMessage_id($api->getChatid(), $text);
    }
}

/**
 * Перевод с анг на рус 
 */
function langauto ($api, $txt, $ver) {
    $lang = $txt[1];
    array_shift($txt);
    array_shift($txt);
    foreach ($txt as $key) {
        $a .= $key . ' ';
    }
    $yandex = new bundle\yandextranslate\YandexTranslate($api->getTokenYandex());
    $text = $yandex->translate($a, $lang); // Вернет переведенный текст или произойдет иключение
    $api->sendMessage_id($api->getChatid(), $text);
}

/**
 * Установить стандартный язык перевода
 * -------------------------------------
 * api        -    jTelegram
 * default    -    Язык перевода
 */
function setDefault ($api, $default) {
    if ($GLOBALS['__LANG_DEFAULT'] != $default) {
        $GLOBALS['__LANG_DEFAULT'] = $default;
        $api->sendMessage_id($api->getChatid() , "Успешно установлен стандартный язык перевода $default");
    } else {
        $api->sendMessage_id($api->getChatid() , "Стандартный язык перевода уже установлен $default");
    }
}

/**
 * Возвращаем язык перевода
 * -------------------------
 */
function getDefault() {
    if (!$GLOBALS['__LANG_DEFAULT']) {
        return 'ru';
    } else {
        return $GLOBALS['__LANG_DEFAULT'];
    }
}

/**
 * Возвращаем подсказку 
 */
function help($api , $ver) {
    $api->sendArrayText_id($api->getChatid() , [
        0 => "[lang $ver] - Переводчик",
        1 => '/lang en | Перевод с en на стандартный',
        2 => '/lang default ru | Установить стандартный перевод',
        3 => '/lang help | Список команд',
    ]);
}