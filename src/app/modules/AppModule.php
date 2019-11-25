<?php
namespace app\modules;

use std, gui, framework, app;

class AppModule extends AbstractModule {

    /**
     * @event action 
     */
    function doAction(ScriptEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->opacity = 0;
        Animation::fadeIn($MainForm , 1000);
        $MainForm->show();
    }
}