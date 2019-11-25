<?php
use app , std , framework , gui;
use php\jsoup\Jsoup;

UXApplication::runLater(function () {
    main();
});

function main () {
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        $api->sendArrayText_id($api->getChatid() , [
            0 => "[bash 0.0.1v] - Работа с bash цитатником",
            1 => '/bash random | Кидает рандомную цитату',
            2 => '/bash help | Вывести стэк команд',
        ]);
    } elseif (str::startsWith($txt[1] , 'r') || $txt[1] == 'random') {
        random($api , $txt);
    } else {
        $api->sendArrayText_id($api->getChatid() , [
            0 => "[bash 0.0.1v] - Работа с bash цитатником",
            1 => '/bash random | Кидает рандомную цитату',
            2 => '/bash help | Вывести стэк команд',
        ]);
    }
}

function random ($api , $txt) {
    if ($txt[1] == 'random'){
        $doc = Jsoup::connect("https://bash.im/")->get();
        $newsHeadlines = $doc->select(".quote");
        $iteam[];
        foreach ($newsHeadlines as $element) {
            array_push($iteam, $element->text());
        }
        $val = $iteam[rand(1 , count($iteam) - 1)];
        $api->sendMessage_id($api->getChatid() , urldecode($api->eachLine($val , 50)));
    } else {
        $api->sendMessage_id($api->getChatid() , "[/bash 0.0.1v]После команды должно быть random а не =>" . $txt[1]);
    }
}