<?php
namespace app\forms;

use facade\Json;
use std, gui, framework, app;

class profile extends AbstractForm {

    /**
     * @event showing 
     */
    function doShowing(UXWindowEvent $e = null) {    
        $this->Menu(true);
        $this->showPreloader('Идет проверка аккаунта :/');
        app()->getForm(auth)->checkauth(false);
    }

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        app()->getForm(store)->hidePreloader();
    }
    /**
     * Загрузка профиля
     */
    function loadprofile () {
        $tdata = Json::fromFile('./tdata.json');
        $login = $tdata['login'];
        $email = $tdata['email'];
        $img_hash = $tdata['img_hash'];
        $this->Login->text = $login;
        $this->type->text = $email;
        if ($img_hash != null) {
            $this->imageAlt->image = new UXImage ('./data/avatar.png');
        }
    }
}
