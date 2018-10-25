<?php

    namespace App\Models\Post;

    class Post {

        private $postservice; 

        public $id = -1;
        public $type = Type::UNDEFINED;
        public $text = "";
        public $media = -1;
        public $time = 0;

        public function __construct($id){
            $this->postservice = new \App\Models\Storage\Sql\PostService();
        }

    }

?>