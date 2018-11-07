<?php

    namespace App\Controllers;

    class Home extends \Core\Controller {

        public function index($_URL){
            if(!$this->client->authenticate()){ return false; }
            $this->view("home/index");
            $this->view->meta->title = "Start";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";

            $this->view->v->content = "";

            $feed = new \App\Models\Post\Feed();
            if(count($feed->postlist) < 1){ return __ROUTER()->setRoute("Home/empty"); }
            foreach($feed->postlist as $id){
                $post = new \App\Models\Post\Post($id);
                $this->view->v->content .= $post->toHtmlFeed();
            }
        }

        public function empty($_URL){
            $this->view("home/empty");
            $this->view->meta->title = "Start";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";
        }
        
    }

?>