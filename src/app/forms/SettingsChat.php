<?php
namespace app\forms;

use std, gui, framework, app;

class SettingsChat extends AbstractForm {

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        $this->Save();
    }
}
