<?php

    namespace App\Controllers;
    use Helpers\Post;

    class CreatePost extends \Core\Controller {

        public function index($_URL){
            if(!$this->client->authenticate()){ return false; }
            $this->view("createpost/index");
            $this->view->meta->title = "Neuer Beitrag";
            $this->view->meta->description = "Ein bisschen Text hier. Ein Foto da. Und schon hast du deinen eigenen Beitrag";
            $this->view->v->form_info = "";
            $this->view->v->form_error = "";
            $this->view->v->form_description = "";

            $this->view->v->notice = "";

            $this->view->addScript("/js/createpost.js");


            $creator = new \App\Models\Post\Creator();

            if(\Core\Config::get("post_disabled")){
                $this->view->v->form_info = \Core\Config::get("post_disabled_msg");
                return false;
            } else {
                if(count(\App\Models\Post\Post::byUser(__CLIENT()->id)) < 1){
                    $this->view->v->notice = 'Erstelle deinen ersten Post und teile deine lustigsten Momente mit deinen Freunden 🎉';
                }
            }

            if(\Helpers\Post::exist(["description","ph_tkn"])){
                if(!\Helpers\Token::check(\Helpers\Post::get("ph_tkn"))){
                    $this->view->v->form_error = "Ungültiger Token - Anfrage abgelehnt";
                    return false;
                }
                $description = \Helpers\Post::get("description");
                $image = (\Helpers\File::exists("image") ? [\Helpers\File::get("image")] : []);
                $this->view->v->form_description = $description;
                if(!$creator->post($description,$image)){
                    $this->view->v->form_error = $creator->errorMsg;
                    return false;
                }
                \App::$router->redirect("/");
            }
        }

    }

?>