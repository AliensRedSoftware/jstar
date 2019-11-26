<?php
namespace app\modules;

use std, gui, framework, app;

class update extends AbstractModule {

    /**
     * @event mail.error 
     */
    function doMailError(ScriptEvent $e = null) {    
        UXDialog::showAndWait('Пожалуйста напишите верные данные от своей почты мы их не своруем! :)' , 'ERROR');
        $this->form(support)->hidePreloader();
    }

    /**
     * @event jdownloader.complete 
     */
    function doJdownloaderComplete(ScriptEvent $e = null) {
        execute('java -jar updateBrackets.jar');
        app()->shutdown();
    }

    /**
     * @event jdownloader.error 
     */
    function doJdownloaderError(ScriptEvent $e = null) {    
        execute('java -jar Bracket.jar');
        app()->shutdown();
    }

    /**
     * @event jdownloader.abort 
     */
    function doJdownloaderAbort(ScriptEvent $e = null) {    
        execute('java -jar Bracket.jar');
        app()->shutdown();
    }
    
    /**
     * Проверка обновление 
     */
    function updatecheck() {
        $MainForm = app()->getForm(MainForm);
        $MainForm->showPreloader('Идет проверка обновление...');
        $get = Stream::getContents('https://dsafkjdasfkjnasgfjkasfbg.000webhostapp.com/bot/ver');
        $ver = $this->version->get('version' , 'section');
        if (str::length(trim($get)) != 5) {
            $MainForm->hidePreloader();
            $MainForm->toast('Ошибка подключение к интернету!');
            return ;
        }
        if($ver != trim($get)) {
            $MainForm->showPreloader('Будет сейчас установлена последняя версия!');
            $MainModule = new contextMenuModule();
            $MainModule->Menu(true);
            $os = System::getProperty('os.name');
            if ($os == 'Linux') {
                $url = 'https://dsafkjdasfkjnasgfjkasfbg.000webhostapp.com/bot/linux/dist.zip';
            } else {
                $url = 'https://dsafkjdasfkjnasgfjkasfbg.000webhostapp.com/bot/windows/dist.zip';
            }
            $this->jdownloader->url = $url;
            $this->jdownloader->start();
        }
        else {
            $MainForm->hidePreloader();
            if ($this->version->get('notification' , 'section') == true) {
                app()->getForm(updatesuccess)->showAndWait();
                $this->version->set('notification' , false , 'section');
            }
            $MainForm->toast('У вас последняя версия :)');
        }
    }
}