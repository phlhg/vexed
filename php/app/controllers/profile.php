<?php

    namespace App\Controllers;

    class Profile extends \Core\Controller {

        public function index($_URL){
            $this->client->authenticate();
            $this->view("profile/index");
            $this->view->meta->title = $_URL["username"];
            $this->view->meta->description = "Nutzernamen, Passwort und eine E-Mail-Adresse - Mehr braucht es nicht um ein Profil zu erstellen";
        }
    }

?>