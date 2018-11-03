<?php

    namespace App\Controllers;

    class Search extends \Core\Controller {

        public function index($_URL){
            $this->client->authenticate();
            $this->view("search/index");
            $this->view->meta->title = "Suchen";
            $this->view->meta->description = "Entdecke neue Beiträge und finde deine Freunde.";

            if(count(\App::$client->subscriptions) < 1){
                $this->view->v->profile_suggestions_title = "Beliebte Nutzer";
                $this->view->v->profile_suggestions = \App\Models\Suggestions::popularUsers(4);
            } else {
                $this->view->v->profile_suggestions_title = "Nutzer die du kennen könntest";
                $this->view->v->profile_suggestions = \App\Models\Suggestions::knownUsers(4);
            }

            $this->view->v->post_suggestions = \App\Models\Suggestions::popularPosts(6);
        }
        
    }

?>