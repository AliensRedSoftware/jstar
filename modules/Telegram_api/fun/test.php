<?php
use app , std , framework , gui;

UXApplication::runLater(function () {
    main();
});

function main () {
    $api = new app\classes\jTelegramApi;
    messbox($api);
    
}

function messbox ($api) {
    $form = new UXForm();
    $form->title = 'Сообщение :)';
    $form->size = [320 , 240];
    $form->centerOnScreen();
    $textarea = new UXTextArea();
    $textarea->size = [304 , 192];
    $textarea->position = [8,8];
    $form->add($textarea);
    $form->on('show' , function () use ($form , $textarea , $api) {
        $textarea->text = 'Hello )';
        $form->layout->;
        $api->sendPhoto_id ($api->getChatid() , './snapshot');
        waitAsync('500' , function () use ($form) {
            $form->hide();
        });
    });
    $form->show();
}
