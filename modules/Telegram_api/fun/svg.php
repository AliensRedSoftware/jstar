<?php
use app, std, framework, gui;

function main () {
    $api = new app\classes\jTelegramApi;
    $txt = explode(' ' , $GLOBALS['telegram_text']);
    if ($txt[1] == 'size'){
        if(str::isNumber($txt[2]) == false) {
            $api->sendmessage_id($api->getChatid() , 'X должно равнятся числу а не ->' . $txt[2]);
            return;
        }
        if (str::isNumber($txt[3]) == false) {
            $api->sendmessage_id($api->getChatid() , 'Y должно равнятся числу а не ->' . $txt[3]);
            return;
        }
        if($txt[4] == 'fillcolor') {
            if(isValidColor($txt[5] , $txt[6] , $txt[7] , $api , 'fillcolor') == true) {
                if ($txt[8] == 'bordercolor') {
                    if (isValidColor($txt[9] , $txt[10] , $txt[11] , $api , 'bordercolor') == true) {
                        if ($txt[12] == 'background') {
                            if (isValidColor($txt[13] , $txt[14] , $txt[15] , $api , 'background') == true) {
                                if ($txt[16] == 'code') {
                                    $x_form = $txt[2];
                                    $y_form = $txt[3];
                                    $fillcolor_r = $txt[5];
                                    $fillcolor_g = $txt[6];
                                    $fillcolor_b = $txt[7];
                                    //Цвет обводки
                                    $bordercolor_r = $txt[9];
                                    $bordercolor_g = $txt[10];
                                    $bordercolor_b = $txt[11];
                                    //Фон цвет
                                    $background_r = $txt[13];
                                    $background_g = $txt[14];
                                    $background_b = $txt[15];
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    array_shift($txt);
                                    foreach ($txt as $text) {
                                        $a .= $text . ' ';
                                    }
                                    //$api->sendmessage_id($api->getChatid() , $a);
                                    if ($a == null) {
                                        $api->sendmessage_id($api->getChatid() , "code пусто :(");
                                        return;
                                    } else {
                                        svgpath($api , $x_form , $y_form , $fillcolor_r, $fillcolor_g , $fillcolor_b , $bordercolor_r , $bordercolor_g , $bordercolor_b , $background_r , $background_g , $background_b , trim($a));
                                    }
                                } else {
                                    $api->sendmessage_id($api->getChatid() , "После команды b  -> должно быть code а не $txt[16]");
                                    return;
                                }
                            }
                        } else {
                            $api->sendmessage_id($api->getChatid() , "После команды b  -> должно быть background а не $txt[12]");
                            return;
                        }
                        svgpath($api , $txt[2] , $txt[3] , $txt[5] , $txt[6] , $txt[7] , $txt[9] , $txt[10] , $txt[11]);
                    }
                } else {
                    $api->sendmessage_id($api->getChatid() , "После команды b  -> должно быть bordercolor а не $txt[8]");
                    return;
                }
            } else {
                $api->sendmessage_id($api->getChatid() , 'Вы не прошли валидность на цветность! | rgb(0 - 255, 0 - 255,0 - 255)');
                return;
            }
        } else {
            $api->sendmessage_id($api->getChatid() , 'После команды Y должно быть fillcolor цвет а не ->' . $txt[4]);
            return;
        }
    } else {
        $api->sendmessage_id($api->getChatid() , 'После команды должно быть size x y а не ->' . $txt[1]);
    }
}

function isValidColor($r , $g , $b , $api , $type) {
    if (str::isNumber($r) == true) {
        if ($r > 255) {
            $api->sendmessage_id($api->getChatid() , "$type r g b -> r не может быть больше 255");
        } elseif ($r < 0) {
            $api->sendmessage_id($api->getChatid() , "$type r g b -> r не может быть меньше 0");
        } else {//Если ок то
            if (str::isNumber($g) == true) {
                if ($g > 255) {
                    $api->sendmessage_id($api->getChatid() , "$type r g b -> g не может быть больше 255");
                } elseif ($g < 0) {
                    $api->sendmessage_id($api->getChatid() , "$type r g b -> g не может быть меньше 0");
                } else {//Если ок то
                    if (str::isNumber($b) == true) {
                        if ($b > 255) {
                            $api->sendmessage_id($api->getChatid() , "$type r g b -> b не может быть больше 255");
                        } elseif ($b < 0) {
                            $api->sendmessage_id($api->getChatid() , "$type r g b -> b не может быть меньше 0");
                        } else {//Если ок то
                            return true;
                        }
                    } else {
                        if ($b == null) {
                            $api->sendmessage_id($api->getChatid() , "$type r g b -> b не число а НИЧЕГО");
                        } else {
                            $api->sendmessage_id($api->getChatid() , "$type r g b -> b не число а $b");
                        }
                        return false;
                    }
                }
            } else {
                $api->sendmessage_id($api->getChatid() , "$type r g b -> g не число а $g");
                return false;
            }
        }
    } else {
        $api->sendmessage_id($api->getChatid() , "$type r g b -> r не число а $r");
        return false;
    }
}

function svgpath ($api , $x , $y , $fillcolor_r , $fillcolor_g , $fillcolor_b , $bordercolor_r , $bordercolor_g , $bordercolor_b , $background_r , $background_g , $background_b , $svg) {
/*
api это апи телеграмм
x ширина формы
y высота формы
Цвет фона
    $fillcolor_r цвет фона фигуры R
    $fillcolor_g цвет фона фигуры G
    $fillcolor_b цвет фона фигуры B
Окантовка
    $bordercolor_r цвет окантовки фигуры R
    $bordercolor_g цвет окантовки фигуры G
    $bordercolor_b цвет окантовки фигуры B
фон задний
    $background_r цвет фона r
    $background_g цвет фона g
    $background_b цвет фона b
*/

    $form = new UXForm();
    $form->title = 'Сообщение :)';
    $form->size = [$x , $y];
    $form->layout->backgroundColor = UXColor::rgb ($background_r , $background_g , $background_b);
    $form->centerOnScreen();
    $uxcanvas = new UXCanvas();
    $y = $form->height - 40;
    $x = $form->width - 20;
    $uxcanvas->size = [$x , $y];
    $form->add($uxcanvas);
    $form->on('show' , function () use ($form , $uxcanvas , $api , $fillcolor_r , $fillcolor_g , $fillcolor_b , $bordercolor_r , $bordercolor_g , $bordercolor_b , $svg) {
        //Рисуем
        // Получаем полотно для рисования.
        $gc = $uxcanvas->gc();
    
        $gc->fillColor = UXColor::rgb($fillcolor_r , $fillcolor_g , $fillcolor_b); // цвет заливки
        $gc->strokeColor = UXColor::rgb($bordercolor_r , $bordercolor_g , $bordercolor_b); // цвет окантовки
        $gc->lineWidth = 3; // ширина линии окантовки.
        
        // Добавляем SVG выражение к пути, рисуем треугольник.
        //$gc->appendSVGPath('M 50 50 L 150 50 L 100 150 z');
        $gc->appendSVGPath($svg);
        // Рисиуем заливку.Z
        $gc->fill();
        
        // Рисуем окантовку (линии).
        $gc->stroke();
        $img = $form->layout->snapshot();
        $img->save('./snapshot');
        $api->sendPhoto_id ($api->getChatid() , './snapshot');
        waitAsync('500' , function () use ($form , $api) {
            $form->hide();
            $api->sendmessage_id($api->getChatid() , 'Успешно!');
        });
    });
    $form->show();
}
