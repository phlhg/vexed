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
        
    }

?>