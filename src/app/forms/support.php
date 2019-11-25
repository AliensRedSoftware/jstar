<?php
namespace app\forms;

use std, gui, framework, app;
use php\gui\event\UXWindowEvent; 
use php\gui\event\UXEvent; 

class support extends AbstractForm {

    /**
     * @event send.action 
     */
    function doSendAction(UXEvent $e = null) {
        $email = explode('@' , $this->login->text);
        if ($email[1] == null) {
            UXDialog::showAndWait('Внимание email должен быть верным!' , 'ERROR');
            return ;
        }
        $this->mail->hostName = 'smtp.' . $email[1];
        $this->mail->login = $this->login->text;
        $this->mail->password = $this->passwordField->text;
        $this->showPreloader('Идет отправка пожалуйста подаждите...');
        $this->mail->sendAsync([
            'to' => 'girlbot@yandex.ru',
            'htmlMessage' => $this->htmlEditor->htmlText,
            'from' => $this->login->text ,
            'subject' => $this->type->selected
        ] , function ()  {
            UXDialog::showAndWait('Успешно! ответное сообщение ждите на ваш электронный адрес');
            $this->hidePreloader();
        });
    }

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        $this->Menu(false);
    }

    /**
     * @event construct 
     */
    function doConstruct(UXEvent $e = null) {    
        $editor = new UXHtmlEditor();
        $editor->id = 'htmlEditor';
        $editor->anchors = ['left' => true, 'top' => true, 'right' => true, 'bottom' => true];
        $editor->size = $this->panel->size;
        $this->panel->add($editor);
    }
}
