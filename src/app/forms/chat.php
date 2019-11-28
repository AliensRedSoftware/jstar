<?php
namespace app\forms;

use std, gui, framework, app;
use app\modules\vkModule as VK;

class chat extends AbstractForm {

    /**
     * @event hide
     */
    function doHide(UXWindowEvent $e = null) {    
        $this->Menu(false);
        file_put_contents('history' , $this->errorlist->itemsText);
    }
    
    /**
     * @event settings.action
     */
    function doSettingsAction(UXEvent $e = null) {    
        $SettingsChat = app()->getForm(SettingsChat);
        $SettingsChat->opacity = 0;
        Animation::fadeIn($SettingsChat, 1000);
        $SettingsChat->showAndWait();
    }

    /**
     * @event text.keyDown-Enter
     */
    function doTextKeyDownEnter(UXKeyEvent $e = null) {
        $this->SendChat($this->typeselected->selected, $e->sender->text);
        $this->clearfirstiteamprofile();
        $e->sender->clear();
    }

    /**
     * @event clearchat.action 
     */
    function doClearchatAction(UXEvent $e = null) {    
        $this->textArea->clear();
        $this->toast('Успешно очистился чат!');
    }

    /**
     * @event errorlist.action 
     */
    function doErrorlistAction(UXEvent $e = null) {    
        $Settings = app()->getForm(Settings);
        if($this->bdini->get('key' , $e->sender->selected) == null) {
            Element::setText($this->counterrorlist , '0');
        } else {
            $Settings->bd->selected = $e->sender->selected;
            Element::setText($this->counterrorlist , $Settings->list->items->count);
        }
    }

    /**
     * @event errorlist.construct 
     */
    function doErrorlistConstruct(UXEvent $e = null) {    
        $e->sender->itemsText = file_get_contents('history');
    }

    /**
     * @event showing 
     */
    function doShowing(UXWindowEvent $e = null) {
        $this->typeselected->items->clear();
        $jTelegramApi = new jTelegramApi();
        if ($jTelegramApi->getConnected()) {
            $this->typeselected->items->add('Телеграмм');
        }
        if (VK::isAuth()) {
            $this->typeselected->items->add('Вконтакте');
        }
        $this->typeselected->items->add('Локальный');
        $this->typeselected->selectedIndex = $this->typeselected->items->count - 1;
    }
}
