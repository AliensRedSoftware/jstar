<?php
namespace app\forms;

use std, gui, framework, app;

class ultimate extends AbstractForm {

    /**
     * @event showing 
     */
    function doShowing(UXWindowEvent $e = null) {
        $this->imageurl->enabled = !$this->alllist->selected;
        $this->alllist->enabled = !$this->imageurl->selected;
        $this->form('Settings')->showPreloader('Ожидаем ответ от формы...');
    }

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        $this->form('Settings')->hidePreloader();
        $this->Menu(true);
        $this->saveultimate($this->alllist->selected , $this->imageurl->selected , false);
    }

    /**
     * @event alllist.click-Left 
     */
    function doAlllistClickLeft(UXMouseEvent $e = null) {    
        $this->imageurl->enabled = !$this->alllist->selected;
    }

    /**
     * @event imageurl.click-Left 
     */
    function doImageurlClickLeft(UXMouseEvent $e = null) {    
        $this->alllist->enabled = !$e->sender->selected;
    }

    /**
     * @event image.construct 
     */
    function doImageConstruct(UXEvent $e = null) {    
        $e->sender->image = new UXImage('ico/logo.png');
    }
}