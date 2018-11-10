<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Media;

    /**
     * Loads, updates and deletes media.
     */
    class Image {
        
        /* Filetype enumerators */
        const UNKNOWN = 0;
        const JPG = 1;
        const PNG = 2;
        const GIF = 3;

        /** Supported formats
         * @var String[] $supported 
         * @static */
        public static $supported = [
            "jpg" => Self::JPG,
            "jpeg" => Self::JPG,
            "png" => Self::PNG,
            "gif" => Self::GIF
        ];

        /** MediaService instance
          * @var MediaService $ms */
        public $fs;

        /** Type of the image 
         * @var Int $type*/
        public $type = Image::UNKNOWN;

        /** Path to the original image
         * @var String $path */
        public $path = "";

        /** Editor for the current filetype
         * @var Array $file */
        public $editor;

        /** If an error has occured
         * @var Boolean $error*/
        public $error = false;

        /** Latest error message 
         * @var String $errorMsg */
        public $errorMsg = "";

        /**
         * Simple class to edit images
         * @param Mixed[] $file File date from $_FILES
         */
        public function __construct(){
            $this->fs = new \App\Models\Storage\File\FileService();
        }

        /**
         * An image from a path.
         * @param String $path Path to the image
         * @return Boolean Returns true if the image was succesfully loaded.
         */
        public function load($path){
            $this->path = $_SERVER["DOCUMENT_ROOT"]."/php/files".$path;
            if(!$this->setType()){ return false; }
            if(!$this->initEditor()){ return false; }
            return true;
        }

        /**
         * Gets the image type for the editor.
         * @return Boolean Returns true if the type is supported.
         */
        private function setType(){
            $this->type = Self::UNKNOWN;
            $ext = strtolower(pathinfo($this->path,PATHINFO_EXTENSION));
            if(!in_array($ext,array_keys(Self::$supported))){ return $this->error($this->path."Nicht unterstütztes Bildformat"); }
            $this->type = Self::$supported[$ext];
            return true;
        }

        /**
         * Loads the suitable editor 
         * @return Boolean Returns true if a suitable editor was found.
        */
        private function initEditor(){
            switch($this->type){
                case Self::JPG:
                    $this->editor = new \App\Models\Media\Image\JPG($this->path);
                    break;
                case Self::PNG:
                    $this->editor = new \App\Models\Media\Image\PNG($this->path);
                    break;
                case Self::GIF:
                    $this->editor = new \App\Models\Media\Image\GIF($this->path);
                    break;
                default:
                    return $this->error("Nicht unterstütztes Bildformat");
            }
            return true;
        }

        /**
         * Saves the image.
         * @param String $path* A location to save the image. If empty the inital path is taken.
         * @return Boolean Returns true if the image was saved.
         */
        public function save($path=null){
            return $this->editor->save($path);
        }

        /**
         * Triggers an error and sets the latest error message.
         * @param String $msg Message for the error.
         * @return Boolean Returns always false.
         */
        private function error($msg){
            $this->error = true;
            $this->errorMsg = $msg;
            return false;
        }

    }

?>