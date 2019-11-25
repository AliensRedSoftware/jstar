<?php
namespace app\forms;

use std, gui, framework, app;
use php\gui\event\UXMouseEvent; 

class SettingsChat extends AbstractForm {

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        $this->Save();
    }
}
