<?php
namespace app\modules;

use std, gui, framework, app;

class theme extends AbstractModule {

    /**
     * Установить скин 
     */
    function EsetTheme (UXColor $background , UXColor $color) {
        $this->EsetChatTheme($background , $color);
        $this->EsetSettingsTheme($background , $color);
        $this->EsetUltimateTheme($background , $color);
        $this->EsetStoreTheme($background , $color);
        $this->EsetSettingsChatTheme($background);
        Logger::info('[Тема] => успешно установилась тема!');
    }
    
    /**
     * Установка темы chat 
     */
    protected function EsetChatTheme (UXColor $background , UXColor $color) {
        $chat = app()->getForm(chat);
        $chat->panel->backgroundColor = $color;
        $chat->panelAlt->backgroundColor = $color;
        $chat->panelerrorlist->backgroundColor = $color;
        //background
        $chat->layout->backgroundColor = $background;
    }    
    
    /**
     * Установка темы SettingsChat 
     */
    protected function EsetSettingsChatTheme (UXColor $background) {
        $chat = app()->getForm(SettingsChat);
        //background
        $chat->panel->backgroundColor = $background;
    }
        
    /**
     * Установка темы SettingsChat 
     */
    protected function EsetUltimateTheme (UXColor $background , UXColor $color) {
        $ultimate = app()->getForm(ultimate);
        $ultimate->panel->backgroundColor = $color;
        //background
        $ultimate->layout->backgroundColor = $background;
    }        
    
    /**
     * Установка темы Store 
     */
    protected function EsetStoreTheme (UXColor $background , UXColor $color) {
        $store = app()->getForm(store);
        $store->background->backgroundColor = $color;
        $store->form->backgroundColor = $color;
        $store->panel->backgroundColor = $color;
        $store->panelAlt->backgroundColor = $color;
        $store->panel3->backgroundColor = $color;
        //background
        $store->layout->backgroundColor = $background;
    }    
         
    /**
     * Установка темы chat 
     */
    protected function EsetSettingsTheme (UXColor $background , UXColor $color) {
        $settings = app()->getForm(Settings);
        $settings->leftmenu->backgroundColor = $color;
        $settings->rightmenu->backgroundColor = $color;
        $settings->Malepanel->backgroundColor = $color;
        $settings->modulesystem->backgroundColor = $color;
        $settings->Femalepanel->backgroundColor = $color;
        $settings->paneldown->backgroundColor = $color;
        $settings->leafaddons->backgroundColor = $color;
        $settings->paneltelegram->backgroundColor = $color;
        $settings->panelvk->backgroundColor = $color;
        $settings->panelwidget->backgroundColor = $color;
        $settings->skinpanel->backgroundColor = $color;
        //background
        $settings->panelmoveform->backgroundColor = $background;
    }

}