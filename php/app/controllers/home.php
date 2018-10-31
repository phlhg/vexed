<?php

    namespace App\Controllers;

    class Home extends \Core\Controller {

        public function index($_URL){
            $this->client->authenticate();
            $this->view("home/index");
            $this->view->meta->title = "Home";
            $this->view->meta->description = "Das soziale Netzwerk. Erstelle ein Profil und trete der Community bei";

            $this->view->v->content = "";

            $postservice = new \App\Models\Storage\Sql\PostService();
            foreach($postservice->getAll() as $id){
                $post = new \App\Models\Post\Post($id);
                $this->view->v->content .= $post->toHtmlFeed();
            }
        }
        
    }

?>