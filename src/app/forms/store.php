<?php
namespace app\forms;

use std, gui, framework, app;
use php\gui\event\UXEvent; 

class store extends AbstractForm {

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        $skinmanager = new skinmanager();
        $this->Menu(false);//Вызов контекстного меню запрещено!
        $skinmanager->reloadskin();//Загрузка скина
        $this->free();//Самоуничтожение формы
    }

    /**
     * @event showing 
     */
    function doShowing(UXWindowEvent $e = null) { 
        $this->getSkins();//Получение скина
        app()->getForm(auth)->checkauth(true);//Проверка авторизован пользователь или нет
    }

    /**
     * @event install.action 
     */
    function doInstallAction(UXEvent $e = null) {
        if ($this->install->tooltipText == 'Установить скин') {
            $this->downloadskin();//Устновка скина
        } else {
            $this->removeskin($this);
        }
    }

    /**
     * @event pagination.action 
     */
    function doPaginationAction(UXEvent $e = null) {
        $this->setSkin($this->image);//Установка скина 
    }

    /**
     * @event Settingsprofile.action 
     */
    function doSettingsprofileAction(UXEvent $e = null) {    
        if ($this->login->text == 'Выйти из аккаунта') {
            $this->form('profile')->showAndWait();
        } else {
            app()->getForm(auth)->checkauth();
        }
    }

    /**
     * @event login.action 
     */
    function doLoginAction(UXEvent $e = null) {
        if($e->sender->text == 'Войти в аккаунт') {
            app()->getForm(auth)->show();
        } else {
            app()->getForm(auth)->exitAuth();
        }
    }

    /**
     * @event newuser.action 
     */
    function doNewuserAction(UXEvent $e = null) {
        app()->getForm(register)->show();
    }

    /**
     * @event toggleButtonmodule.action 
     */
    function doToggleButtonmoduleAction(UXEvent $e = null) {
        $this->skin->selected = !$e->sender->selected;
        if ($e->sender->selected) {
            $this->iteam->items->clear();
            $this->panel3->enabled = true;
            if ($this->type_modules->selectedIndex == -1) {
                $this->type_modules->selectedIndex = 0;
            }
        } else {
            $this->getSkins();
        }
    }

    /**
     * @event skin.action 
     */
    function doSkinAction(UXEvent $e = null) {    
        $this->toggleButtonmodule->selected = !$e->sender->selected;
        if ($e->sender->selected) {
            $this->panel3->enabled = false;
            $this->getSkins();
        } else {
            $this->iteam->items->clear();
        }
    }

    /**
     * @event type.action 
     */
    function doTypeAction(UXEvent $e = null) {    
        if ($this->type_modules->selectedIndex == -1) {
            $this->type_modules->selectedIndex = 0;
        }
    }

    /**
     * @event iteam.action 
     */
    function doIteamAction(UXEvent $e = null) {    
        $this->selectedElement($e->sender);
    }

}
