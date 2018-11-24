<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Controllers;

    class Ajax extends \Core\Controller {

        private $ajax;
        private $url;

        public function _switch($_URL){
            if((!\Helpers\Check::isAjax() && \Core\Config::get("application_version_stage") == true) OR !\Helpers\Token::check(\Helpers\Token::get())){ \App::$router->setRoute("Error/E403"); }
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Charset: utf-8");
            $this->view("out");
            $this->view->setTemplate("none");
            $this->view->setFormat("application/json");
            $this->ajax = new \App\Models\Ajax\Ajax();
            $this->url = $_URL;
            if($this->client->isLoggedIn()){
                if(isset($this->url["function"]) && !empty($this->url["function"])){
                    $this->action($this->url["function"]);
                } else {
                    $this->error("Es wurde keine Funktion übergeben");
                }
            } else {
                $this->denied();
            }
            $this->view->v->content = $this->ajax->bake();
        }

        /* INTERNAL */

        private function action($f){
            switch($f){
                case "rel_follow":
                    $this->follow();
                    break;
                case "rel_unfollow":
                    $this->unfollow();
                    break;
                case "vote_up":
                    $this->vote_up();
                    break;
                case "vote_down":
                    $this->vote_down();
                    break;
                case "vote_neutral":
                    $this->vote_neutral();
                    break;
                case "post_delete":
                    $this->post_delete();
                    break;
                case "feed_init":
                    $this->feed_init();
                    break;
                case "feed_latest":
                    $this->feed_latest();
                    break;
                case "feed_previous":
                    $this->feed_previous();
                    break;
                case "feed_check":
                    $this->feed_check();
                    break;
                case "search":
                    $this->search();
                    break;
                default:
                    $this->error("");
                    break;
            }
        }

        private function denied(){
            $this->ajax->rspn = \App\Models\Ajax\Response::DENIED;
            $this->ajax->value = [];
            $this->ajax->info = "LOGIN";
        }

        private function error($msg){
            $this->ajax->rspn = \App\Models\Ajax\Response::ERROR;
            $this->ajax->value = [];
            $this->ajax->info = $msg;
        }

        private function warning($msg){
            $this->ajax->rspn = \App\Models\Ajax\Response::WARNING;
            $this->ajax->value = [];
            $this->ajax->info = $msg;
        }

        /* FUNCTIONS */

        private function follow(){
            if(!\Helpers\Get::exist(["user"])){ return $this->error("Fehlende Parameter - Parameter [user]"); }
            $user = new \App\Models\Account\User(\Helpers\Get::get("user"));
            if(!$user->exists){ return $this->warning("Der Nutzer wurde nicht gefunden"); }
            if(!$user->follow()){ return $this->warning("Der Nutzer konnte nicht abonniert werden"); }
            $this->ajax->value["state"] = $user->relation;
            return true;
        }

        private function unfollow(){
            if(!\Helpers\Get::exist(["user"])){ return $this->error("Fehlende Parameter - Parameter [user]"); }
            $user = new \App\Models\Account\User(\Helpers\Get::get("user"));
            if(!$user->exists){ return $this->warning("Der Nutzer wurde nicht gefunden"); }
            if(!$user->unfollow()){ return $this->warning("Der Nutzer konnte nicht deabonniert werden"); }
            $this->ajax->value["state"] = $user->relation;
            return true;
        }

        private function vote_up(){
            if(!\Helpers\Get::exist(["post"])){ return $this->error("Fehlende Parameter - Parameter [post]"); }
            $post = new \App\Models\Post\Post(\Helpers\Get::get("post"));
            if(!$post->exists){ return $this->warning("Der Post wurde nicht gefunden"); }
            if(!$post->upVote()){ return $this->warning("Der Post konnte nicht upgevotet werden."); }
            $this->ajax->value["vote"] = $post->clientVote;
            $this->ajax->value["votes"] = $post->votes;
            return true;
        }  
        
        private function vote_down(){
            if(!\Helpers\Get::exist(["post"])){ return $this->error("Fehlende Parameter - Parameter [post]"); }
            $post = new \App\Models\Post\Post(\Helpers\Get::get("post"));
            if(!$post->exists){ return $this->warning("Der Post wurde nicht gefunden"); }
            if(!$post->downVote()){ return $this->warning("Der Post konnte nicht downgevotet werden."); }
            $this->ajax->value["vote"] = $post->clientVote;
            $this->ajax->value["votes"] = $post->votes;
            return true;
        } 
        
        private function vote_neutral(){
            if(!\Helpers\Get::exist(["post"])){ return $this->error("Fehlende Parameter - Parameter [post]"); }
            $post = new \App\Models\Post\Post(\Helpers\Get::get("post"));
            if(!$post->exists){ return $this->warning("Der Post wurde nicht gefunden"); }
            if(!$post->unVote()){ return $this->warning("Der Post konnte nicht geunvotet werden."); }
            $this->ajax->value["vote"] = $post->clientVote;
            $this->ajax->value["votes"] = $post->votes;
            return true;
        }   

        private function post_delete(){
            if(!\Helpers\Get::exist(["post"])){ return $this->error("Fehlende Parameter - Parameter [post]"); }
            $post = new \App\Models\Post\Post(\Helpers\Get::get("post"));
            if(!$post->exists){ return $this->warning("Der Post wurde nicht gefunden"); }
            if(!$post->delete()){ return $this->warning("Der Post konnte nicht gelöscht werden."); }
            return true;
        }

        private function feed_init(){
            $feed = new \App\Models\Post\Feed();
            $feed->loadInit();
            $this->ajax->value["feed"] = [];
            foreach($feed->postlist as $id){
                $post = new \App\Models\Post\Post($id);
                $this->ajax->value["feed"][] = $post->toArray();
            }
            return true;
        }

        private function feed_latest(){
            if(!\Helpers\Get::exist(["last"])){ return $this->error("Fehlende Parameter - Parameter [last]"); }
            $feed = new \App\Models\Post\Feed();
            $feed->loadLatest(\Helpers\Get::get("last"));
            $this->ajax->value["feed"] = [];
            foreach($feed->postlist as $id){
                $post = new \App\Models\Post\Post($id);
                $this->ajax->value["feed"][] = $post->toArray();
            }
            return true;
        }

        private function feed_previous(){
            if(!\Helpers\Get::exist(["prev"])){ return $this->error("Fehlende Parameter - Parameter [prev]"); }
            $feed = new \App\Models\Post\Feed();
            $feed->loadPrevious(\Helpers\Get::get("prev"));
            $this->ajax->value["feed"] = [];
            foreach($feed->postlist as $id){
                $post = new \App\Models\Post\Post($id);
                $this->ajax->value["feed"][] = $post->toArray();
            }
            return true;
        }

        private function feed_check(){
            if(!\Helpers\Get::exist(["last"])){ return $this->error("Fehlende Parameter - Parameter [last]"); }
            $feed = new \App\Models\Post\Feed();
            $feed->loadLatest(\Helpers\Get::get("last"));
            $this->ajax->value["new"] = count($feed->postlist);
            return true;
        }
        
        private function search(){
            if(!\Helpers\Get::exist(["string"])){ return $this->error("Fehlende Parameter - Parameter [post]"); }
            $query = new \App\Models\Search\Query(urldecode(urldecode(\Helpers\Get::get("string"))));
            if(!$query->search()){ return $this->warning($query->errorMsg); }
            $this->ajax->value["results"] = [];
            foreach(array_slice($query->results,0,10) as $id){
                $user = new \App\Models\Account\User($id);
                $this->ajax->value["results"][] = $user->toHtml();
            }
            return true;
        }
        
    }

?>