<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Media\Image;

    /**
     * Editor extension for JPG images
     */
    class JPG extends Editor {

        public function __construct($path){
            $this->image = imagecreatefromjpeg($path);
            Parent::__construct($path);
        }

        public function save($path = null){
            if(empty($path)){ $path = $this->path; }
            if(imagejpeg($this->image, $this->path, 90)){ return $this->error(); }
            return true;
        }

    }
?>