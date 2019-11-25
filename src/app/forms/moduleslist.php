<?php
namespace app\forms;

use std, gui, framework, app;
use php\gui\event\UXWindowEvent; 

class moduleslist extends AbstractForm {

    /**
     * @event showing 
     */
    function doShowing(UXWindowEvent $e = null) {    
        $this->getmoduleslist();
    }

    /**
     * @event applymoduleslist.action 
     */
    function doApplymoduleslistAction(UXEvent $e = null) {
        $Settings = app()->getForm(Settings);
        $Settings->list->items->clear();
        $Settings->list->items->add($this->moduleiteam->selected);
        $this->bdini->set('key' , $this->moduleiteam->selected , $Settings->bd->selected);
        $this->free();
    }

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        $this->free();
    }
}
