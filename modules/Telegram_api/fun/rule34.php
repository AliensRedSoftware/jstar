<?php
use app, std, framework, gui;

function main () {
    $ver = '0.0.1v';
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'help') {
        $api->sendArrayText_id($api->getChatid() , getHelp($ver));
    } elseif (str::startsWith($txt[1] , 'r') || $txt[1] == 'random') {
        random ($api , $txt);
    } elseif (str::startsWith($txt[1] , 's') || $txt[1] == 'search') {
        getlist ($api , $txt);
    } elseif (str::startsWith($txt[1] , 'count') || $txt[1] == 'count') {
        getcount ($api , $txt , $ver);
    }  else {
        $api->sendArrayText_id($api->getChatid() , getHelp($ver));
    }
}


/**
 * Возвращает помощь 
 */
function getHelp ($ver) {
    return [
        0 => "[/rule34 $ver] - 2д пикчи :)",
        1 => '/rule34 random | Кидает рандомную пикчу',
        2 => '/rule34 random tag тег| Кидает рандомную пикчу с тегом',
        3 => '/rule34 search буква | Вывести теги с похожей буквой',
        4 => '/rule34 count tag | Глянуть сколько кол-во пикч',
        5 => '/rule34 help | Вывести стэк команд',
    ];
}

/**
 * Рандомно кидает пикчу 
 */
function random ($api , $txt) {
    if ($txt[1] == 'random') {
        $url = 'https://rule34hentai.net/post/list/';
        if ($txt [2] == 'tag') {
            $url .= $txt[3] . '/1';
        }
        $jsoup = new script\JsoupScript();
        $jsoup->on('error' , function () use ($api) {
            $api->sendMessage_id($api->getChatid() , "Ничего не найдено :(");
        });
        $jsoup->parseAsync($url , function () use ($jsoup , $api , $url) {
            $response = $jsoup->find('.blockbody')->select('a');
            foreach ($response as $data) {
                if ($data->text() == 'Random') {
                    $post = $data->attr('href');
                }
            }
            $jsoup->parseAsync('https://rule34hentai.net' . $post , function () use ($jsoup , $api , $url) {
                $iteam = [];
                $response = $jsoup->find('.shm-image-list')->select('a');
                foreach ($response as $data) {
                    array_push($iteam , $data->attr('href'));
                }
                $id = $iteam[rand(0 , count($iteam) - 1)];
                Logger::info("Первый уровень парсинга был успешен! id => $id");
                $jsoup->parseAsync ('https://rule34hentai.net' . $id , function () use ($jsoup , $api) {
                    $iteam = [];
                    $response = $jsoup->find('.blockbody')->select('img');
                    foreach ($response as $data) {
                        array_push($iteam , $data->attr('src'));
                    }
                    $image = 'https://rule34hentai.net' . $iteam[1];
                    Logger::info("Второй уровень парсинга был успешен! id => $image");
                    $api->sendPhotoByUrl($api->getChatid() , $image);
                    Logger::info("Успешно парсинг завершился! :)");
                });
            });
        });
    } else {
        $api->sendMessage_id($api->getChatid() , "[/rule34 $ver]После команды должно быть random а не =>" . $txt[1]);
    }
}

/**
 * Вывести список тегов
 */
function getlist ($api , $txt) {
    if ($txt[1] == 'search') {
        $jsoup = new script\JsoupScript();
        $jsoup->on('error' , function () use ($api) {
            $api->sendMessage_id($api->getChatid() , "Ничего не найдено :(");
        });
        $jsoup->parseAsync('https://rule34hentai.net/tags' , function () use ($jsoup , $api , $txt) {
            $iteam = [];
            $iteam1 = [];
            $response = $jsoup->find('.blockbody')->select('a');
            foreach ($response as $data) {
                if (count($iteam) < 150) {
                    if (str::startsWith($data->text() , $txt[2])) {
                        $i++;
                        array_push($iteam , "[$i] =>". trim($data->text()));
                    }
                } else {
                    if (str::startsWith($data->text() , $txt[2])) {
                        $i++;
                        array_push($iteam1 , "[$i] =>". trim($data->text()));
                    }
                }
            }
            if (count ($iteam1) != 0) {
                $api->sendArrayText_id($api->getChatid() , $iteam1);
            }
            $api->sendArrayText_id($api->getChatid() , $iteam);
        });
    } else {
        $api->sendMessage_id($api->getChatid() , "[/rule34 $ver]После команды должно быть search а не =>" . $txt[1]);
    }
}

function getcount ($api , $txt , $ver) {
    if ($txt[1] == 'count') {
        $url = 'https://rule34hentai.net/post/list/';
        if ($txt [2] == 'tag') {
            $url .= $txt[3] . '/1';
        }
        $jsoup = new script\JsoupScript();
        $jsoup->on('error' , function () use ($api) {
            $api->sendMessage_id($api->getChatid() , "Ничего не найдено :(");
        });
        $jsoup->parseAsync($url , function () use ($jsoup , $api , $ver) {
            $response = $jsoup->find('.blockbody')->select('a');
            foreach ($response as $data) {
                if ($data->text() == 'Random') {
                    $post = $data->attr('href');
                }
            }
            $jsoup->parseAsync('https://rule34hentai.net' . $post , function () use ($jsoup , $api , $ver) {
                $count = null;
                $response = $jsoup->find('.shm-image-list')->select('a');
                foreach ($response as $data) {
                    $count++;
                }
                Logger::info("Успешно парсинг завершился! :)");
                $api->sendMessage_id($api->getChatid() , "[/rule34 $ver] [count] => $count");
            });
        });
    } else {
        $api->sendMessage_id($api->getChatid() , "[/rule34 $ver]После команды должно быть count а не =>" . $txt[1]);
    }
}
