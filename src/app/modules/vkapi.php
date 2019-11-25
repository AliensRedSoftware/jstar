<?php
namespace app\modules;

use std, gui, framework, app;
use app\modules\vkModule as api;

class vkapi extends AbstractModule {

    /**
     * Отправить сообщение
     */
    public function sendmessage_id ($id , $txt) {
        api::Query('messages.send', [ 'user_id' => $id , 'message' => $txt]);
    }
    
    /**
     * Получить id группы
     */
     public function getById($idgroup) {
         return api::Query('groups.getById' , ['group_ids' => $idgroup])['response'][0]['id'];
     }
     
     /**
      * Получить кол-во пикч в группе
      */
     public function getCountPhoto($idgroup , $album_id) {
         return api::Query('photos.get' , ['owner_id' => $idgroup , 'album_id' => $album_id])['response']['count'];
     }
     
     /**
      * Возвращает рандомную пикчу с группы
      * @return photos
      */
     public function getRandomPicturesGroup($idgroup , $count , $album_id , $offset) {
         $iteamArray = api::Query('photos.get' , ['owner_id' => $idgroup , 'album_id' => $album_id , 'count' => $count , 'offset' => $offset])['response']['items'];
         for ($x = 0;$x < count($iteamArray);$x++){
             $its .= $iteamArray[$x]['sizes'][6]['url'] . "\n";
         }
         $s = explode("\n" , $its);
         $pic = $s[rand(0,count($s) - 2)];
         return $pic;
     }
    
    /**
     * Отправить запрос
     */
    public function Query ($method , $value = []) {
        api::Query($method, $value);
    }
        
    /**
     * Возвращает запрос
     */
    public function getQuery ($method , $value = []) {
        return api::Query($method, $value);
    }
}
