<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Media\Image;

    /**
     * Editor extension for GIF images
     */
    class GIF extends Editor {

        public function __construct($path){
            $this->image = imagecreatefromgif($path);
            Parent::__construct($path);
        }

        public function save($path){
            if(!empty($path)){ copy($this->path,$path); }
            //if(imagejpeg($this->image, $this->path)){ return $this->error(); }
            return true;
        }

    }
?>