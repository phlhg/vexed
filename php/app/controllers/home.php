<?php

    namespace App\Controllers;

    class Home extends \Core\Controller {

        public function index($_URL){
            if(!$this->client->authenticate()){ return false; }
            $this->view("home/index");
            $this->view->meta->title = "Start";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";
            $this->view->addScript("/js/feed.js");

            $this->view->v->content = "";
            $this->view->v->notice = "";

            if(__CLIENT()->description == ""){
                $this->view->v->notice = 'Füge deinem <a href="/p/'.__CLIENT()->name.'/">Profil</a> eine Beschreibung hinzu, damit die anderen Nutzer mehr über dich erfahren können 😉';
            }

            if(count(\App\Models\Post\Post::byUser(__CLIENT()->id)) < 1){
                $this->view->v->notice = 'Noch keine Posts?! 🤔<br/>Erstelle deinen ersten Post, indem du auf "<i class="material-icons">add</i>" klickst';
            }
        }

        public function _empty($_URL){
            $this->view("Home/empty");
            $this->view->meta->title = "Start";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";
        }
        
    }

?>