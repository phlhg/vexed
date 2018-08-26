<?php

    namespace App\Controllers;

    class Home extends \Core\Controller {

        public function index(){
            if(!$this->login()){ return false; }
            $this->view = new \Core\View("home/index");
            $this->view->meta->title = "Home";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";
            $this->view->v->client = $this->client;
        }

        public function signup(){
            $this->view = new \Core\View("register/index");
            $this->view->meta->title = "Registrieren";
            $this->view->meta->description = "Nutzernamen, Passwort und eine E-Mail-Adresse - Mehr braucht es nicht um ein Profil zu erstellen";
            $this->view->v->client = $this->client;
        }

        //RESTRUCTURE ROUTER CLASS AS PLANNED
        public function profile($_URL){
            $this->view = new \Core\View("home/index");
            $this->view->meta->title = $_URL["username"];
            $this->view->meta->description = "Nutzernamen, Passwort und eine E-Mail-Adresse - Mehr braucht es nicht um ein Profil zu erstellen";
            $this->view->v->client = $this->client;
        }
    }

?>