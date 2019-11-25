<?php
namespace app\modules;

use app\forms\Settings;
use action\Element;
use std, gui, framework, app;

class icopack extends AbstractModule {
    
    /**
     * Получение tray иконки 
     */     
    function getTray() {
        $ini = new MainModule();
        return $ini->ini->get('traypackselected' , 'ico');
    }
    
    /**
     * Получение tray иконки 
     */     
    function getDefault() {
        $ini = new MainModule();
        return $ini->ini->get('defaultpackselected' , 'ico');
    }

    /**
     * Установить пакет иконок 
     */
     public function installpackico (Settings $settings) {
         $index = $settings->icotype->selectedIndex;
         if ($index == 0) {
             $settings->ini->set('defaultpackselected' , $settings->icopackselected->selected , 'ico');
         } if ($index == 1) {
             $settings->ini->set('traypackselected' , $settings->icopackselected->selected , 'ico');
             UXDialog::showAndWait('Пожалуйста перезапустите программу');
         }
         $this->checkico($settings);
     }
          
    /**
     * Получение пак иконок 
     */
    public function getpack(Settings $settings) {
        $settings->icopackselected->items->clear();
        if ($settings->icotype->selectedIndex == 0) {
            $dir = fs::scan('ico' . fs::separator() . 'default' . fs::separator() , ['excludeFiles' => true]);  
            foreach ($dir as $file) {
                $name = explode(fs::separator() , $file);
                $settings->icopackselected->items->add($name[2]);
            }
            $settings->icopackselected->selected = $this->getDefault();
        } else {
            $dir = fs::scan('ico' . fs::separator() . 'tray' . fs::separator() , ['excludeFiles' => true]);  
            foreach ($dir as $file) {
                $name = explode(fs::separator() , $file);
                $settings->icopackselected->items->add($name[2]);
            }
            $settings->icopackselected->selected = $this->getTray();
        }
        $settings->icoselected->selectedIndex = 0;
    }
    
    /**
     * Выбрать иконку
     */
     public function selectedico (Settings $Settings) {
         if ($Settings->icotype->selectedIndex == 0) {
             $pathtype = 'default';
             if ($this->icopackselected->selected == null) {
                 $selected = $this->getDefault();
             } else {
                 $selected = $this->icopackselected->selected;
             }
         } else {
             $pathtype = 'tray';
             if ($this->icopackselected->selected == null) {
                 $selected = $this->getTray();
             } else {
                 $selected = $this->icopackselected->selected;
             }
         }
         $Settings->showPreloader('Идет загрузка иконки пожалуйста подождите...');
         switch ($Settings->icoselected->selectedIndex) {
             case 0:
                 $path = 'ico' . fs::separator() . $pathtype . fs::separator() . $selected . fs::separator() . 'exit.png';
                 Element::loadContentAsync($Settings->icoview , $path , function () use ($Settings) {
                     $Settings->hidePreloader();
                 });
             break;
             case 1:
                 $path = 'ico' . fs::separator() . $pathtype . fs::separator() . $selected . fs::separator() . 'support.png';
                 Element::loadContentAsync($Settings->icoview , $path , function () use ($Settings) {
                     $Settings->hidePreloader();
                 });
             break;
             case 2:
                 $path = 'ico' . fs::separator() . $pathtype . fs::separator() . $selected . fs::separator() . 'chat.png';
                 Element::loadContentAsync($Settings->icoview , $path , function () use ($Settings) {
                     $Settings->hidePreloader();
                 });
             break;
             case 3:
                 $path = 'ico' . fs::separator() . $pathtype . fs::separator() . $selected . fs::separator() . 'settings.png';
                 Element::loadContentAsync($Settings->icoview , $path , function () use ($Settings) {
                     $Settings->hidePreloader();
                 });
             break;
             case 4:
                 $path = 'ico' . fs::separator() . $pathtype . fs::separator() . $selected . fs::separator() . 'store.png';
                 Element::loadContentAsync($Settings->icoview , $path , function () use ($Settings) {
                     $Settings->hidePreloader();
                 });
             break;
             case 5:
                 $path = 'ico' . fs::separator() . $pathtype . fs::separator() . $selected . fs::separator() . 'screen.png';
                 Element::loadContentAsync($Settings->icoview , $path , function () use ($Settings) {
                     $Settings->hidePreloader();
                 });
             break;
             case 6:
                 $path = 'ico' . fs::separator() . $pathtype . fs::separator() . $selected . fs::separator() . 'update.png';
                 Element::loadContentAsync($Settings->icoview , $path , function () use ($Settings) {
                     $Settings->hidePreloader();
                 });
             break;
             case 7:
                 $path = 'ico' . fs::separator() . $pathtype . fs::separator() . $selected . fs::separator() . 'notepad.png';
                 Element::loadContentAsync($Settings->icoview , $path , function () use ($Settings) {
                     $Settings->hidePreloader();
                 });
             break;
         }
     }
     
     /**
      * Чекануть скинчики ) 
      */
      public function checkico (Settings $settings) {
          if ($settings->icotype->selectedIndex == 0) {
              if ($settings->ini->get('defaultpackselected' , 'ico') == $settings->icopackselected->selected) {
                  $settings->labelicoselected->text = 'Выбран пак :)';
                  $settings->labelicoselected->textColor = UXColor::of('#99cc99');
                  $settings->installpackico->enabled = false;
              } else {
                  $settings->labelicoselected->text = 'Не выбран пак :(';
                  $settings->labelicoselected->textColor = UXColor::of('#ff9999');
                  $settings->installpackico->enabled = true;
              }
          } else {
              if ($settings->ini->get('traypackselected' , 'ico') == $settings->icopackselected->selected) {
                  $settings->labelicoselected->text = 'Выбран пак :)';
                  $settings->labelicoselected->textColor = UXColor::of('#99cc99');
                  $settings->installpackico->enabled = false;
              } else {
                  $settings->labelicoselected->text = 'Не выбран пак :(';
                  $settings->labelicoselected->textColor = UXColor::of('#ff9999');
                  $settings->installpackico->enabled = true;
              }
          }
          if ($settings->icoselected->selectedIndex == 0) {
              $this->selectedico($settings);
          } else {
              $settings->icoselected->selectedIndex = 0;
          }
      }
}