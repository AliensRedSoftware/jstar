<?php
namespace app\modules;

use facade\Json;
use std, gui, framework, app;
use php\gui\framework\ScriptEvent; 

class storemodule extends AbstractModule {
    
    protected $url;

    /**
     * @event jdownloader.start 
     */
    function doJdownloaderStart(ScriptEvent $e = null) {    
        $store = app()->getForm(store);//Получаем форму store
        $store->showPreloader('Идет установка скина...');
    }

    /**
     * @event jdownloader.complete 
     */
    function doJdownloaderComplete(ScriptEvent $e = null) {    
        $store = app()->getForm(store);//Получаем форму store
        $this->zipFile->path = $store->pagination->hintText . '.zip';
        $this->zipFile->unpack('.' . fs::separator() . 'skin' . fs::separator());
    }

    /**
     * @event zipFile.unpackAll 
     */
    function doZipFileUnpackAll(ScriptEvent $e = null) {    
        $store = app()->getForm(store);//Получаем форму store
        fs::delete($store->pagination->hintText . '.zip');
        $store->hidePreloader();//убираем прелоудер
        $store->toast('Скин успешно установился!');
        $store->install->graphic = new UXImageView(new UXImage('res://.data/img/delete-icon.png'));
        $store->install->tooltipText = 'Удалить скин';
    }

    /**
     * @event jdownloader.error 
     */
    function doJdownloaderError(ScriptEvent $e = null) {
        UXDialog::showAndWait('Внимание что-то пошло не так нажми' , 'ERROR');
        $skinmanager = new skinmanager();
        $menu = new contextMenuModule();
        $menu->Menu(false);//Вызов контекстного меню запрещено!
        $skinmanager->reloadskin();//Загрузка скина
        app()->getForm(store)->free();//Самоуничтожение формы
    }

    /**
     * @event construct 
     */
    function doConstruct(ScriptEvent $e = null) {    
        $this->url = 'https://dsafkjdasfkjnasgfjkasfbg.000webhostapp.com';
    }
    
    /**
     * получение скинов 
     */
    function getSkins() {
        $store = app()->getForm(store);
        $store->showPreloader('Получение скинов...');
        $listName = new UXListView();//Создаем listName лист с именами
        $json = Json::decode(file_get_contents($this->url . '/bot/store/skin'));//Получаем json с сервера
        $GLOBALS['skin'] = $json;
        foreach ($json['skin'] as $skin) { //Добавляем лист с именами
            $this->iteam->items->add($skin);//Процесс добавление
        }
        $this->checkskin(false);
        $store->hidePreloader();
        Logger::info('Скины получены!');//Кидаем logger то что функция начила работу!
    }
    
    /**
     * Выбрать элемент 
     */
    public function selectedElement (UXListView $e) {
        if ($this->skin->selected) {
            $json = $GLOBALS['skin'];//Получаем json с сервера
            $this->pagination->hintText = $e->selectedItem;//Установка статуса
            $this->checkskin(false);
            $i = -1;
            foreach ($json[$e->selectedItem] as $log) {
                $i++;//Коды
                switch ($i) { //Проверка кодов
                    case 0://Описание
                        $this->description->text = $log;
                    break;
                    case 1://Пагинация страниц и (кол-скинов)
                        $this->pagination->total = $log;
                    break;
                }
            }
            if ($this->pagination->selectedPage != 0) {
                $this->pagination->selectedPage = 0;//Установка пагинация на 0
            } else {
                $this->setSkin($this->image);//Установка скина 
            }
            $skinmanager = new skinmanager();
            if ($skinmanager->getselected() == $e->selectedItem || $e->selectedItem == 'miku_default') {
                $this->install->enabled = false;
            } else {
                $this->install->enabled = true;
            }
        }
    }
    
    
    /**
     * Установить скин 
     */
    function setSkin($img) {
        $store = app()->getForm(store);//Получаем форму store
        $store->showPreloader('Загрузка...');//showPreloader включен
        //Загрузка img с callback
        Element::loadContentAsync($img, $this->url . '/bot/store/dir_skin/view/'  . $store->pagination->hintText . '/' . 'skin_' . $store->pagination->selectedPage . '.png', function () use ($img, $store) {
            $store->hidePreloader();//Убираем showPreloader
        });
    }
    
    /**
     * Проверка скина установлен ли он или нет
     */
    function checkskin ($reloadskin) { 
        $settings = app()->getForm(Settings);
        if ($reloadskin == true) {
            $settings->reloadskin();
        }
        foreach ($settings->Category_skin->items as $name_cat) {
            if ($this->pagination->hintText == 'miku_default') {$this->install->enabled = false; return;}
            $this->install->enabled = true;
            if ($name_cat == $this->pagination->hintText) {
                $this->install->graphic = new UXImageView(new UXImage('res://.data/img/delete-icon.png'));
                $this->install->tooltipText = 'Удалить скин';
                return ;
            } else {
                $this->install->graphic = new UXImageView(new UXImage('res://.data/img/download.png'));
                $this->install->tooltipText = 'Установить скин';
            }
        }
    }
    
    /**
     * Установка скина 
     */
    function downloadskin() { //Функция скачивает скин
        $store = app()->getForm(store);//Получаем форму store
        $this->jdownloader->url = $this->url . '/bot/store/dir_skin/pack/'  . $store->pagination->hintText . '.zip';
        $this->jdownloader->savePath = './';
        $this->jdownloader->start();
    }
    
    /**
     * Удалить скин 
     */
     function removeskin (store $form) {
         $settings = app()->getForm(Settings);
         $skinmanager = new skinmanager();
         if ($skinmanager->getselected() == $this->pagination->hintText) {$skinmanager->setskin(0);}
         $form->showPreloader('Удаление скина...');
         $path = 'skin' . fs::separator() . $form->pagination->hintText;
         fs::clean($path);
         fs::delete($path);
         $form->hidePreloader();
         $form->toast('Успешно удалился скин!');
         $this->checkskin(true);
     }
}
