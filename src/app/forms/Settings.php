<?php
namespace app\forms;

use facade\Json;
use std, gui, framework, app;
use app\modules\vkModule as VK;

class Settings extends AbstractForm {

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {
        $this->Menu(false);
    }

    /**
     * @event colorcorrection.click-Left 
     */
    function doColorcorrectionClickLeft(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        if($e->sender->selected) {
            $MainForm->image->colorAdjustEffect->enable();
            $MainForm->image->colorAdjustEffect->brightness = $this->brightness->value;
            $MainForm->image->colorAdjustEffect->contrast = $this->contrast->value;
            $MainForm->image->colorAdjustEffect->hue = $this->hue->value;
            $MainForm->image->colorAdjustEffect->saturation = $this->saturation->value;
            $MainForm->toast('Цветовая коррекция->Вкл');
        }
        else {
            $MainForm->image->colorAdjustEffect->disable();
            $MainForm->toast('Цветовая коррекция->Выкл');
        }
    }

    /**
     * @event brightness.mouseDrag 
     */
    function doBrightnessMouseDrag(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->colorAdjustEffect->brightness = $e->sender->value;
    }

    /**
     * @event brightness.mouseDown 
     */
    function doBrightnessMouseDown(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->colorAdjustEffect->brightness = $e->sender->value;
    }

    /**
     * @event contrast.mouseDrag 
     */
    function doContrastMouseDrag(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->colorAdjustEffect->contrast = $e->sender->value;
    }

    /**
     * @event contrast.mouseDown 
     */
    function doContrastMouseDown(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->colorAdjustEffect->contrast = $e->sender->value;
    }

    /**
     * @event hue.mouseDown 
     */
    function doHueMouseDown(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->colorAdjustEffect->hue = $e->sender->value;
    }

    /**
     * @event hue.mouseDrag 
     */
    function doHueMouseDrag(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->colorAdjustEffect->hue = $e->sender->value;
    }

    /**
     * @event saturation.mouseDown 
     */
    function doSaturationMouseDown(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->colorAdjustEffect->saturation = $e->sender->value;
    }

    /**
     * @event saturation.mouseDrag 
     */
    function doSaturationMouseDrag(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->colorAdjustEffect->saturation = $e->sender->value;
    }

    /**
     * @event reloadcolorcorrection.action 
     */
    function doReloadcolorcorrectionAction(UXEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $this->brightness->value = 0;
        $this->contrast->value = 0;
        $this->hue->value = 0;
        $this->saturation->value = 0;
        $this->Save();
        $MainForm->toast('Эффект был восстановлен');
        $this->LoadLeaf($MainForm->image);
    }

    /**
     * @event selectedShadow.click-Left 
     */
    function doSelectedShadowClickLeft(UXMouseEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        if($e->sender->selected) {
            $MainForm->image->dropShadowEffect->enable();
            $MainForm->image->dropShadowEffect->radius = $this->radiusShadow->value;
            $MainForm->image->dropShadowEffect->spread = $this->Intensity->value;
            $MainForm->image->dropShadowEffect->offsetX = $this->transetX->value;
            $MainForm->image->dropShadowEffect->offsetY = $this->transetY->value;
            $MainForm->toast('Отбрасываемая тень->Вкл');
        }
        else {
            $MainForm->image->dropShadowEffect->disable();
            $MainForm->toast('Отбрасываемая тень->Выкл');
        }
    }

    /**
     * @event resetShadow.action 
     */
    function doResetShadowAction(UXEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        $this->colorPicker->value = UXColor::of('#b3b3b3');
        $this->radiusShadow->value = 10;
        $this->Intensity->value = 0;
        $this->transetX->value = 0;
        $this->transetY->value = 0;
        $this->Save();
        $MainForm->toast('Эффект был восстановлен');
        $this->LoadLeaf($MainForm->image);
    }

    /**
     * @event transetY.mouseDown 
     */
    function doTransetYMouseDown(UXMouseEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->dropShadowEffect->offsetY = $e->sender->value;
    }

    /**
     * @event transetY.mouseDrag 
     */
    function doTransetYMouseDrag(UXMouseEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->dropShadowEffect->offsetY = $e->sender->value;
    }

    /**
     * @event transetX.mouseDown 
     */
    function doTransetXMouseDown(UXMouseEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->dropShadowEffect->offsetX = $e->sender->value;
    }

    /**
     * @event transetX.mouseDrag 
     */
    function doTransetXMouseDrag(UXMouseEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->dropShadowEffect->offsetX = $e->sender->value;
    }

    /**
     * @event Intensity.mouseDrag 
     */
    function doIntensityMouseDrag(UXMouseEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->dropShadowEffect->spread = $e->sender->value;
    }

    /**
     * @event Intensity.mouseDown 
     */
    function doIntensityMouseDown(UXMouseEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->dropShadowEffect->spread = $e->sender->value;
    }

    /**
     * @event radiusShadow.mouseDrag 
     */
    function doRadiusShadowMouseDrag(UXMouseEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->dropShadowEffect->radius = $e->sender->value;
    }

    /**
     * @event radiusShadow.mouseDown 
     */
    function doRadiusShadowMouseDown(UXMouseEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->dropShadowEffect->radius = $e->sender->value;
    }

    /**
     * @event spoilerAlt.click-Left 
     */
    function doSpoilerAltClickLeft(UXMouseEvent $e = null) {    
        if($e->sender->expanded == true) {
            $e->sender->toFront();
        }
        else {
            //$e->sender->toBack();
        }
    }

    /**
     * @event spoiler.click-Left 
     */
    function doSpoilerClickLeft(UXMouseEvent $e = null) {    
        if($e->sender->expanded == true) {
            $e->sender->toFront();
        }
        else {
            $e->sender->toBack();
        }
    }

    /**
     * @event colorPicker.action 
     */
    function doColorPickerAction(UXEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->image->dropShadowEffect->color = $e->sender->value;
    }


    /**
     * @event addbd.keyDown-Enter 
     */
    function doAddbdKeyDownEnter(UXKeyEvent $e = null) {    
        $this->addbd($e->sender->text , []);
        $e->sender->clear();
    }

    /**
     * @event bd.action 
     */
    function doBdAction(UXEvent $e = null) {
        //$this->savebd($this->list->itemsText , $e->sender->selected);
        $this->selectedbd();
    }
    
    /**
     * Выбрать бд 
     */
    function selectedbd () {
        $selected = $this->bd->selected;
        $this->magicmodules->selected = $this->bdini->get('magicmodules' , $selected);
        $this->autostart->selected = $this->bdini->get('start' , $selected);
        $magicmodule = $this->magicmodules->selected;
        if ($magicmodule == true) {
            $this->text->enabled = false;
            $this->packconstruct->enabled = false;
            $this->autostart->enabled = true;
        } else {
            $this->text->enabled = true;
            $this->packconstruct->enabled = true;
            $this->autostart->enabled = false;
        }
        $this->list->itemsText = $this->bdini->get('key' , $selected);
        $this->form('ultimate')->alllist->selected = $this->bdini->get('all' , $selected);
        $this->form('ultimate')->imageurl->selected = $this->bdini->get('imageurl' , $selected);
        $this->addbd->text = $selected;
        $this->checkmodule(false);
    }

    function checkmodule ($checkmode) {
        if ($this->magicmodules->selected && $checkmode == true && str::startsWith($this->bd->selected , '/')) {
            if(uiConfirm('Внимание вы точно хотите перейти в модульный режим ?')) {
                $this->toast('Успешно перешли в новый режим');
                $this->magicmodules->selected = true;
                $this->list->items->clear();
                $this->bdini->set('key' , $this->list->itemsText , $this->bd->selected);
                app()->getForm(moduleslist)->showAndWait();
            } else {
                $this->toast('Успешно не перешли в новый режим');
                $this->magicmodules->selected = false;
            }
            $this->bdini->set('magicmodules' , $this->magicmodules->selected , $this->bd->selected);
            $this->text->enabled = !$this->magicmodules->selected;
        } else if (!$this->magicmodules->selected && $checkmode == true) {
            if(uiConfirm('Внимание вы точно хотите уйти от этого режима ?')) {
                $this->toast('Успешно ушли от этого режима');
                $this->magicmodules->selected = false;
                $this->list->items->clear();
                $this->bdini->set('key' , $this->list->itemsText , $this->bd->selected);
            } else {
                $this->toast('Успешно перешли в новый режим');
                $this->magicmodules->selected = true;
            }
            $this->bdini->set('magicmodules' , $this->magicmodules->selected , $this->bd->selected);
            $this->text->enabled = !$this->magicmodules->selected;
        } else if ($checkmode == true) {
            UXDialog::showAndWait('Ошибка отсуствует символ / , он должен быть первым' , 'ERROR');
            $this->magicmodules->selected = false;
            $this->bdini->set('key' , $this->list->itemsText , $this->bd->selected);
        } else {
            //pre('test');
            //$this->savebd();
        }
        $this->selectedmoduleindex->enabled = $this->magicmodules->selected;
    }
    
    /**
     * @event text.keyDown-Enter 
     */
    function doTextKeyDownEnter(UXKeyEvent $e = null) {    
        $this->additeambd($this->text->text , $this->bd->selected);
        $e->sender->clear();
    }

    /**
     * @event list.action 
     */
    function doListAction(UXEvent $e = null) {    
        $this->text->text = $e->sender->selectedItem;
    }

    /**
     * @event list.keyDown-Delete 
     */
    function doListKeyDownDelete(UXKeyEvent $e = null) {    
        $this->deleteElementIteambd();
    }
    
    function deleteElementIteambd() {
        if($this->list->selectedItem != null && !$this->magicmodules->selected) {
            $this->toast('Уничтожена->' . $this->list->selectedItem);
            $this->bdini->removeSection($this->list->selectedItem);
            $this->list->items->removeByIndex($this->list->selectedIndex);
            $this->savebd($this->list->itemsText , $this->bd->selected);
        }
        else {
            if ($this->magicmodules->selected) {
                $this->toast('Невозможно удалить в режиме модуля');
            } else {
                $this->toast('Невозможно удалить ничего = )');
            }
        }
    }
    
    /**
     * Добавление новой бд 
     */
    function addbd ($bd , array $list) {
        if($bd != null) {
            foreach ($this->bd->items as $val) {
                if($val == $bd) {
                    $this->toast('Такое уже есть лол');
                    return;
                }
            }
            $kus = null;
            foreach ($list as $kiss_list) {
                $kus .= $kiss_list . "\n";
            }
            if (!str::startsWith($bd , '/')) {
                $this->bd->items->add($bd);
                $this->bdini->set('key' , $this->bd->itemsText , 'section');
                $this->bdini->set('key' , trim($kus) , $bd);
                $this->bd->selected = $bd;
            } 
            else {
                $text = str::lower($bd);
                $this->bd->items->add($text);
                $this->bdini->set('key' , $this->bd->itemsText , 'section');
                $this->bdini->set('key' , trim($kus) , $text);
                $this->bd->selected = $text;
            } 
        } else {
            $this->toast('Нужно ввести текст');
        }
    }
    
    function deletebd() {
        $this->bdini->removeSection($this->bd->selected);
        $this->toast('Уничтожена->' . $this->bd->selected);
        $this->bd->items->removeByIndex($this->bd->selectedIndex);
        $this->bdini->set('key' , $this->bd->itemsText , 'section');
        if($this->bd->selected == null) {
            $this->bd->selectedIndex = 0;
        }
    }

    /**
     * @event Category_skin.action 
     */
    function doCategory_skinAction(UXEvent $e = null) {    
        $this->setskin($e->sender->selectedIndex);
    }

    /**
     * @event skin.action 
     */
    function doSkinAction(UXEvent $e = null) {
        if ($this->Category_skin->selected != null && $e->sender->selected != null) {
            $this->form('MainForm')->showPreloader('Идет установка скина...');
            $path = 'skin' . fs::separator() . $this->Category_skin->selected . fs::separator() . $e->sender->selected;
            Element::loadContentAsync($this->form('MainForm')->image , $path , function () {
                $this->form('MainForm')->hidePreloader();
            });
        }
    }

    /**
     * @event Category_skin.construct 
     */
    function doCategory_skinConstruct(UXEvent $e = null) { 
        $this->reloadskin();
    }

    /**
     * @event sizeimage.mouseDrag 
     */
    function doSizeimageMouseDrag(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->width = $e->sender->value;
        $MainForm->image->width = $MainForm->width;
        $MainForm->height = $e->sender->value;
        $MainForm->image->height = $MainForm->height;
    }

    /**
     * @event sizeimage.click 
     */
    function doSizeimageClick(UXMouseEvent $e = null) {    
        $MainForm = app()->getForm(MainForm);
        $MainForm->width = $e->sender->value;
        $MainForm->image->width = $MainForm->width;
        $MainForm->height = $e->sender->value;
        $MainForm->image->height = $MainForm->height;  
    }

    function smoothAnimation (UXToggleButton $e = null , UXPanel $panel = null , $time) {
        if ($e->selected) {
            $e->enabled = false;
            $panel->visible = true;
            Animation::fadeIn($panel , $time);
            Animation::fadeIn($panel , $time , function () use ($e) {
                $e->enabled = true;
            });
        } else {
            $e->enabled = false;
            Animation::fadeOut($panel , $time);
            Animation::fadeOut($panel , $time , function () use ($e , $panel) {
                $e->enabled = true;
                $panel->visible = false;
            });
        }
    }
    
    /**
     * @event Asynx_token.action 
     */
    function doAsynx_tokenAction(UXEvent $e = null) {
        $api = new jTelegramApi();
        $api->connectToTelegram();
    }
    
    /**
     * @event editsection.action 
     */
    function doEditsectionAction(UXEvent $e = null) {
        foreach ($this->bd->items->toArray() as $value) {
            if ($value == $this->addbd->text) {
                $this->toast('Такое уже есть лол');
                return ;
            }
        }
        if ($this->bd->selected && trim($this->addbd->text) != null) {
            $this->bdini->removeSection($this->bd->selected);
            $this->toast($this->bd->selected . " => " . trim($this->addbd->text));
            $this->bd->items->set($this->bd->selectedIndex , $this->addbd->text);
            $this->bdini->set('key' , $this->bd->itemsText , 'section');
            $this->bdini->set('key' , $this->list->itemsText , $this->bd->selected);
        } else {
            $this->toast("");
        }
    }

    function replaceiteambd () {
        if ($this->list->selectedItem != null && !$this->magicmodules->selected) {
            foreach ($this->list->items as $value) {
                if ($this->text->text == $value) {
                    $this->toast('Такое уже есть лол');
                    $this->text->clear();
                    return;
                }
            }
            $this->toast('Ответ->' . $this->list->selectedItem . ' | заменен на ->' . $this->text->text);
            $this->list->items->replace($this->list->selectedItem , $this->text->text);
        }
        else {
            if ($this->magicmodules->selected) {
                $this->toast('Невозможно заменить в режиме модуля');
            } else {
                $this->toast('Невозможно заменить ничего = )');
            }
        }
    }
    
    /**
     * Занести новую бд 
     */
    function additeambd ($txt , $bd) {
        if($txt != null) {
            if($this->list->items->isEmpty() == true) {
                $this->list->items->add($this->text->text);
                $this->toast('Дабавлен ответ->' . $txt . ' | на этот вопрос->' . $bd);
                $this->bdini->set('key' , $this->list->itemsText , $bd);
            }
            else {
                foreach ($this->list->items as $item) {
                    if($item == $txt) {
                        $this->toast('Такое уже есть лол');
                        return ;
                    }
                }
                $this->list->items->add($txt);
                $this->toast('Дабавлен ответ->' . $txt . ' | на этот вопрос->' . $bd);
                $this->bdini->set('key' , $this->list->itemsText , $bd);
            }
        }
        else {
            $this->toast('Такое уже есть лол');
        }
    }

    /**
     * @event magicmodules.click-Left 
     */
    function doMagicmodulesClickLeft(UXMouseEvent $e = null) {    
        $this->checkmodule(true);
    }

    /**
     * @event bd.keyDown-Delete 
     */
    function doBdKeyDownDelete(UXKeyEvent $e = null) {    
        $this->deletebd();
    }
    /**
     * Перебор тоглов и выставление true 
     */
    function fortoggleon(array $btn , $type) {
        foreach ($btn as $lo) {
            $lo->enabled = $type;
        }
    }
    
    /**
     * @event loginvk.action 
     */
    function doLoginvkAction(UXEvent $e = null) {
        VK::checkAuth();
    }

    /**
     * @event longpoll.action 
     */
    function doLongpollAction(UXEvent $e = null) {
        $MainForm = app()->getForm(MainForm);
        if($e->sender->selected) {
            $e->sender->text = 'Отключить long-Poll';
            $e->sender->graphic = new UXImageView (new UXImage('res://.data/img/Exit.png'));
            VK::longPollConnect(function($updates) use ($MainForm) {
                foreach($updates as $update) {
                    switch($update[0]) {
                        case '4':
                            $Settings = app()->getForm(Settings);
                            if($Settings->idgroup->selected == false) {
                                if($update[3] == $Settings->groupandid->value) {
                                    $module4 = new module4();
                                    $module4->module4_get($update[3] , $update[6]);
                                }
                            }
                            else {
                                if($update[2] != 35) {
                                   $module4 = new module4();
                                   $module4->module4_get($update[3] , $update[6]);
                                }
                            }
                        break;
                        
                        case '8':
                            //$this->lpText->text = ('Пользователь id' . $update[1] . ' стал онлайн');
                        break;
                        
                        case '9':
                            //$this->lpText->text = ('Пользователь id' . $update[1] . ' стал оффлайн');
                        break;
                       
                        case '61':
                            //$this->lpText->text = ('Пользователь id' . $update[1] . ' набирает сообщение');
                        break;
                    }
                }
            });
            $MainForm->toast('long-poll подключился успешно!');
        } 
        else {
            $e->sender->text = 'Подключиться к long-poll';
            VK::longPollDisconnect();
            $e->sender->graphic = new UXImageView (new UXImage('res://.data/img/action.png'));
            $MainForm->toast('long-poll отключился успешно!');
        }
    }

    /**
     * @event valueX.globalKeyDown-Enter 
     */
    function doValueXGlobalKeyDownEnter(UXKeyEvent $e = null) {
        $form = app()->getForm(MainForm);
        $form->x = $e->sender->value;
    }

    /**
     * @event valueY.globalKeyDown-Enter 
     */
    function doValueYGlobalKeyDownEnter(UXKeyEvent $e = null) {    
        $form = app()->getForm(MainForm);
        $form->y = $e->sender->value;
    }

    /**
     * @event icotype.construct 
     */
    function doIcotypeConstruct(UXEvent $e = null) {    
        $this->getpack($this);
    }

    /**
     * @event icotype.action 
     */
    function doIcotypeAction(UXEvent $e = null) {    
        $this->getpack($this);
    }

    /**
     * @event icoselected.action 
     */
    function doIcoselectedAction(UXEvent $e = null) {    
        $this->selectedico($this);
    }

    /**
     * @event icopackselected.action 
     */
    function doIcopackselectedAction(UXEvent $e = null) {
        $this->checkico($this);
    }

    /**
     * @event installpackico.action 
     */
    function doInstallpackicoAction(UXEvent $e = null) {    
        $this->installpackico($this);
    }
    
    /**
     * @event colorPicker_background.action 
     */
    function doColorPicker_backgroundAction(UXEvent $e = null) {    
        $this->EsetTheme($e->sender->value , $this->colorPicker_panel->value);
    }

    /**
     * @event colorPicker_panel.action 
     */
    function doColorPicker_panelAction(UXEvent $e = null) {    
        $this->EsetTheme($this->colorPicker_background->value , $e->sender->value);
    }

    /**
     * @event info.action 
     */
    function doInfoAction(UXEvent $e = null) {
        $this->toast('Автор:->Merkus622');
    }

    /**
     * @event mod.action 
     */
    function doModAction(UXEvent $e = null){
        $this->fortoggleon([
            $this->telegram ,
            $this->vk ,
            $this->widgetgirl
        ] , false);
        $this->smoothAnimation($e->sender , $this->skinpanel , 1000);
    }

    /**
     * @event mod.step 
     */
    function doModStep(UXEvent $e = null) {
        if ($e->sender->selected) {
        } else {
            if ($this->skinpanel->opacity == 0) {
                $this->fortoggleon([
                    $this->telegram ,
                    $this->vk ,
                    $this->widgetgirl
                ] , true);
            }
        } 
    }

    /**
     * @event telegram.action 
     */
    function doTelegramAction(UXEvent $e = null) {
        $this->fortoggleon([
            $this->mod ,
            $this->vk ,
            $this->widgetgirl
        ] , false);
        $this->smoothAnimation($e->sender , $this->paneltelegram , 1000);
    }

    /**
     * @event telegram.step 
     */
    function doTelegramStep(UXEvent $e = null) {
        if ($e->sender->selected) {
        } else {
            if ($this->paneltelegram->opacity == 0) {
                $this->fortoggleon([
                    $this->mod ,
                    $this->vk ,
                    $this->widgetgirl
                ] , true);
            }
        } 
    }

    /**
     * @event vk.action 
     */
    function doVkAction(UXEvent $e = null) {
        $this->fortoggleon([
            $this->telegram ,
            $this->mod ,
            $this->widgetgirl
        ] , false);
        $this->smoothAnimation($e->sender , $this->panelvk , 1000);
    }

    /**
     * @event vk.step 
     */
    function doVkStep(UXEvent $e = null) {
        if ($e->sender->selected) {
        } else {
            if ($this->panelvk->opacity == 0) {
                $this->fortoggleon([
                    $this->telegram ,
                    $this->mod ,
                    $this->widgetgirl
                ] , true);
            }
        } 
    }

    /**
     * @event widgetgirl.action 
     */
    function doWidgetgirlAction(UXEvent $e = null) {
        $this->fortoggleon([
            $this->telegram ,
            $this->vk ,
            $this->mod
        ] , false);
        $this->smoothAnimation($e->sender , $this->panelwidget , 1000);
    }

    /**
     * @event widgetgirl.step 
     */
    function doWidgetgirlStep(UXEvent $e = null) {
        if ($e->sender->selected) {
        
        } else {
            if ($this->panelwidget->opacity == 0) {
                $this->fortoggleon([
                    $this->telegram ,
                    $this->vk ,
                    $this->mod
                ] , true);
            }
        } 
    }

    /**
     * @event toggleButtonmodulesystem.action 
     */
    function doToggleButtonmodulesystemAction(UXEvent $e = null) {
        if($e->sender->selected) {
            $this->Female->selected = false;
            $this->Male->selected = false;
            $this->effect->selected = false;
            //toggle
            $this->modulesystem->visible = true;
            //off
            $this->Malepanel->visible = false;
            $this->Femalepanel->visible = false;
            $this->leafaddons->visible = false;
        }
        else {
            //on
            $this->Femalepanel->visible = true;
            //off
            $this->leafaddons->visible = false;
        }
    }

    /**
     * @event effect.action 
     */
    function doEffectAction(UXEvent $e = null) {
        if($e->sender->selected) {
            $this->Female->selected = !$e->sender->selected;
            $this->Male->selected = false;
            $this->toggleButtonmodulesystem->selected = false;
            //toggle
            $this->leafaddons->visible = true;
            //off
            $this->Malepanel->visible = false;
            $this->Femalepanel->visible = false;
            $this->modulesystem->visible = false;
        }
        else {
            if($e->sender->selected == false) {
                if($this->Female->selected == false) {
                    $this->Female->selected = true;
                }
            }
            //on
            $this->Femalepanel->visible = true;
            //off
            $this->leafaddons->visible = false;
        }
    }

    /**
     * @event Female.action 
     */
    function doFemaleAction(UXEvent $e = null) {
        if($e->sender->selected) {
            $this->Male->selected = !$e->sender->selected;
            $this->effect->selected = false;
            $this->toggleButtonmodulesystem->selected = false;
            //toggle
            $this->Femalepanel->visible = true;
            //off
            $this->Malepanel->visible = false;
            $this->leafaddons->visible = false;
            $this->modulesystem->visible = false;
        }
        else {
            if($e->sender->selected == false) {
                if($this->Male->selected == false) {
                    $this->Male->selected = true;
                }
            }
            //on
            $this->Malepanel->visible = true;
            //off
            $this->Femalepanel->visible = false;
        }
    }

    /**
     * @event Male.action 
     */
    function doMaleAction(UXEvent $e = null) {
        if($e->sender->selected) {
            $this->Female->selected = !$e->sender->selected;
            $this->effect->selected = false;
            $this->toggleButtonmodulesystem->selected = false;
            //toggle
            $this->Malepanel->visible = true;
            //off
            $this->Femalepanel->visible = false;
            $this->leafaddons->visible = false;
            $this->modulesystem->visible = false;
        }
        else {
            if($e->sender->selected == false) {
                if($this->Female->selected == false) {
                    $this->Female->selected = true;
                }
            }
            //on
            $this->Malepanel->visible = false;
            //off
            $this->Femalepanel->visible = true;
        }
    }

    /**
     * @event selectedmoduleindex.action 
     */
    function doSelectedmoduleindexAction(UXEvent $e = null) {
        app()->getForm(moduleslist)->showAndWait();
    }

    /**
     * @event selectedmoduleindex.construct 
     */
    function doSelectedmoduleindexConstruct(UXEvent $e = null) {
        $this->checkmodule(false);
    }

    /**
     * @event replacelist.action 
     */
    function doReplacelistAction(UXEvent $e = null) {
        $this->replaceiteambd();
    }

    /**
     * @event openfile.action 
     */
    function doOpenfileAction(UXEvent $e = null) {
        $this->fileChooserimg->execute();
    }

    /**
     * @event packconstruct.action 
     */
    function doPackconstructAction(UXEvent $e = null) {
        $this->Menu(false);
        app()->getForm(ultimate)->showAndWait();
    }





    /**
     * @event keyDown-Ctrl+S 
     */
    function doKeyDownCtrlS(UXKeyEvent $e = null) {    
        $this->savebd($this->list->itemsText , $this->bd->selected);
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null) {
        //$this->updatebd($this->list , $this->bd->selected);   
    }

    /**
     * @event autostart.click-Left 
     */
    function doAutostartClickLeft(UXMouseEvent $e = null) {
        if ($e->sender->selected) {
            $this->toast('Успешно включен автостарт модуля!');
        } else {
           $this->toast('Успешно выключен автостарт модуля!');
        }
        $this->bdini->set('start' , $e->sender->selected , $this->bd->selected);
    }

    /**
     * @event addmodules.action 
     */
    function doAddmodulesAction(UXEvent $e = null) {    
        $this->newmodule(trim($this->namemodules->text) , trim($this->descriptionmodules->text) , trim($this->type->selected) , trim($this->typemodules->selected));
    }

    /**
     * @event type.action 
     */
    function doTypeAction(UXEvent $e = null) {    
        $this->updatelist($e->sender->selected , $this->typemodules->selected);
    }

    /**
     * @event typemodules.action 
     */
    function doTypemodulesAction(UXEvent $e = null) {    
        $this->updatelist($this->type->selected , $e->sender->selected);
    }


    /**
     * @event deletemodules.action 
     */
    function doDeletemodulesAction(UXEvent $e = null) {
        $this->deletemodule($this->listmodules->selected , $this->type->selected);
    }
}
