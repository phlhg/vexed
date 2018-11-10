<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Storage\File;

    /**
     * Saves, loads and deletes files.
     */
    class FileService {

        /** The root of the filestrorage.
         * @var String $root */
        public $root;
        
        /**
         * Saves, loads and deletes files.
         */
        public function __construct(){
            $this->root = $_SERVER["DOCUMENT_ROOT"]."/php/files";
        }

        /**
         * Generates an absolute path to the file.
         * @param String $path The relative path to the file.
         * @return String Returns the absolute path to the file.
         */
        private function makePath($path){
            return $this->root.$path;
        }

        /**
         * Checks if the file exists.
         * @param String $path The relative path to the file.
         * @return Boolean Returns true if the file exists.
         */
        public function exists($path){
            return file_exists($this->makePath($path));
        }

        /**
         * Saves an upload.
         * @param String $file The tmp_name of an uploaded file.
         * @param String $path The relative path to the location to save.
         * @return Boolean Return true if the file was saved.
         */
        public function saveUpload($file,$path){
            return move_uploaded_file($file,$this->makePath($path));
        }

        /**
         * Deletes a file.
         * @param String $path The relative path to the file.
         * @return Boolean Returns true if the file was deleted.
         */
        public function delete($path){
            return unlink($this->makePath($path));
        }

    }

?>
