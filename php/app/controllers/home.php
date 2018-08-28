<?php

    namespace App\Controllers;

    class Home extends \Core\Controller {

        public function index($_URL){
            $this->client->login();
            $this->view = new \Core\View("home/index");
            $this->view->meta->title = "Home";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";
        }

        public function signup($_URL){
            $this->view = new \Core\View("register/index");
            $this->view->meta->title = "Registrieren";
            $this->view->meta->description = "Nutzernamen, Passwort und eine E-Mail-Adresse - Mehr braucht es nicht um ein Profil zu erstellen";
        }

        public function profile($_URL){
            $this->view = new \Core\View("home/index");
            $this->view->meta->title = $_URL["username"];
            $this->view->meta->description = "Nutzernamen, Passwort und eine E-Mail-Adresse - Mehr braucht es nicht um ein Profil zu erstellen";
        }
    }

?>