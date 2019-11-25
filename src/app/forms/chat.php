<?php
namespace app\forms;
use std, gui, framework, app;

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
        Animation::fadeIn($SettingsChat , 1000);
        $SettingsChat->showAndWait();
    }

    /**
     * @event text.keyDown-Enter 
     */
    function doTextKeyDownEnter(UXKeyEvent $e = null) {
        switch ($this->typeselected->selectedIndex) {
            case 0:
                $this->SendChat('Vk' , $e->sender->text);
            break;
            case 1:
                $this->SendChat('Telegram' , $e->sender->text);
            break;
            case 2:
                $this->SendChat('local' , $e->sender->text);
            break;
        }
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

}
