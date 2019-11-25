<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    $ver = '0.0.3v';
    main($ver);
});

function main ($ver) {
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        $api->sendArrayText_id($api->getChatid() , getHelp($ver));
    } elseif (str::startsWith($txt[1] , 'r') || $txt[1] == 'random') {
        random ($api , $txt);
    } else {
        $api->sendArrayText_id($api->getChatid() , getHelp($ver));
    }
}


/**
 * Возвращает помощь 
 */
function getHelp ($ver) {
    return [
        0 => "[/yande $ver] - 2д пикчи :)",
        1 => '/yande random | Кидает рандомную пикчу',
        2 => '/yande random tag тег| Кидает рандомную пикчу с тегом',
        3 => '/yande help | Вывести стэк команд',
    ];
}

/**
 * Рандомно кидает пикчу 
 */
function random ($api , $txt) { //https://yande.re/post?page=2
    if ($txt[1] == 'random') {
        $url = 'https://yande.re/';
        $page = 'post?page=1';
        $post = $url . $page;
        $maxpage = null;
        if ($txt [2] == 'tag') {
            $post .= '&tags=' . $txt[3];
        }
        $jsoup = new script\JsoupScript();
        $jsoup->parseAsync($post , function () use ($jsoup , $txt , $api , $post , $maxpage) {
            $iteam = [];
            $error = $jsoup->findFirst ('.content')->select('p');
            if ($error->html() == 'Nobody here but us chickens!'){
                $api->sendMessage_id($api->getChatid() , "Ничего не найдено :(");
                return ;
            }
            if ($jsoup->find('.pagination')->html() == null) {
            
            } else {
                $response = $jsoup->findLast('.pagination')->select('a');
                foreach ($response as $data) {
                    array_push($iteam , $data->text());
                }
                $page = $iteam[count($iteam) - 2];
                $post = str::replace($post , '1' , rand(1 ,$page));
            }
            $jsoup->parseAsync($post , function () use ($jsoup , $txt , $api) {
                $iteam = [];
                $response = $jsoup->find('#post-list-posts')->select('li');
                foreach ($response as $data) {
                    array_push($iteam , str::sub($data->attr('id') , 1));
                }
                $id = $iteam[rand(0 , count($iteam) - 1)];
                Logger::info("Первый уровень парсинга был успешен! id => $id");
                $jsoup->parseAsync ('https://yande.re/post/show/' . $id , function () use ($jsoup , $txt , $api) {
                    $type = [];
                    $response = $jsoup->find('.sidebar')->select('a');
                    foreach ($response as $data) {
                        if ($data->attr('id') != null) {
                            array_push($type , $data->attr('href'));
                        }
                    }
                    $image = $type[0];
                    Logger::info("Второй уровень парсинга был успешен! id => $image");
                    $api->sendPhotoByUrl($api->getChatid() , $image);
                    Logger::info("Успешно парсинг завершился! :)");
                });
            });
        });
    } else {
        $api->sendMessage_id($api->getChatid() , "[/yande $ver]После команды должно быть random а не =>" . $txt[1]);
    }
}