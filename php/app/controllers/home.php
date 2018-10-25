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
            foreach($postservice->getAll() as $post){
                $user = new \App\Models\Account\User($post["user"]);
                $this->view->v->content .= $user->name."<br/><strong style='font-weight: bold;'>".$post["description"]."</strong><br/>".date("d.m.Y H:i:s",$post["date"])."<br/><br/>";
            }
        }
        
    }

?>