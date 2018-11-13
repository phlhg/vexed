<?php

    namespace App\Controllers;

    class Profile extends \Core\Controller {

        public function pb($_URL){
            if(!$this->client->authenticate()){ return false; }
            $this->view("out");
            $this->view->setTemplate("none");
            $id = $_URL["id"];
            if(!isset($id) && is_int($id)){ $id = 0; }
            $folder = $_SERVER["DOCUMENT_ROOT"].'/php/files/img/profile/pb/';
            $filetype = false;
            $filetypes = ["jpg","png","gif"];
            foreach($filetypes as $type){
                if(is_readable($folder.$id.'.'.$type)){
                    $filetype = $type;
                }
            }

            if($filetype !== false){
                $this->view->setFormat("image/".$filetype);
                $this->view->v->content = file_get_contents($folder.$id.'.'.$filetype);
            } else {
                $this->view->setFormat("image/jpg");
                $this->view->v->content = file_get_contents($folder."0.jpg");
            }
        }
        
    }

?>