<?php
namespace app\forms;

use app\modules\jURL;
use std, gui, framework, app;

class updatesuccess extends AbstractForm {

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null) {   
        $this->Menu(true); 
        $ch = new jURL("http://dsafkjdasfkjnasgfjkasfbg.000webhostapp.com/bot/description");
        $this->showPreloader('Идет получение описание ждите...');
        $ch->asyncExec(function ($data) {
            $this->descriptions->text = $data;
            $this->hidePreloader();
        });
        $ch->close();
    }

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        $this->Menu(false);
    }
}