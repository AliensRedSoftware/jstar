<?php
namespace app\forms;

use bundle\jurl\jURL;
use bundle\http\HttpClient;
use std, gui, framework, app;

class register extends AbstractForm {

    public $img_hash;

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        app()->getForm(store)->hidePreloader();
    }

    /**
     * @event showing 
     */
    function doShowing(UXWindowEvent $e = null) {    
        $this->Menu(true);
        app()->form(store)->showPreloader('Ожидание...');
    }

    /**
     * @event successuser.action 
     */
    function doSuccessuserAction(UXEvent $e = null) {    
        $url = "http://s2s5.space/bot/";
        if (trim($this->login->text) != null) {
            $login = $this->login->text;
            if (trim($this->email->text) != null) {
                $email = $this->email->text;
                if (str::contains(trim($email) , '@')) {
                    if(str::count($email , '@') == 1) {
                        if (trim($this->pass->text) != null) {
                            $pass = $this->pass->text;
                            if (trim($this->passAlt->text) != null) {
                                $passAlt = $this->passAlt->text;
                                if ($pass == $passAlt) {
                                    $httploginclient = new HttpClient();
                                    $request = $url . "bot/profile/getpassuser.php/logincheck/" . $login;
                                    $httploginclient->postAsync($request, [], function ($data) use ($login, $email, $pass, $url) {
                                        if ($data->body() == true) {
                                            UXDialog::show('Логин занят!', 'ERROR');
                                            return ;
                                        } else {
                                            $httpemailclient = new HttpClient();
                                            $request = $url . "bot/profile/getpassuser.php/emailcheck/" . $email;
                                            $httpemailclient->postAsync($request, [], function ($data) use ($login, $email, $pass, $url) {
                                                if ($data->body() == true) {
                                                    UXDialog::showAndWait('Email занят!' , 'ERROR');
                                                } else {
                                                    $img_hash = $this->getimg_hash();
                                                    $httpClient = new HttpClient();
                                                    $request = $url . "bot/profile/adduser.php/createuser/$login/$email/$pass/$img_hash";
                                                    $this->showPreloader('Идет обработка данных пожалуйста подождите...');
                                                    $httpClient->postAsync($request , [] , function ($data) {
                                                        if ($this->image != '.data/img/user.png') {
                                                            if (!is_dir('./data')) {
                                                                mkdir('./data');
                                                                $this->image->image->save('./data/avatar.png');
                                                            } else {
                                                                $this->image->image->save('./data/avatar.png');
                                                            }
                                                        }
                                                        
                                                        $this->hidePreloader();
                                                        UXDialog::showAndWait('Успешно зарегистрировались нажмите кнопку ок чтобы войти в аккаунт!' , 'INFORMATION');
                                                        $this->hide();
                                                        app()->form(auth)->showAndWait();
                                                    });
                                                }
                                            });
                                        }
                                    });
                                }
                                else {
                                    UXDialog::showAndWait('Пожалуйста проверти правильность паролей' , 'ERROR'); 
                                }
                            } else {
                                UXDialog::showAndWait('Пожалуйста введите повторый пароль' , 'ERROR'); 
                            }
                        } else {
                           UXDialog::showAndWait('Пожалуйста введите первое поля пароля' , 'ERROR'); 
                        }
                    } else {
                        UXDialog::showAndWait('Пожалуйста в поля Email должен быть 1 знак @ , а не ' . str::count($email , '@') , 'ERROR');
                    }
                } else {
                    UXDialog::showAndWait('Пожалуйста введите действительный Email' , 'ERROR');
                }
            } else {
                UXDialog::showAndWait('Пожалуйста введите Email' , 'ERROR');
            }
        } else {
            UXDialog::showAndWait('Пожалуйста введите логин' , 'ERROR');
        }
    }

    /**
     * @event image.click-Left 
     */
    function doImageClickLeft(UXMouseEvent $e = null) {    
        /**
        $img = new FileChooserScript();
        $img->on('action' , function () use ($img , $e) {
            $avatar = new UXImage($img->file);
            if ($avatar->width > 256) {
                UXDialog::showAndWait('Ширина больше 256!' , 'ERROR');
            } else if ($avatar->height > 256) {
                UXDialog::showAndWait('Высота больше 256!' , 'ERROR');
            } else {
                $e->sender->image = $avatar;
                $this->setimg_hash(md5_file('./data/avatar.png'));
                UXDialog::showAndWait('Заебись загрузилось!' , 'INFORMATION');
            }
        });
        $img->execute();
        */
    }

    
    /**
     * Установка hash аватарки 
     */
    function setimg_hash($file) {
        $this->img_hash = $file;
    }
    
    /**
     * Возвращение hash аватарки 
     */
    function getimg_hash () {
        return $this->img_hash;
    }
}
