<?php

    namespace App\Controllers;

    class Post extends \Core\Controller {

        public function index($_URL){
            if(!$this->client->authenticate()){ return false; }
            $this->view("post/index");
            $post = new \App\Models\Post\Post($_URL["id"]);
            if(!$post->exists){ return __ROUTER()->redirect("/"); }
            $poster = new \App\Models\Account\User($post->user);
            $this->view->meta->title = $poster->displayName;
            $this->view->meta->description = $post->text_nf;
            $this->view->meta->image = (isset($post->media[0]) ? "/img/media/".$post->media[0]."/" : null);

            $this->view->v->post = $post;
            $this->view->v->poster = $poster;
        }
        
    }

?>