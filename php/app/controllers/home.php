<?php

    namespace App\Controllers;

    class Home extends \Core\Controller {

        public function index($_URL){
            $this->client->authenticate();
            $this->view("home/index");
            $this->view->meta->title = "Home";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";

            $this->view->v->content = "";

            $feed = new \App\Models\Post\Feed();
            foreach($feed->postlist as $id){
                $post = new \App\Models\Post\Post($id);
                $this->view->v->content .= $post->toHtmlFeed();
            }
        }
        
    }

?>