<?php
namespace app\modules;

use facade\Json;
use std, gui, framework, app;

class systemmodules extends AbstractModule {
    
    /**
     * Создать новый модуль 
     */
    public function newmodule ($name , $descripton , $type , $selected) {
        $path = 'modules' . fs::separator() . 'Telegram_api' . fs::separator() . $type . fs::separator();
        if (fs::exists('modules' . fs::separator() . 'Telegram_api' . fs::separator() . 'install' . fs::separator() . "$name.json")) {
            UXDialog::showAndWait('Такой модуль уже есть!' , 'ERROR');
            return ;
        } 
        if ($name != null) {
            mkdir($path. $name);
            Json::toFile('modules' . fs::separator() . 'Telegram_api' . fs::separator() . 'install' . fs::separator() . "$name.json" , 
            [
                "name" => $name,
                "description" => $descripton,
                "selected" => $selected,
                "type" => $type,
                "path" => [
                    $path . $name . ".php" ,
                ]
            ]
            );
            stream::putContents($path . fs::separator() . $name . fs::separator() . "$name.php" , 
            '<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    main();
});

function main () {
    $api = new app\classes\jTelegramApi;
    $api->sendMessage_id($api->getChatid() , "Привет :)");
}'
            );
            $this->updatelist($type , $selected);
            UXDialog::showAndWait('Успешно создался модуль!' , 'INFORMATION');
        } else {
            UXDialog::showAndWait('Нужно ввести имя нового модуля!' , 'ERROR');
        }
        app()->getForm(Settings)->namemodules->clear();
    }
    
    /**
     * Обновление списка 
     */
     public function updatelist ($type , $selected) {
         $Settings = app()->getForm(Settings);
         $Settings->listmodules->items->clear();
         $dir = fs::scan('modules' . fs::separator() . 'Telegram_api' . fs::separator() . 'install' ,  ['extensions' => ['json'], 'excludeDirs' => false]);
         foreach ($dir as $json) {
             if (json::fromFile($json)['type'] == $type && json::fromFile($json)['selected'] == $selected) {
                 $Settings->listmodules->items->add(json::fromFile($json)['name']);
             }
         }
         if ($Settings->listmodules->selectedIndex == -1) {
             $Settings->listmodules->selectedIndex = 0;
         }
     }
     
     /**
      * Удалить модуль! 
      */
      public function deletemodule ($name , $type) {
          if(uiConfirm('Вы точно хотите удалить модуль ?')) {
              $path = 'modules' . fs::separator() . 'Telegram_api' . fs::separator() . 'install' . fs::separator();
              fs::delete($path . "$name.json");
          }
      }
}