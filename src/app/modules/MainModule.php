<?php
namespace app\modules;

use facade\Json;
use php\lang\System;
use app\classes\jTelegramApi;
use php\lang\Thread;
use std, gui, framework, app;
use app\modules\vkModule as VK;

class MainModule extends AbstractModule {

    protected $request;//Хуй пойми что за переменная ну она служит длляя запроса если нету контента нету и запроса ЫЫЫ
    
    /**
     * @event dirChooser.action 
     */
    function doDirChooserAction(ScriptEvent $e = null) {//Выбор папок или папки блять
        $form = app()->getForm(Settings);//Ну форма настроек
        $files = fs::scan($e->sender->file , ['extensions' => ['jpg', 'png', 'gif', 'jpeg'], 'excludeDirs' => true]);//Тупа переменная для скана папка = $array
        foreach ($files as $val) {//Типичный фореч изи короче из files в val каждый массив
            $img = str::replace($val, $e->sender->file . '/', "");//А тут замена короче не ебу чт за код ну он не в функционале
            $form->list->items->add($img);//Ну тут тупа короче добавлеие в лист iteams $img конент
            $s = new MainModule ();//s вара типа модуль этот хз зах
        }//ковычка
    }//ковычка

    /**
     * @event fileChooserimg.action 
     */
    function doFileChooserimgAction(ScriptEvent $e = null) { //А тут короче выбор текстового файла там ascii преоброзовывает в eachline 
        $form = app()->getForm(Settings);//А тут форма получение или возвращение её Натстройки
        if ($form->checkbox_img->selected && !$form->magicmodules->selected) {//Ну если типа чекс бокс выбрана и если типа magicmodules выбранный да не выбранный то будет код
            Stream::of($e->sender->file)->eachLine(function($val) use ($form) {//Я ебу что дальше написано ( но это я писал  забыл)
                $form->list->items->add(str::replace(urldecode(trim($val)) , " " , ""));
            });
        } else {
            if ($form->magicmodules->selected) {
                $form->toast('Открыть в режиме модуля нельзя!');
            } else { 
                Stream::of($e->sender->file)->eachLine(function($val) use ($form) {
                    $form->list->items->add($val);
                });
            }
        }
    }

    /**
     * Сохранение ultimate
     */
    function saveultimate ($all , $imgurl , $start) {
        $Settings = app()->getForm(Settings);
        $this->bdini->set('all' , $all , $Settings->bd->selected);
        $this->bdini->set('imageurl' , $imgurl , $Settings->bd->selected);
        $this->bdini->set('start' , $start , $Settings->bd->selected);
        $Settings->toast('Успешно сохранились настройки!');
    }
    
    /**
     * Сохранение бд 
     */
     function savebd ($txt , $bd) {
         $this->bdini->set('key', $txt, $bd);
     }
    
    /**
     * Сохранение настройки 
     */
    function Save() { //сохранение бд всех настроек
        $Settings = app()->getForm(Settings);//Получение формы сеттингс
        $MainForm = app()->getForm(MainForm);//Получение форсы Маинформ
        $ChatSettings = app()->getForm(SettingsChat);//Получение формы настройки формы чата 
        //Male типа для м но я не ебу зах это написал
        //Female типа для ж но я не ебу зах это написал просто копипаст 
        $this->ini->set('FemaleName' , $Settings->NameFemale->text , 'SettingsFemale');//Сохрание имени короче ж это имя бота короче
        $this->ini->set('waitsend' , $ChatSettings->waitsend->value , 'SettingsFemale');//Сохрание время отправки на локал машине
        $this->ini->set('clearchatauto' , $ChatSettings->clearchatauto->selected , 'SettingsFemale');//Сохранение Удаление автоматически чат диалог чтобы засора не была
        $this->ini->set('clearchatauto_interval' , $ChatSettings->intervaltext->value , 'SettingsFemale');//Сохранение Интервал тоже время отправки ну это интервал в чате
        $this->ini->set('dateandtime' , $ChatSettings->datamessage->selected , 'SettingsFemale');//Сохранение это короче время отправки во сколько отправилось когда отправилось и всё
        //Widget виджет настройки
        $this->ini->set('WidgetX' , $Settings->valueX->value , 'SettingsFemale');//Ну тута просто виджет настройки перемещение ботище на x
        $this->ini->set('WidgetY' , $Settings->valueY->value , 'SettingsFemale');//Тута также только Y
        //AlwaysOnTop поверх всех окон
        $this->ini->set('AlwaysOnTop' , $MainForm->alwaysOnTop , 'view');//Чексбокс сохранеие поверх или нет
        //colorAdjustEffect цветовая коррекция скриптик для смены короче цветов ботище
        $this->ini->set('selected' , $Settings->colorcorrection->selected , 'colorAdjustEffect');//переключатель блять
        $this->ini->set('brightness' , $Settings->brightness->value , 'colorAdjustEffect');//Яркость
        $this->ini->set('contrast' , $Settings->contrast->value , 'colorAdjustEffect');//Контрактность
        $this->ini->set('hue' , $Settings->hue->value , 'colorAdjustEffect');//ХУЕ
        $this->ini->set('saturation' , $Settings->saturation->value , 'colorAdjustEffect');//насыщение
        //shadowEffect  эффекты блять теней
        $this->ini->set('selected' , $Settings->selectedShadow->selected , 'shadowEffect'); //переключатель!!
        $this->ini->set('color' , $Settings->colorPicker->value , 'shadowEffect');//цвета
        $this->ini->set('radius' , $Settings->radiusShadow->value , 'shadowEffect');//Изи радиус
        $this->ini->set('Intensity' , $Settings->Intensity->value , 'shadowEffect');//Интенсивность
        $this->ini->set('offsetX' , $Settings->transetX->value , 'shadowEffect');//Смещение х
        $this->ini->set('offsetY' , $Settings->transetY->value , 'shadowEffect');//смещение y
        //skin СКИНЫ Ботище 
        $this->ini->set('path' , 'skin' . fs::separator() . $Settings->Category_skin->selected . fs::separator() . $Settings->skin->selected , 'SettingsFemale');//Установка скина
        $this->ini->set('size' , $MainForm->size , 'SettingsFemale');//Размер
        //vk панель
        $this->ini->set('longpoll' , $Settings->longpoll->selected , 'vk');//Лонгполл сервер
        $this->ini->set('selectedGroupandUsert' , $Settings->groupandid->value , 'vk');//Установить в группу кидать или нет ? или тип в лс
        $this->ini->set('id' , $Settings->idgroup->selected , 'vk');//id группу
        $this->ini->set('prefix' , $Settings->prefix->text , 'vk');//Префикс при написаний блять
        //telegram телега 
        $this->ini->set('proxyTelegram' , $Settings->proxyTelegram->text , 'telegram');//Прокси если типа провайдер блочит то юзай эту хрень
        $this->ini->set('token' , $Settings->token->text , 'telegram');//Токен ну это надо
        $this->ini->set('chatid' , $Settings->chatid->text , 'telegram');//Чат id от кого получила ссобщение и отправила тож
        $this->ini->set('prefix' , $Settings->prefixtelegram->selected , 'telegram');
        $this->ini->set('chatid_look' , $Settings->chatid_look->selected , 'telegram');//Закрепить id и отправлять только ему
        //Тема
        $this->ini->set('colorpanel' , $Settings->colorPicker_panel->value , 'theme');
        $this->ini->set('colorbackground' , $Settings->colorPicker_background->value , 'theme');
        Logger::info('[Настройки] => сохранились успешно!');
    }
    
    /**
     * Загрузка настройки
     */
    function Load() {//Ебанная загрузка настроек изи написать
        $Settings = app()->getForm(Settings);//Получаем форму Settings
        $MainForm = app()->getForm(MainForm);//Получаем форму MainForm
        //------------------------------------------
            $MainForm->title = $this->ini->get('FemaleName' , 'SettingsFemale');//Название формы равняется имени бота
        //------------------------------------------
        $ChatSettings = app()->getForm(SettingsChat);//Получаем форму SettingsChat
        //MaleName опять тутат 
        //FemaleName fem загрузка
        $Settings->NameFemale->text = $this->ini->get('FemaleName' , 'SettingsFemale');//Загрузка имени ботмище
        $ChatSettings->waitsend->value = $this->ini->get('waitsend' , 'SettingsFemale');//Загрузка время ожиданий
        $ChatSettings->clearchatauto->selected = $this->ini->get('clearchatauto' , 'SettingsFemale');//Удаление автоматом мусор диалог чата
        $ChatSettings->intervaltext->value = $this->ini->get('clearchatauto_interval' , 'SettingsFemale');//Интервал ну локал хост время ожидание запроса
        $ChatSettings->datamessage->selected = $this->ini->get('dateandtime' , 'SettingsFemale');//лоад и это все лоад ну да datamessage дата отправки сообщение когда во сколько была сообщение и тд
        //Widget виджеты расположение тяночки )))
        $Settings->valueX->value = $this->ini->get('WidgetX' , 'SettingsFemale');//ПО Х
        $Settings->valueY->value = $this->ini->get('WidgetY' , 'SettingsFemale');//ПО Y
        //widgetLoad Виджеты грузка разгрузка
        $MainForm->x = $this->ini->get('WidgetX' , 'SettingsFemale');//Расположение по Х
        $MainForm->y = $this->ini->get('WidgetY' , 'SettingsFemale');//Расположение по Y формы маинформас
        //leafeffect типа листик эффектор
        //colorAdjustEffect думаю тут понятно
        $Settings->colorcorrection->selected = $this->ini->get('selected' , 'colorAdjustEffect');//Селектед ини загрузка
        $Settings->brightness->value = $this->ini->get('brightness' , 'colorAdjustEffect');//ЯрКость загрузка
        $Settings->contrast->value = $this->ini->get('contrast' , 'colorAdjustEffect');//Контрактность
        $Settings->hue->value = $this->ini->get('hue' , 'colorAdjustEffect');//Хуе
        $Settings->saturation->value = $this->ini->get('saturation' , 'colorAdjustEffect');//Насыщение загрузка
        //shadowEffect тени эффектор
        $Settings->selectedShadow->selected = $this->ini->get('selected' , 'shadowEffect');//чекер
        $Settings->colorPicker->value = UXColor::of($this->ini->get('color' , 'shadowEffect'));//колопиккер загрузчик
        $Settings->radiusShadow->value = $this->ini->get('radius' , 'shadowEffect');//Радиус
        $Settings->Intensity->value = $this->ini->get('Intensity' , 'shadowEffect');//Размер ну дальность
        $Settings->transetX->value = $this->ini->get('offsetX' , 'shadowEffect');//Смешение по Х
        $Settings->transetY->value = $this->ini->get('offsetY' , 'shadowEffect');//Смещение по Y
        //AlwaysOnTop Поверх экрана окон 
        $MainForm->alwaysOnTop = $this->ini->get('AlwaysOnTop' , 'view');//Загрзука вроверх экрана 
        //bd бд
        $Settings->bd->itemsText = $this->bdini->get('key' , 'section');//Загрузка секций
        $Settings->bd->selectedIndex = 0;//Секция равно 0
        $Settings->selectedbd();//Выбранная бд )
        //Skin
        $pathskin = str::split($this->ini->get('path' , 'SettingsFemale') , fs::separator());//Split окно чё блять )
        $Settings->Category_skin->selected = $pathskin[1];//Категория скинов 
        $Settings->skin->selected = $pathskin[2];//Выбранный скин
        $MainForm->image->image = new UXImage($this->ini->get('path' , 'SettingsFemale'));//Картинка загрузка 
        $MainForm->image->size = $this->ini->get('size' , 'SettingsFemale');//Размер ботще
        $Settings->sizeimage->value = $MainForm->image->size[0];//Размер пикчи
        //vk вк
        $Settings->groupandid->value = $this->ini->get('selectedGroupandUsert' , 'vk'); // группка идов
        $Settings->idgroup->selected = $this->ini->get('id' , 'vk');//Ид группа ..
        $Settings->prefix->text = $this->ini->get('prefix' , 'vk');//Префикс и загрузка
        $this->vklogin();//вк загрузка
        //telegram телега 
        $Settings->proxyTelegram->text = $this->ini->get('proxyTelegram' , 'telegram');//Прокси ну это чтобы можно была авторизоваться короче
        $Settings->token->text = $this->ini->get('token' , 'telegram');//Токен нужно от бота и всё если ты бот можешь кинуть свой токен )
        $Settings->chatid->text = $this->ini->get('chatid' , 'telegram');//Чат ид просто чат ид всё блять что тут описывать ?
        $Settings->prefixtelegram->selected = $this->ini->get('prefix' , 'telegram');//Сохраняет префикс
        $Settings->chatid_look->selected = $this->ini->get('chatid_look' , 'telegram');//Установка закрепа
        //theme
        $theme = new theme();
        $Settings->colorPicker_panel->value = UXColor::of($this->ini->get('colorpanel' , 'theme'));
        $Settings->colorPicker_background->value = UXColor::of($this->ini->get('colorbackground' , 'theme'));
        $theme->EsetTheme($Settings->colorPicker_background->value , $Settings->colorPicker_panel->value);
        //Проверка обновление
        $update = new update();//Создается новый упдате модуль чтобы его юзать пожалуйста воспользуйтесь командой Модули и всё там команды
        $update->updatecheck();//Проверка обновление
        Logger::info('[Настройки] => загрузка успешно!');
    }//Скобка закрылось
    //Скобка закрыть блять заебало уже писать этуй хуйню пойду лучше на пикабу
    function getmoduleslist() {
        $Settings = app()->getForm(moduleslist);
        $Settings->moduleiteam->items->clear();
        $modules = fs::scan('modules' . fs::separator(), ['extensions' => ['php'], 'excludeDirs' => true]);
        foreach ($modules as $module) {
            $Settings->moduleiteam->items->add(str::replace($module , '\\' , '/'));
        }
        $Settings->moduleiteam->selectedIndex = 0;
    }
    
    function LoadLeaf($e) {
        //colorAdjustEffect
        if($this->ini->get('selected' , 'colorAdjustEffect') == false) {
            $e->colorAdjustEffect->disable();
            $e->colorAdjustEffect->brightness = $this->ini->get('brightness' , 'colorAdjustEffect');
            $e->colorAdjustEffect->contrast = $this->ini->get('contrast' , 'colorAdjustEffect');
            $e->colorAdjustEffect->hue = $this->ini->get('hue' , 'colorAdjustEffect');
            $e->colorAdjustEffect->saturation = $this->ini->get('saturation' , 'colorAdjustEffect');
        }
        elseif($this->ini->get('selected' , 'colorAdjustEffect') == true) {
           $e->colorAdjustEffect->enable();
           $e->colorAdjustEffect->brightness = $this->ini->get('brightness' , 'colorAdjustEffect');
           $e->colorAdjustEffect->contrast = $this->ini->get('contrast' , 'colorAdjustEffect');
           $e->colorAdjustEffect->hue = $this->ini->get('hue' , 'colorAdjustEffect');
           $e->colorAdjustEffect->saturation = $this->ini->get('saturation' , 'colorAdjustEffect');
        }
        //shadowEffect
        if($this->ini->get('selected' , 'shadowEffect') == false) {
            $e->dropShadowEffect->disable();
            $e->dropShadowEffect->color = $this->ini->get('color' , 'shadowEffect');
            $e->dropShadowEffect->radius = $this->ini->get('radius' , 'shadowEffect');
            $e->dropShadowEffect->spread = $this->ini->get('Intensity' , 'shadowEffect');
            $e->dropShadowEffect->offsetX = $this->ini->get('offsetX' , 'shadowEffect');
            $e->dropShadowEffect->offsetY = $this->ini->get('offsetY' , 'shadowEffect');
        }
        elseif($this->ini->get('selected' , 'shadowEffect') == true) {
           $e->dropShadowEffect->enable();
           $e->dropShadowEffect->color = $this->ini->get('color' , 'shadowEffect');
           $e->dropShadowEffect->radius = $this->ini->get('radius' , 'shadowEffect');
           $e->dropShadowEffect->spread = $this->ini->get('Intensity' , 'shadowEffect');
           $e->dropShadowEffect->offsetX = $this->ini->get('offsetX' , 'shadowEffect');
           $e->dropShadowEffect->offsetY = $this->ini->get('offsetY' , 'shadowEffect');
        }
    }
    
    /**
     * Проверка есть ли такое в бд 
     */
     public function checkbd (string $type , string $txt , Settings $Settings) {
         if ($type == 'Телеграмм') {//Проверка типа
             if (!$Settings->Asynx_token->selected) {
                 $this->request = true;
                 return ;
             }
             $GLOBALS['telegram_text'] = $txt;
             if($this->bdini->get('key' , $txt) != null || $Settings->prefixtelegram->selectedIndex != 0 && !str::startsWith($txt , '/')) {
                 if($Settings->prefixtelegram->selected != 'null' && !str::startsWith($txt , '/')) {
                     $text = explode($Settings->prefixtelegram->selected , trim($txt));
                     $Settings->bd->selected = $text[1];
                 } else {
                     $Settings->bd->selected = $txt;
                 }
                 if ($Settings->magicmodules->selected) {
                     $GLOBALS['modules'] = $Settings->bd->selected;
                     $this->request = true;
                 } else {
                     if ($Settings->prefixtelegram->selected == 'null') {
                         $this->request = true;
                     } else {
                         if (str::startsWith($txt , $Settings->prefixtelegram->selected)) {
                             $this->request = true;
                         }
                     }
                 }
            } else {
                if (str::startsWith($txt, '/')) {
                    $txt = explode(' ' , $txt);
                    if (str::contains($txt[0], '@')) {
                        $txt = explode('@' , $txt[0]);
                        $Settings->bd->selected = trim($txt[0]);
                        $GLOBALS['modules'] = $txt[0];
                        $this->request = true;
                        return; 
                    }
                    $Settings->bd->selected = trim($txt[0]);
                    $GLOBALS['modules'] = $txt[0];
                    $this->request = true;
                } else {
                    
                }
            }
        } elseif ($type == 'Вконтакте') {
             if (!$Settings->loginvk->selected) {
                 $this->request = true;
                 return ;
             } else {
                 $this->request = false;
             }
        } else {
            if($this->bdini->get('key' , $txt) != null) {
                $Settings->bd->selected = $txt;
                $Settings->list->itemsText = $this->bdini->get('key' , $txt);
                $this->request = true;
            } else {
                $this->request = false;
            }
        }
    }
     
    /**
     * Отправить сообщение боту
     * @return string 
     */
    public function SendChat(string $type, string $txt) {
        (new Thread(function () use ($type, $txt) {
            uiLater(function () use ($type, $txt) {
                $chat = app()->getForm(chat);
                $Settings = app()->getForm(Settings);
                $settingschat = app()->getForm(SettingsChat);
                $ultimate = app()->getForm(ultimate);//Доп фичи
                $Name = System::getProperty('user.name');
                $FemaleName = $this->ini->get('FemaleName' , 'SettingsFemale');
                if ($type == 'Телеграмм' || $type == 'Вконтакте' || $type == 'Локальный') {
                    $this->checkbd($type, $txt, $Settings);//Проверка бд
                }
                $chat->textArea->appendText($this->genMODX($settingschat, $txt, $Name, $type)  . "\n");//Добавляем блять карл эту модх а не мод-икс так просто правильно читается и была задумно ну короче это в этой переменной этот исходный результат вроде тут понятно 
                $counterror = $chat->counterrorlist->text;
                if ($this->request == true) {
                    waitAsync($this->ini->get('waitsend' , 'SettingsFemale') , function () use ($Settings, $chat, $settingschat, $Name, $type, $ultimate, $txt) {//Отправка блять с ожиданием плюс юзается Сеттингс форма потом чат форма и модх
                        if (str::startsWith($txt, '/')) {
                            $txt = explode(' ', $txt)[0];
                        }
                        $item = new UXListView();
                        $item->itemsText = $this->bdini->get('key', $txt);
                        $magicmodules = $this->bdini->get('magicmodules', $txt);
                        switch ($type) {
                            case 'Телеграмм':
                                if (!$Settings->Asynx_token->selected) {
                                    $chat->textArea->appendText($this->genMODX($settingschat, 'Пожалуйста авторизуйтесь или выбрать тип => Локальный!', 'Бот', $type) . "\n");
                                    return ;
                                }
                                if ($magicmodules) {
                                    //Вызов модуля
                                    $namemodule = $item->items[0];
                                    $module = new Module($namemodule);
                                    $module->call();
                                    $chat->textArea->appendText($this->genMODX($settingschat, "Был выполнен модуль ->$namemodule" , 'Бот', $type) . "\n");
                                    Logger::info("Был выполнен модуль ->$namemodule");
                                    $GLOBALS['execute_modules'] = $item->items[0];
                                } else {
                                    if ($ultimate->alllist->selected) {
                                        $jTelegramApi = new jTelegramApi();
                                        $jTelegramApi->sendEachText_id($jTelegramApi->getChatid() , $item->itemsText, $item->items->count() - 1);
                                        $chat->textArea->appendText($this->genMODX($settingschat, "\n" . $item->itemsText, 'Бот', $type) . "\n");
                                    } elseif ($ultimate->imageurl->selected) {
                                        $jTelegramApi = new jTelegramApi();
                                        $txt = $item->items[rand(0, $item->items->count - 1)];
                                        $jTelegramApi->sendPhotoByUrl($jTelegramApi->getChatid(), $txt);
                                    } else {
                                        $jTelegramApi = new jTelegramApi();
                                        $txt = $item->items[rand(0, $item->items->count - 1)];
                                        $jTelegramApi->sendMessage_id($jTelegramApi->getChatid(), urlencode($txt));
                                        $chat->textArea->appendText($this->genMODX($settingschat, $txt, 'Бот', $type) . "\n");
                                    }
                                }
                            break;
                            case 'Локальный':
                                if ($magicmodules && !$Settings->isFree()) {
                                    //Вызов модуля
                                    $namemodule = $item->items[0];
                                    $module = new Module($namemodule);
                                    $module->call();
                                    $chat->textArea->appendText($this->genMODX($settingschat , "Был выполнен модуль ->$namemodule" , 'Бот' , $type) . "\n");
                                    Logger::info("Был выполнен модуль ->$namemodule");
                                } else {
                                    if ($ultimate->alllist->selected) {
                                        $chat->textArea->appendText($this->genMODX($settingschat , "\n" . $item->itemsText , 'Бот' , $type) . "\n");
                                    } elseif ($ultimate->imageurl->selected) {
                                        //$content = $Settings->list->items[rand(0 ,$Settings->list->items->count() - 1)];
                                    } else {
                                        $txt = $item->items[rand(0, $item->items->count - 1)];
                                        $chat->textArea->appendText($this->genMODX($settingschat, $txt, 'Бот', $type) . "\n");
                                    }
                                }                                  
                            break;
                            case 'Вконтакте':
                                if (!$Settings->loginvk->selected) {
                                    $chat->textArea->appendText($this->genMODX($settingschat , 'Пожалуйста авторизуйтесь или выбрать тип => Локальный!' , 'Бот' , $type) . "\n");
                                    return ;
                                }
                            break;
                        }
                    });
                }
                if ($chat->Echeckerror->selected && $this->getcountbd($txt) < $chat->counterror->value) {
                    if ($chat->errorlist->selectedIndex != -1 && $chat->Echeckbox_errorlist->selected) {
                        if ($counterror >= 1) {
                            foreach ($Settings->list->items->toArray() as $kss) {
                                if ($kss == $txt) {
                                    $chat->text->clear();
                                    return ;
                                }
                            }
                            $Settings->additeambd($chat->text->text , $chat->errorlist->selected);
                        } else {
                            $Settings->addbd($chat->errorlist->selected , [$chat->text->text]);
                        }
                        $chat->errorlist->items->removeByIndex($chat->errorlist->selectedIndex);
                    } else {
                        foreach ($chat->errorlist->items->toArray() as $iteam) {
                            if ($iteam == $txt) {
                                $chat->errorlist->selected = $iteam;
                                return ;
                            }
                        }
                        $chat->errorlist->items->add($txt);
                    }
                    //автостартскриптов
                    foreach ($this->bdini->toArray() as $value) {
                        if ($value['magicmodules'] == true && $value['start'] == true && $value['key'] == $GLOBALS['execute_modules']) {
                            $GLOBALS['start'] = true;
                            $php = $value['key'];
                            Logger::info('[Modules] [Автозапуск] => ' . $php);
                            $module = new Module($php);
                            $module->call();
                        } elseif($value['magicmodules'] == true && $value['start'] == true && $value['key'] != $GLOBALS['execute_modules']) {
                            $GLOBALS['start'] = true;
                            $php = $value['key'];
                            Logger::info('[Modules] [Автозапуск] => ' . $php);
                            $module = new Module($php);
                            $module->call();
                        }
                    }
                    Logger::error('Ошибка такой бд нету!');//Бд нема епт
                }
            });
        }))->start();
    }
    
    /**
     * Получение кол-во бд 
     */
    public function getcountbd ($name) {
        if ($this->bdini->get('key' , $name) != null) {
            $list = str::split($this->bdini->get('key' , $name) , "\n");
            $i = 0;
            foreach ($list as $value) {
                $i++;
            }
            return $i;
        } else {
            return 0;
        }
    }
    
    /**
     * модч генерация ой модх генерация отправки мемотекст )  
     */
    public function genMODX (SettingsChat $settingschat , string $text , string $Name , string $type) { //тут указываем форму чатика текст и ииимя потом тип
        if ($settingschat->typesa->selected) {//А тут я чет долго дрочил
            $modx .= "[$type]";//Типа блять плюс тип к модх
        }
        if ($settingschat->datamessage->selected) {//Блять меня в линуксе бесит cairo-dock нижняя хуйня ня
            $date = Time::now();//магическая переменная которая блять узнает когда отправилось сообщение и во сколько и тд
            $modx .= "[$date]";//Типа блять плюс к модх дата переменная
        } //иф конец сбора информаций
        $modx .= "[$Name]->$text";//Типа блять в последнию очередь эту хуйню пишем так как в последнию очередь надо Имя вывести и ебанный текст отправителя наверное :)
        return $modx;
     }
     
    /**
     * Авто-удаление
     */
    function clearfirstiteam ($Name , $text) {
        $settingschat = app()->getForm(SettingsChat);
        $chat = app()->getForm(chat);
        if ($settingschat->clearchatauto->selected) {
            $chat = app()->getForm(chat);
            if ($settingschat->datamessage->selected) {
                $date = Time::now();
                $chat->textArea->appendText("[$date][$Name]->$text\n");
            } else {
                $chat->textArea->appendText("[$Name]->$text\n");
            }
            $iteam = new UXListView();
            $text = null;
            $i = null;
            foreach (str::split($chat->textArea->text , "\n") as $value) {
                $i++;
                if ($i > app()->getForm(SettingsChat)->intervaltext->value) {
                    $iteam->items->removeByIndex(0);
                    $iteam->items->add($value);
                } else {
                    $iteam->items->add($value);
                }
            }     
            foreach ($iteam->items as $val) {
                $text .= $val . "\n";
            }
            $chat->textArea->text = $text;
        } else {
            if ($settingschat->datamessage->selected) {
                $date = Time::now();
                $chat->textArea->appendText("[$date][$Name]->$text\n");
            } else if (!$settingschat->datamessage->selected) {
                $chat->textArea->appendText("[$Name]->$text\n");
            }
            else {
                $chat->textArea->appendText("[$Name]->$text\n");
            }
        }
    } 
    
    /**
     * Авто-удаление в чате
     */
    function clearfirstiteamprofile () {
        $settingschat = app()->getForm(SettingsChat);
        if ($settingschat->clearchatauto->selected) {
            $chat = app()->getForm(chat);
            $iteam = new UXListView();
            $text = null;
            $i = null;
            foreach (str::split($chat->textArea->text , "\n") as $value) {
                $i++;
                if ($i > app()->getForm(SettingsChat)->intervaltext->value) {
                    $iteam->items->removeByIndex(0);
                    $iteam->items->add($value);
                } else {
                    $iteam->items->add($value);
                }
            }
            foreach ($iteam->items as $val) {
                $text .= $val . "\n";
            }
            $chat->textArea->text = $text;
        } 
    } 
    
    /**
     * Авторизация вк
     */
    function vklogin() {
        $Settings = app()->getForm(Settings);
        $MainForm = app()->getForm(MainForm);
        if(VK::isAuth() == false) {
            $Settings->loginvk->graphic = new UXImageView (new UXImage('res://.data/img/Exit.png'));
            $Settings->longpoll->enabled = false;
        }
        else {
            $Settings->loginvk->graphic = new UXImageView (new UXImage('res://.data/img/action.png'));
            $Settings->longpoll->enabled = true;
            $MainForm->toast('Авторизован вк -> успешно!');
        }
    }
    
    /**
     * Обновление бд у списка
     */
     public function updatebd (UXListView $list, $bd) {
         $list->itemsText = $this->bdini->get('key' , $bd);
     }
}
