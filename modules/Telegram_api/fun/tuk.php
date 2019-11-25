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
        $num = $GLOBALS['tuk']++;
        $textarea->text = $num;
        $form->layout->snapshot()->save('snapshot'  , 'png');
        $api->sendPhoto_id ($api->getChatid() , './snapshot');
        waitAsync('1000' , function () use ($form) {
            $form->hide();
        });
    });
    $form->show();
}
