<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Post;
    use App\Models\Storage\Sql\PostService;

    /**
     * Handles the process of creating a new post.
     */
    class Creator {

        /** Holds the id of the new post.
         * @var Int $id */
        public $id = -1;

        /** Defines the type of the post.
         * @var Type $type */
        public $type = Type::UNDEFINED;

        /** Holds the content of the post.
         * @var String $text */
        public $text = "";

        /** Holds an array of medias for the post.
         * @var Mixed[] $media*/
        public $media = [];

        /** Holds wether there is an error or not.
         * @var Boolean $error */
        public $error = false;

        /** Human readable error message 
         * @var String $errorMsg */
        public $errorMsg = "";

        /** The postservice for the current class 
         * @var \PostService $postservice */
        private $postservice;

        public function __construct(){
            $this->postservice = new \App\Models\Storage\Sql\PostService();
        }

        public function post($text, $media = []){
            $this->text = $text;
            $this->media = $media;
            $this->type = $this->getType();
            return $this->makeText();
        }

        private function getType(){
            if(count($this->media) > 0){
                return Type::MEDIA;
            } else {
                return Type::TEXT;
            }
        }

        private function validate(){
            if(strlen($text) > 250){
                return $this->error("Die maximale Textlänge wurde überschritten");
            }
        }

        private function make(){
            if($this->type == Type::MEDIA){
                return $this->makeMedia();
            } else {
                return $this->makeText();
            }
        }

        private function makeText(){
            if(!$this->postservice->createText($this->text)){
                return $this->error("Beitrag konnte nicht erstellt werden");
            }
            return true;
        }

        private function makeMedia(){
            return $this->error("Diese Funktion befindet sich noch in Entwicklung");
        }

        /**
         * Logs an error. (Use return to terminate after the error)
         * @param String $msg Human readable error information.
         * @return Boolean Returns always false
         */
        private function error($msg){
            $this->error = true;
            $this->errorMsg = $msg;
            return false;
        }

    }

?>