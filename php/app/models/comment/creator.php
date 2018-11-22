<?php

    namespace App\Models\Comment;

    class Creator {

        private $cs;

        public $id = -1;
        public $post = -1;
        public $content = "";

        public $error = false;
        public $errorMsg = "";

        public function __construct(){
            $this->cs = new \App\Models\Storage\Sql\CommentService();
        }

        public function comment($post,$content){
            $this->post = $post;
            $this->content = $content;
            if(!$this->validate()){ return false; }
            if(!$this->save()){ return false; }
            return true;
        }

        private function validate(){
            if($this->post < 1){ return $this->error("Der Post, welcher kommentiert werden soll, existiert nicht."); }
            if(preg_replace('/\s+/im','',$this->content) == ""){ return $this->error("Du kannst keine leeren Posts erstellen"); }
            if(strlen($this->content) > 255){ return $this->error("Ein Kommentar kann maximal 255 Zeichen enthalten"); }
            return true;
        }

        private function save(){
            $id = $this->cs->create($this->post,$this->content);
            if(!$id){ return $this->error("Beim Speichern des Kommentars ist ein Fehler aufgetreten"); }
            $this->id = $id;
            return true;
        }

        private function error($msg){
            $this->error = true;
            $this->errorMsg = $msg;
            return false;
        }
    }
?>