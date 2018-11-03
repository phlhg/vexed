<?php

    namespace App\Controllers;

    class Login extends \Core\Controller {

        public function index($_URL,$error="",$info="",$username="",$password="",$remember=false){
            $this->view("login/index");
            $this->view->meta->title = "Login";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";
            $this->view->v->page_menu_class = "transparent-menu login-menu";
            $this->view->v->login_form_error = $error;
            $this->view->v->login_form_info = $info;
            $this->view->v->login_used_username = $username;
            $this->view->v->login_used_password = $password;
            $this->view->v->login_used_remember = $remember;
        }

        public function logout($_URL){
            $this->view("out");
            $this->client->logout();
            $re = (\Helpers\Get::exists("re") ? \Helpers\Get::get("re") : "/");
            if(substr($re,0,1) != "/" OR strpos($re,"http") === 0){ $re = "/"; }
            $this->view->v->content = $re;
            \App::$router->redirect($re);
        }
    }

?>