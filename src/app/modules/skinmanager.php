<?php
namespace app\modules;

use std, gui, framework, app;

class skinmanager extends AbstractModule {
    
    /**
     * Получить выбранный путь скина
     */
     function getselected() {
         $settings = app()->getForm(Settings);
         $selected = explode(fs::separator() , $settings->ini->get('path' , 'SettingsFemale'));
         return $selected[1];
     }    
     
     /**
     * Установить скин
     */
     function setskin($id) {
         $settings = app()->getForm(Settings);
         $settings->skin->items->clear();
         $files = fs::scan('skin' . fs::separator() . $settings->Category_skin->selected . fs::separator(), ['extensions' => ['jpg', 'png' , 'jpeg'], 'excludeDirs' => true]);
         foreach ($files as $fs) {
             $path = str::split($fs , fs::separator());
             $settings->skin->items->add($path[2]);
         }
         if ($settings->skin->selected == null) {
             $settings->skin->selectedIndex = 0;
         }
         $settings->Category_skin->selectedIndex = $id;
    }
     
    /**
     * Обновление скина 
     */
     function reloadskin () {
         $form = app()->getForm(Settings);
         $ini = new MainModule();
         $files = fs::scan('skin' . fs::separator() , ['excludeFiles' => true]);
         $form->Category_skin->items->clear();
         foreach ($files as $fs) {
             $path = str::split($fs , fs::separator());
             $form->Category_skin->items->add($path[1]);
         }
         $form->skin->items->clear();
         $f = fs::scan('skin' . fs::separator() . $form->Category_skin->selected . fs::separator() , ['extensions' => ['jpg', 'png' , 'jpeg'], 'excludeDirs' => true]);
         foreach ($f as $fsk) {
             $paths = str::split($fsk , fs::separator());
             $form->skin->items->add($paths[2]);
         }
         //load content
         $pathskin = str::split($ini->ini->get('path' , 'SettingsFemale') , fs::separator());//Split окно чё блять )
         $form->Category_skin->selected = $pathskin[1];//Категория скинов 
         $form->skin->selected = $pathskin[2];//Выбранный скин
     }
}