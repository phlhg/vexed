<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Media;

    /**
     * Loads, updates and deletes media.
     */
    class Creator {

        private $ms;

        public $id;

        public $post;

        public $image;

        public $error = false;

        public $errorMsg = "";

        public function __construct(){
            $this->ms = new \App\Models\Storage\Sql\MediaService();
        }

        public function create($post,$image){
            $this->post = $post;
            $this->image = $image;
            if(!$this->createEntry()){ return false; }
            if(!$this->processImage()){ $this->ms->delete($this->id); return false; }
            return true;
        }

        private function createEntry(){
            $id = $this->ms->create($this->post,Self::getType($this->image->type),0);
            if($id == false){ return $this->error("Das Bild konnte nicht gespeichert werden [DB]"); }
            $this->id = $id;
            return true;
        }

        private function processImage(){
            $this->image->editor->maxDim(720);
            if(!$this->image->save("/media/".$this->id)){ return $this->error("Das Bild konnte nicht gespeichert werden [FS]"); }
            return true;
        }

        private static function getType($type){
            if($type == \App\Models\Media\Image::JPG){ return "jpg"; }
            if($type == \App\Models\Media\Image::PNG){ return "png"; }
            if($type == \App\Models\Media\Image::GIF){ return "gif"; }
            return "err";
        }

        private function error($msg){
            $this->error = true;
            $this->errorMsg = $msg;
            return false;
        }

    }
?>