<?php
use app, std, framework, gui;
use action\Element; 
use php\io\Stream;
use Exception;

function main () {
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'execute') {
        $api->sendMessage_id($api->getChatid(), jphp($api, $txt));
    } elseif ($txt[1] == 'help') {
        $api->sendArrayText_id($api->getChatid() , [
            0 => "[jphp online 0.0.1v] - Выполнение кода jphp",
            1 => '/jphp execute | Выполнить приложение в виде массива json',
            2 => '/jphp help | Вывести стэк команд',
        ]);
    } else {
        $api->sendArrayText_id($api->getChatid() , [
            0 => "[jphp online 0.0.1v] - Выполнение кода jphp",
            1 => '/jphp execute | Выполнить приложение в виде массива json',
            2 => '/jphp help | Вывести стэк команд',
        ]);
    }
}

/**
 * Возвращаем успешное выполнение скрипта
 * ---------------------------------------
 */
function jphp ($api, $txt) {
    array_shift($txt);
    array_shift($txt);
    foreach ($txt as $val) {
        $arr .= $val . ' ';
    }
    $form    =    new UXForm();
    $panel    =    new UXPanel();
    $form->size        =    [640,480];
    $panel->size    =    $form->size;
    $form->layout->width        =    640;
    $form->layout->backgroundColor = UXcolor::of('white');
    $panel->backgroundColor = UXcolor::of('white');
    $form->add($panel);
    try {
        $arr = Json::decode($arr);
        $i = -1;
        foreach ($arr['content'] as $content) {
            $i++;
            foreach ($arr['content'][$i] as $val => $key) {
                switch ($val) {
                    case 'button':
                        $name    =    $key['value'];
                        $btn    =    new UXButton($name);
                        $x        =    $key['x'];
                        $y        =    $key['y'];
                        $css    =    $key['css'];
                        $btn->x    =    $x;
                        $btn->y    =    $y;
                        $btn->style    =    $css;
                        $panel->add($btn);
                    break;
                    case 'image':
                        $src                =    $key['src'];
                        $auto                =    $key['auto'];
                        $proportional        =    $key['proportional'];
                        $stretch            =    $key['stretch'];
                        $width                =    $key['width'];
                        $height                =    $key['height'];
                        $image                =    new UXImageArea();
                        if ($src) {
                            Element::loadContent($image, $src);
                        }
                        $image->width        =    $width;
                        $image->height        =    $height;
                        $image->autoSize         =    $auto;
                        $image->proportional    =    $proportional;
                        $image->stretch        =    $stretch;
                        $panel->add($image);
                    break;
                }
            }
        }
        $form->on('show', function () use ($api, $panel, $form) {
            $panel->snapshot()->save('screenshot.png');;
            $api->sendPhoto_id($api->getChatId(), 'screenshot.png');
            $form->hide();
    
        });
        $form->show();
    } catch (Exception $e) {
        if ($e->getMessage() == 'java.lang.NullPointerException') {
            return "Кады успешно выполнились :)";
        } else {
            return "Кады не выполнились :( " . $e->getMessage();
        }
    }
    
}