<?php

    namespace App\Controllers;

    class Login extends \Core\Controller {

        public function index($_URL,$error="",$info="",$username="",$password="",$remember=false){
            $this->view = new \Core\View("login/index");
            $this->view->menu = "page/menu/login";
            $this->view->meta->title = "Login";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";
            $this->view->v->login_form_error = $error;
            $this->view->v->login_form_info = $info;
            $this->view->v->login_used_username = $username;
            $this->view->v->login_used_password = $password;
            $this->view->v->login_used_remember = $remember;
        }
    }

?>