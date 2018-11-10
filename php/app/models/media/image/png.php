<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Media\Image;

    /**
     * Editor extension for PNG images
     */
    class PNG extends Editor {

        public function __construct($path){
            $this->image = imagecreatefrompng($path);
            Parent::__construct($path);
        }

        public function save($path){
            if(empty($path)){ $path = $this->path; }
            if(imagepng($this->image, $this->path, 6)){ return $this->error(); }
            return true;
        }

    }
?>