<?php
use app, std, framework, gui;

function main () {
    $api = new app\classes\jTelegramApi;
    $stickpack = [
            0 => 'CAADAgADT0AAAuCjggcuO1Eat5jdpwI' ,
            1 => 'CAADAgADSwAD3U04DgmuSqi6NLDfAg' ,
            2 => 'CAADAgADOgAD3U04Dl5-B8cNIWFRAg' ,
            3 => 'CAADAgADXgcAAskyyA97Enj4eSH46wI' ,
            4 => 'CAADAgADKwADiiBhFFupUyO1iQikAg' ,
            5 => 'CAADAgADSgADiiBhFEP0-vG6N29xAg' ,
            6 => 'CAADBAADRwADQOKfErdAa_S9Ra7AAg'
        ];
    $api->sendSticker_id($api->getChatid(), $stickpack[rand(0,count($stickpack) - 1)]);
}