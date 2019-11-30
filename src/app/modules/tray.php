<?php
namespace app\modules;

use std, gui, framework, app;

class tray extends AbstractModule {

    protected $contextMenu;
    
    /**
     * Показать трей 
     */
    public function showtray($MainForm ,  $contextMenu , $updateModule , $icopack) {
        //-------------------------------------------------------------------------------
            Logger::info('Контекстное меню открыто!');
        //-------------------------------------------------------------------------------
        $this->contextMenu = new UXContextMenu();//Создаем ContexMenu
        $menuUpdate = new UXMenuItem('Проверить обновление', new UXImageView (new UXImage('ico' . fs::separator() . 'tray' . fs::separator() . $icopack->getTray() . fs::separator() . 'update.png')));//Создаем item + иконка
        //Событие при нажатие
        $menuUpdate->on('action', function () use ($updateModule){ //Событие
            $updateModule->updatecheck();//Проверка обновление
        });
        $this->contextMenu->items->add($menuUpdate);//Добавляем обновление в меню
        $menuAlwaysOnTop = new UXMenuItem('Поверх всех окон', new UXImageView (new UXImage('ico' . fs::separator() . 'tray' . fs::separator() . $icopack->getTray() . fs::separator() . 'screen.png')));//Создаем item + иконка
        //Событие при нажатие
        $menuAlwaysOnTop->on('action', function () use ($MainForm){//Событие + подключаем Главный модуль по идеи его название должно быть core но Я его назвал MainModule хз почему :)
            if($MainForm->alwaysOnTop == true) { //Поверх окон ли ?) если да то...
                $MainForm->alwaysOnTop = false;//Выключаем поверх окон
                $MainForm->toast('Поверх экрана->Выкл');//показываем тост сообщение
            }
            else { //Если не поверх окон то...
                $MainForm->alwaysOnTop = true;//Делаем поверх окон
                $MainForm->toast('Поверх экрана->Вкл');//показываем тост сообщение
            }
            $MainForm->Save();//Сохранение изменение в ini
        });
        $this->contextMenu->items->add($menuAlwaysOnTop);//Добавляем поверх окон в меню
        //-------------------------------------------------------------------------------
            $this->contextMenu->items->add(UXMenuItem::createSeparator());//Создаем сеппаратор
        //-------------------------------------------------------------------------------
        $notepadmenu = new UXMenuItem('Блокнот', new UXImageView (new UXImage('ico' . fs::separator() . 'tray' . fs::separator() . $icopack->getDefault() . fs::separator() . 'notepad.png')));//Создаем item + иконка
        $notepadmenu->on('action', function () {//Событие
            $notepad = app()->getForm(ticket);//Получаем форму clocktime
            //Animation EFFECT FADEIN
            $notepad->opacity = 0;//Прозрачность на 0
            Animation::fadeIn($notepad , 1000);//Делаем плавное перемещение прозрачности на 1
            $notepad->show();//Заведомо показываем саму форму
        });
        $this->contextMenu->items->add($notepadmenu);//Добавляем clocktime в меню
        //-------------------------------------------------------------------------------
            $this->contextMenu->items->add(UXMenuItem::createSeparator());//Создаем сеппаратор
        ///-------------------------------------------------------------------------------
        $menuStore = new UXMenuItem('Магазин', new UXImageView (new UXImage('ico' . fs::separator() . 'tray' . fs::separator() . $icopack->getTray() . fs::separator() . 'store.png')));//Создаем item + иконка
        //Событие при нажатие
        $menuStore->on('action', function () use ($contextMenu) {//Событие
            $Store = app()->getForm(store);//Получаем форму store
            //Animation EFFECT FADEIN
            $Store->opacity = 0;//Прозрачность на 0
            Animation::fadeIn($Store , 1000);//Делаем плавное перемещение прозрачности на 1
            $Store->show();//Заведомо показываем саму форму
            $contextMenu->Menu(true);//Включаем запрет на показывание menu ;)
        });
        $this->contextMenu->items->add($menuStore);//Добавляем menuStore в меню
        //Событие при нажатие
        $menuSettings = new UXMenuItem('Настройки', new UXImageView (new UXImage('ico' . fs::separator() . 'tray' . fs::separator() . $icopack->getTray() . fs::separator() . 'settings.png')));//Создаем item + иконка
        //Событие при нажатие
        $menuSettings->on('action', function () use ($contextMenu) {//Событие
            $Settings = app()->getForm(Settings);//Получаем форму clocktime
            //Animation EFFECT FADEIN
            $Settings->opacity = 0;//Прозрачность на 0
            Animation::fadeIn($Settings, 1000);//Делаем плавное перемещение прозрачности на 1
            $Settings->show();//Заведомо показываем саму форму
            $contextMenu->Menu(true);//Включаем запрет на показывание menu ;)
        });
        $this->contextMenu->items->add($menuSettings);//Добавляем menuSettings в меню
        $menuchat = new UXMenuItem('Чат', new UXImageView (new UXImage('ico' . fs::separator() . 'tray' . fs::separator() . $icopack->getTray() . fs::separator() . 'chat.png')));//Создаем item + иконка
        //Событие при нажатие
        $menuchat->on('action', function () use ($contextMenu) {//Событие
            $Chat = app()->getForm(chat);//Получаем форму chat
            //Animation EFFECT FADEIN
            $Chat->opacity = 0;//Прозрачность на 0
            Animation::fadeIn($Chat, 1000);//Делаем плавное перемещение прозрачности на 1
            $Chat->show();//Заведомо показываем саму форму
            $contextMenu->Menu(true);//Включаем запрет на показывание menu ;)
        });
        $this->contextMenu->items->add($menuchat);//Добавляем menuchat в меню
        //-------------------------------------------------------------------------------
            $this->contextMenu->items->add(UXMenuItem::createSeparator());//Создаем сеппаратор
        //-------------------------------------------------------------------------------
        $menusupport = new UXMenuItem('Поддержка', new UXImageView (new UXImage('ico' . fs::separator() . 'tray' . fs::separator() . $icopack->getTray() . fs::separator() . 'support.png')));//Создаем item + иконка
        //Событие при нажатие
        $menusupport->on('action', function () use ($contextMenu) {//Событие
            $support = app()->getForm(support);//Получаем форму chat
            //Animation EFFECT FADEIN
            $support->opacity = 0;//Прозрачность на 0
            Animation::fadeIn($support, 1000);//Делаем плавное перемещение прозрачности на 1
            $support->show();//Заведомо показываем саму форму
            $contextMenu->Menu(true);//Включаем запрет на показывание menu ;)
        });
        $this->contextMenu->items->add($menusupport);//Добавляем menuchat в меню
        $menuExit = new UXMenuItem('Выход из программы', new UXImageView (new UXImage('ico' . fs::separator() . 'tray' . fs::separator() . $icopack->getTray() . fs::separator() . 'exit.png')));//Создаем item + иконка
        //Событие при нажатие
        $menuExit->on('action', function () use ($MainForm){//Событие
            //Animation EFFECT FADEOUT
            Animation::fadeOut($MainForm, 1000);//Плавное затухание...
            Animation::fadeOut($MainForm, 1000, function () { //Затухание + callback
                app()->shutdown();//Когда == 0 прозрачность то выход из программы
            });
        });
        $this->contextMenu->items->add($menuExit);//Добавние menuExit в меню
        $this->systemTray->displayMessage('[бот]', 'Добро пожаловать :)', 'INFO');
        $this->systemTray->on('click', function (UXMouseEvent $e = null) use ($MainForm , $menu) { 
            if ($e->button == 'SECONDARY') {
                $contextMenu = new contextMenuModule(); 
                if ($contextMenu->getMenu() == false) {
                    $this->contextMenu->show($MainForm, $e->screenX, $e->screenY);
                } else {
                    Logger::info('Контекстное меню закрыто!');
                }
            }
        });    
    }
}
