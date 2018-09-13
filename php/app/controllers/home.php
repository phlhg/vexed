<?php

    namespace App\Controllers;

    class Home extends \Core\Controller {

        public function index($_URL){
            $this->client->authenticate();
            $this->view = new \Core\View("home/index");
            $this->view->meta->title = "Home";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";
        }
        
    }

?>