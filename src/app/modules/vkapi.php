<?php
namespace app\modules;

use std, gui, framework, app;
use app\modules\vkModule as api;

class vkapi extends AbstractModule {

    /**
     * Отправить сообщение
     */
    public function sendmessage_id ($id, $txt) {
        api::Query('messages.send', ['user_id' => $id, 'message' => $txt], function() {});
    }
    
    /**
     * Получить id группы асинхронный
     */
     public function getByIdAsync($idgroup, $callback) {
         $this->QueryAsync('groups.getById', ['group_ids' => $idgroup], function ($r) use ($callback) {
             if (is_callable($callback)) {
                 $callback($r['response'][0]['id']);
             }
         });
     }
     
     /**
      * Получить кол-во пикч в группе асинхронна
      */
     public function getCountPhotoAsync($idgroup, $album_id, $callback) {
         $this->QueryAsync('photos.get', ['owner_id' => $idgroup, 'album_id' => $album_id], function ($r) use ($callback) {
             if (is_callable($callback)) {
                 $callback($r['response']['count']);
             }
         });
     }
     
     /**
      * Возвращает рандомную пикчу с группы асинхронна
      * @return photos
      */
     public function getRandomPicturesGroupAsync($idgroup, $count, $album_id, $offset, $callback) {
         $this->QueryAsync('photos.get', ['owner_id' => $idgroup, 'album_id' => $album_id, 'count' => $count, 'offset' => $offset], function ($r) use ($callback) {
             if (is_callable($callback)) {
                 $iteamArray = $r['response']['items'];
                 for ($x = 0; $x < count($iteamArray); $x++) {
                     $its .= $iteamArray[$x]['sizes'][count($iteamArray[$x]['sizes']) - 1]['url'] . "\n";
                 }
                 $s = explode("\n" , $its);
                 $pic = $s[rand(0,count($s) - 2)];
                 $callback($pic);
             }
         });
     }
    
    /**
     * Отправить запрос асинхронный
     */
    public function QueryAsync ($method, $value = [], $callback) {
        api::Query($method, $value, function ($req) use ($callback) {
            if (is_callable($callback)) {
                $callback($req);
            }
        });
    }
}
