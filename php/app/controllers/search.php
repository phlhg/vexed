<?php

    namespace App\Controllers;

    class Search extends \Core\Controller {

        public function index($_URL){
            if(!$this->client->authenticate()){ return false; }
            $this->handlePost();
            $this->view("search/index");
            $this->view->meta->title = "Entdecken";
            $this->view->meta->description = "Entdecke neue Beiträge und finde deine Freunde.";

            if(count(\App::$client->subscriptions) < 1){
                $this->view->v->profile_suggestions_title = "Beliebte Nutzer";
                $this->view->v->profile_suggestions = \App\Models\Suggestions::popularUsers(4);
            } else {
                $this->view->v->profile_suggestions_title = "Nutzer die du kennen könntest";
                $this->view->v->profile_suggestions = \App\Models\Suggestions::knownUsers(4);
            }

            $this->view->v->post_suggestions = \App\Models\Suggestions::popularPosts(12);
        }

        public function query($_URL){
            if(!$this->client->authenticate()){ return false; }
            $this->handlePost();
            $string = htmlspecialchars(urldecode(urldecode($_URL["query"])));
            $this->view("search/query");
            $this->view->meta->title = 'Entdecken';
            $this->view->meta->description = "Entdecke neue Beiträge und finde deine Freunde.";

            $this->view->v->query = $string;
            $this->view->v->q_error = "";
            $this->view->v->q_results = [];
            $query = new \App\Models\Search\Query($string);
            if(!$query->search()){
                $this->view->v->q_error = $query->errorMsg;
                return;
            }
            $this->view->v->q_results = $query->results;
        }

        private function handlePost(){
            if(\Helpers\Post::exists("q")){ 
                if(\Helpers\Post::get("q") == ""){
                    \App::$router->redirect("/search/");
                } else {
                    //By https://stackoverflow.com/questions/26087133/htaccess-redirect-to-url-in-encoded-form
                    \App::$router->redirect("/search/".urlencode(urlencode(\Helpers\Post::get("q")))."/");
                }
            }
        }
        
    }

?>