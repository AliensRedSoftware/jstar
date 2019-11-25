<?php
namespace app\forms;

use std, gui, framework, app;
use php\gui\event\UXEvent; 
use php\gui\event\UXWindowEvent;

class MainForm extends AbstractForm {

    protected $contextMenu;
    
    /**
     * @event image.click-Right 
     */
    function doImageClickRight(UXMouseEvent $e = null) {
        //-------------------------------------------------------------------------------
            $this->ContextMenuShow($e->sender , $e->x , $e->y);//Вызвать функцию контекстного меню
        //-------------------------------------------------------------------------------
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null) {
        if ($this->version->get('notification' , true , 'section')) {
            $this->Menu(false);
        }
        $this->Load();//Загрузки ini + проверка на обновление
    }

    /**
     * @event image.construct 
     */
    function doImageConstruct(UXEvent $e = null) {
        $this->LoadLeaf($e->sender);//Загрузка эффектов
    }

    /**
     * @event image.mouseUp-Left 
     */
    function doImageMouseUpLeft(UXMouseEvent $e = null) {    
        $form = app()->getForm(Settings);
        $form->valueX->value = $this->x;
        $form->valueY->value = $this->y;
    }

    /**
     * @event image.mouseDrag 
     */
    function doImageMouseDrag(UXMouseEvent $e = null){  
        $form = app()->getForm(Settings);  
        $form->valueX->value = $this->x;
        $form->valueY->value = $this->y;
    }

    /**
     * @event image.mouseDown-Left 
     */
    function doImageMouseDownLeft(UXMouseEvent $e = null) {
        $form = app()->getForm(Settings);
        $form->valueX->value = $this->x;
        $form->valueY->value = $this->y;
    }

    /**
     * @event construct 
     */
    function doConstruct(UXEvent $e = null){
        $this->showtray($this , $this , $this , $this);
    }
    
    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {
        $this->Save();
        $this->systemTray->hide();
    }
}
