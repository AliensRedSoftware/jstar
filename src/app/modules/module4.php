<?php
namespace app\modules;
use std, gui, framework, app;

class module4 extends AbstractModule {
    public function module4_get($id , $txt) {
        $ChatSettings = app()->getForm(SettingsChat);
        $MainForm = app()->getForm(MainForm);
        $MainModule = new MainModule();
        $Settings = app()->getForm(Settings);
        if($id == $Settings->groupandid->value) {
            if($Settings->idgroup->selected == false) {
                $MainModule->SendChat($id, $txt, false);
            }
        }
        elseif($id == 2000000000 + $Settings->groupandid->value) {    
            if($Settings->idgroup->selected == true) {
                $MainModule->SendChat($id, $txt, $MainModule->bdini->get('all', $txt));
            }
        }
    }
}
