<?php
namespace app\forms;

use std, gui, framework, app;
use php\gui\event\UXWindowEvent; 

class ticket extends AbstractForm {

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {
         file_put_contents('ticket' , $this->textArea->text);
         $this->Menu(false);
    }

    /**
     * @event showing 
     */
    function doShowing(UXWindowEvent $e = null) {    
        $this->textArea->text = file_get_contents('ticket');
        $this->Menu(true);//Включаем запрет на показывание menu ;)
    }
}
