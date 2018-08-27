<?php

    namespace App\Controllers;

    class Login extends \Core\Controller {

        public function index($_URL,$msg=""){
            $this->view = new \Core\View("login/index",$this->client);
            $this->view->meta->title = "Login";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";
            $this->view->v->login_form_msg = $msg;
        }
    }

?>