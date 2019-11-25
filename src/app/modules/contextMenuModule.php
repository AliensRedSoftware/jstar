<?php
namespace app\modules;

use std, gui, framework, app;
//Модуль контекстное меню
class contextMenuModule extends AbstractModule {

    /**
     * @event action 
     */
    function doAction(ScriptEvent $e = null) {    
        Logger::info('Модуль контекстное меню вызван!');
    }
    
    protected $menu;//Переменная меню открыта или нет
    
    function Menu($bool) {
        $this->menu = $bool;
        $GLOBALS['menu'] = $bool;
    }
    function getMenu() {
        return $GLOBALS['menu'];
    }
    
    function ContextMenuShow($e = null , $x = null, $y = null) { //Функция показания контекстного меню
        if($this->getMenu() == false) { //делаем проверку на показание главного меню
            $MainModule = new MainModule();//Подключаем главный модуль
            $updateModule = new update();//Подключаем модуль обновление
            $icopack = new icopack();
            //-------------------------------------------------------------------------------
                Logger::info('Контекстное меню открыто!');
            //-------------------------------------------------------------------------------
            $contextMenu = new UXContextMenu();//Создаем ContexMenu
            $menuUpdate = new UXMenuItem('Проверить обновление' , new UXImageView (new UXImage('ico' . fs::separator() . 'default' . fs::separator() . $icopack->getDefault() . fs::separator() . 'update.png')));//Создаем item + иконка
            //Событие при нажатие
            $menuUpdate->on('action', function () use ($updateModule){ //Событие
                $updateModule->updatecheck();//Проверка обновление
            });
            $contextMenu->items->add($menuUpdate);//Добавляем обновление в меню
            $menuAlwaysOnTop = new UXMenuItem('Поверх всех окон' , new UXImageView (new UXImage('ico' . fs::separator() . 'default' . fs::separator() . $icopack->getDefault() . fs::separator() . 'screen.png')));//Создаем item + иконка
            //Событие при нажатие
            $menuAlwaysOnTop->on('action', function () use ($MainModule){//Событие + подключаем Главный модуль по идеи его название должно быть core но Я его назвал MainModule хз почему :)
                $MainForm = app()->getForm(MainForm);//возвращаем главную форму
                if($MainForm->alwaysOnTop == true) { //Поверх окон ли ?) если да то...
                    $MainForm->alwaysOnTop = false;//Выключаем поверх окон
                    $MainForm->toast('Поверх экрана->Выкл');//показываем тост сообщение
                }
                else { //Если не поверх окон то...
                    $MainForm->alwaysOnTop = true;//Делаем поверх окон
                    $MainForm->toast('Поверх экрана->Вкл');//показываем тост сообщение
                }
                $MainModule->Save();//Сохранение изменение в ini
            });
            $contextMenu->items->add($menuAlwaysOnTop);//Добавляем поверх окон в меню
            //-------------------------------------------------------------------------------
                $contextMenu->items->add(UXMenuItem::createSeparator());//Создаем сеппаратор
            /*/-------------------------------------------------------------------------------
            $menuurl = new UXMenuItem('Открыть URL' , new UXImageView (new UXImage('ico/url.png')));//Создаем item + иконка
            //Событие при нажатие
            $menuurl->on('action', function () { //Событие
                $UrlForm = app()->getForm(urlform);//Получаем форму urlform
                //Animation EFFECT FADEIN
                $UrlForm->opacity = 0;//Прозрачность на 0
                Animation::fadeIn($UrlForm , 1000);//Делаем плавное перемещение прозрачности на 1
                $UrlForm->show();//Заведомо показываем саму форму
                $this->Menu(true);//Включаем запрет на показывание menu ;)
            });
            $contextMenu->items->add($menuurl);//Добавляем menuurl в меню
            //Событие при нажатие
            $menuformagic = new UXMenuItem('Открыть formagic' , new UXImageView (new UXImage('ico/formagic.png')));//Создаем item + иконка
            $menuformagic->on('action', function () {//Событие
                $formagic = app()->getForm(formagic);//Получаем форму formagic
                //Animation EFFECT FADEIN
                $formagic->opacity = 0;//Прозрачность на 0
                Animation::fadeIn($formagic , 1000);//Делаем плавное перемещение прозрачности на 1
                $formagic->show();//Заведомо показываем саму форму
                $this->Menu(true);//Включаем запрет на показывание menu ;)
            });
            $contextMenu->items->add($menuformagic);//Добавляем formagic в меню
            $menukeymagic = new UXMenuItem('Открыть клавишный тренажер' , new UXImageView (new UXImage('ico/keyboard.png')));//Создаем item + иконка
            //Событие при нажатие
            $menukeymagic->on('action', function () {//Событие
                $keymagic = app()->getForm(keytyping);//Получаем форму keytyping
                //Animation EFFECT FADEIN
                $keymagic->opacity = 0;//Прозрачность на 0
                Animation::fadeIn($keymagic , 1000);//Делаем плавное перемещение прозрачности на 1
                $keymagic->show();//Заведомо показываем саму форму
                $this->Menu(true);//Включаем запрет на показывание menu ;)
            });
            $contextMenu->items->add($menukeymagic);//Добавляем keymagic в меню
            
            /*///Событие при нажатие
            $notepadmenu = new UXMenuItem('Блокнот' , new UXImageView (new UXImage('ico' . fs::separator() . 'default' . fs::separator() . $icopack->getDefault() . fs::separator() . 'notepad.png')));//Создаем item + иконка
            $notepadmenu->on('action', function () {//Событие
                $notepad = app()->getForm(ticket);//Получаем форму clocktime
                //Animation EFFECT FADEIN
                $notepad->opacity = 0;//Прозрачность на 0
                Animation::fadeIn($notepad , 1000);//Делаем плавное перемещение прозрачности на 1
                $notepad->show();//Заведомо показываем саму форму
            });
            $contextMenu->items->add($notepadmenu);//Добавляем clocktime в меню
            //-------------------------------------------------------------------------------
                $contextMenu->items->add(UXMenuItem::createSeparator());//Создаем сеппаратор
            ///-------------------------------------------------------------------------------
            $menuStore = new UXMenuItem('Открыть Магазин' , new UXImageView (new UXImage('ico' . fs::separator() . 'default' . fs::separator() . $icopack->getDefault() . fs::separator() . 'store.png')));//Создаем item + иконка
            //Событие при нажатие
            $menuStore->on('action', function () {//Событие
                $Store = app()->getForm(store);//Получаем форму store
                //Animation EFFECT FADEIN
                $Store->opacity = 0;//Прозрачность на 0
                Animation::fadeIn($Store , 1000);//Делаем плавное перемещение прозрачности на 1
                $Store->show();//Заведомо показываем саму форму
                $this->Menu(true);//Включаем запрет на показывание menu ;)
            });
            $contextMenu->items->add($menuStore);//Добавляем menuStore в меню
            //Событие при нажатие
            $menuSettings = new UXMenuItem('Открыть настройки' , new UXImageView (new UXImage('ico' . fs::separator() . 'default' . fs::separator() . $icopack->getDefault() . fs::separator() . 'settings.png')));//Создаем item + иконка
            //Событие при нажатие
            $menuSettings->on('action', function () {//Событие
                $Settings = app()->getForm(Settings);//Получаем форму clocktime
                //Animation EFFECT FADEIN
                $Settings->opacity = 0;//Прозрачность на 0
                Animation::fadeIn($Settings , 1000);//Делаем плавное перемещение прозрачности на 1
                $Settings->show();//Заведомо показываем саму форму
                $this->Menu(true);//Включаем запрет на показывание menu ;)
            });
            $contextMenu->items->add($menuSettings);//Добавляем menuSettings в меню
            $menuchat = new UXMenuItem('Открыть чат' , new UXImageView (new UXImage('ico' . fs::separator() . 'default' . fs::separator() . $icopack->getDefault() . fs::separator() . 'chat.png')));//Создаем item + иконка
            //Событие при нажатие
            $menuchat->on('action', function () {//Событие
                $Chat = app()->getForm(chat);//Получаем форму chat
                //Animation EFFECT FADEIN
                $Chat->opacity = 0;//Прозрачность на 0
                Animation::fadeIn($Chat , 1000);//Делаем плавное перемещение прозрачности на 1
                $Chat->show();//Заведомо показываем саму форму
                $this->Menu(true);//Включаем запрет на показывание menu ;)
            });
            $contextMenu->items->add($menuchat);//Добавляем menuchat в меню
            //-------------------------------------------------------------------------------
                $contextMenu->items->add(UXMenuItem::createSeparator());//Создаем сеппаратор
            //-------------------------------------------------------------------------------
            $menusupport = new UXMenuItem('Поддержка' , new UXImageView (new UXImage('ico' . fs::separator() . 'default' . fs::separator() . $icopack->getDefault() . fs::separator() . 'support.png')));//Создаем item + иконка
            //Событие при нажатие
            $menusupport->on('action', function () {//Событие
                $support = app()->getForm(support);//Получаем форму chat
                //Animation EFFECT FADEIN
                $support->opacity = 0;//Прозрачность на 0
                Animation::fadeIn($support , 1000);//Делаем плавное перемещение прозрачности на 1
                $support->show();//Заведомо показываем саму форму
                $this->Menu(true);//Включаем запрет на показывание menu ;)
            });
            $contextMenu->items->add($menusupport);//Добавляем menuchat в меню
            $menuExit = new UXMenuItem('Выход из программы' , new UXImageView (new UXImage('ico' . fs::separator() . 'default' . fs::separator() . $icopack->getDefault() . fs::separator() . 'exit.png')));//Создаем item + иконка
            //Событие при нажатие
            $menuExit->on('action', function () {//Событие
                $MainForm = app()->getForm(MainForm);//возвращаем форму главную
                //Animation EFFECT FADEOUT
                Animation::fadeOut($MainForm , 1000);//Плавное затухание...
                Animation::fadeOut($MainForm , 1000 , function () { //Затухание + callback
                    app()->shutdown();//Когда == 0 прозрачность то выход из программы
                });
            });
                $contextMenu->items->add($menuExit);//Добавние menuExit в меню
            //-------------------------------------------------------------------------------
                $contextMenu->showByNode($e, $x, $y);//Показать контекстное меню на image и с позиций по курсору
            //-------------------------------------------------------------------------------
        }
        else {
            //-------------------------------------------------------------------------------
                Logger::info('Контекстное меню закрыто!');
            //-------------------------------------------------------------------------------
        }
    } 
}
