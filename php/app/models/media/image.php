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
        public $type = Self::UNKNOWN;

        /** Initial data of the image 
         * @var Resource $image */
        public $data = null;

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
        public function __construct($data,$type = Self::UNKNOWN){
            $this->fs = new \App\Models\Storage\File\FileService();
            $this->data = $data;
            $this->type = $type;
            $this->editor = new \App\Models\Media\Editor(imagecreatefromstring($this->data));
        }

        /**
         * Saves the image.
         * @param String $path The location to save the image.
         * @param Bool $force Save gifs and destroy animation.
         * @return Bool Returns true if the image was saved.
         */
        public function save($path,$force=false){
            $path = \Core\Config::get("storage_root").$path;
            switch($this->type){
                case Self::JPG:
                    return imagejpeg($this->editor->image, $path.".jpg", 90);
                    break;
                case Self::PNG:
                    return imagepng($this->editor->image, $path.".png", 6);
                    break;
                case Self::GIF:
                    //Workaround for animated gifs.
                    //if($force){ return file_put_contents($path.".gif",$this->data); }
                    return imagegif($this->editor->image, $path.".gif");
                    break;
                default:
                    return false;
            }
        }

        /**
         * Loads an image from a given relative path.
         * @param String $path A relative path to the image.
         * @return Self|Boolean Returns an instance of itself on success else false.
         */
        public static function load($path){
            $type = Self::getType($path);
            if($type == Self::UNKNOWN){ return false; }
            return new Self(file_get_contents(\Core\Config::get("storage_root").$path),$type);
        }

        /**
         * Loads an image from an uploaded file.
         * @param Mixed[] $file Resource of $_FILES* 
         * @return Self|Boolean Returns an instance of itself on success else false.
         */
        public static function loadUpload($file){
            $file = (object) $file;
            $type = Self::getType($file->name);
            if($type == Self::UNKNOWN){ return false; }
            return new Self(file_get_contents($file->tmp_name),$type);
        }

        /**
         * Gets the image type for the editor.
         * @return Boolean Returns true if the type is supported.
         */
        private static function getType($path){
            $ext = strtolower(pathinfo($path,PATHINFO_EXTENSION));
            if(!in_array($ext,array_keys(Self::$supported))){ return Self::UNKNOWN; }
            return Self::$supported[$ext];
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