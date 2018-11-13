<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Media;

    /**
     * Loads, updates and deletes media.
     */
    class Uploader {

        /** Holds the MediaService
          * @var MediaService $ms */
        public $fs;

        /** Holds information about the file 
         * @var Array $file */
        public $file;

        /** Name of the file uploaded 
         * @var String $name */
        public $name = "Die Datei";

        /** Extension of the file 
         * @var String $extension*/
        public $extension = "";

        /** Accepted filetypes
         * @var String[] $accepted */
        public $accepted = [];

        /** Indicates if an error has occured
         * @var Boolean $error */
        public $error = false;

        /** Stores the latest error message 
         * @var String $errorMsg */
        public $errorMsg = "";

        /**
         * Uploads files to the server
         */
        public function __construct(){
            $this->fs = new \App\Models\Storage\File\FileService();
        }

        /** 
         * Upload a file.
         * @param Mixed[] $file File date from $_FILES
         * @param String *$name Name der Datei rspt. Referenz 
         * @return Boolean Returns true on success otherwise false
         */
        public function fetch($file,$name=null){
            $this->file = (object) $file;
            if(!empty($name)){ $this->name = $name; }
            $this->extension = strtolower(pathinfo($this->file->name,PATHINFO_EXTENSION));
            if($this->extension == "jpeg"){ $this->extension = "jpg"; }
            if(!$this->check()){ return false; }
            return true;
        }

        /** 
         * Checks for upload errors
         * @return Boolean Returns false if an error has occured during to upload and writes a message to $errorMsg.
         */
        private function check(){
            if($this->file->error == UPLOAD_ERR_INI_SIZE OR $this->file->error == UPLOAD_ERR_FORM_SIZE){ return $this->error($this->name." ist zu gross"); }
            if($this->file->error != UPLOAD_ERR_OK){ return $this->error($this->name." konnte nicht hochgeladen werden"); }
            if(!in_array($this->extension,$this->accepted)){ return $this->error($this->name." kann folgende Formate haben: .".join(", .",$this->accepted)); } 
            return true;
        }

        /**
         * Sets the accepable filetypes
         * @param String[] $filetypes An array with accepted extensions
         * @return Returns true if the accepted filetypes were set
         */
        public function accept($filetypes){
            return $this->accepted = $filetypes;
        }

        /**
         * Saves the file.
         * @param String $folder The path without the extension.
         * @param String $name The name of the file.
         * @return Boolean Returns true if the file was saved.
         */
        public function save($path){
            return $this->fs->saveUpload($this->file->tmp_name,$path.".".$this->extension);
        }

        /**
         * Returns the file.
         */
        public function get(){
            return $this->file;
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