<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Account;

    /**
     * Handles the process of updating an account.
     */
    class Updator {

        /** Holds the UserService 
         * @var UserService $us*/
        private $us;

        /** Holds the Validator
         * @var Validator $validator*/
        private $validator;

        /** Holds the latest error message 
         * @var String $errorMsg */
        public $errorMsg = "";

        /**
         * Handles the process of updating an account.
         */
        public function __construct(){
            $this->us = new \App\Models\Storage\Sql\UserService();
            $this->validator = new \App\Models\Account\Validator();
        }

        /**
         * Updates the username of the client.
         * @param String $name The new username.
         * @return Boolean Returns true if the name was changed else false.
         */
        public function username($name){
            if(__CLIENT()->name == $name){ return true; }
            if(!$this->validator->isUsername($name)){ return $this->error("Der Nutzername enthält ungültige Zeichen"); }
            if($this->validator->usedUsername($name)){ return $this->error("Der Nutzername wird bereits verwendert"); }
            if(!$this->us->updateName($name)){ return $this->error("Der Nutzername konnte nicht aktualisiert werden"); }
            return true;
        }

        /**
         * Updates the description of the client.
         * @param String $description The new description.
         * @return Boolean Returns true if the description was changed else false.
         */
        public function description($description){
            if(__CLIENT()->description == $description){ return true; }
            if(strlen($description) > 255){ return $this->error("Die Beschreibung darf nur 255 Zeichen lang sein"); }
            if(!$this->us->updateDescription($description)){ return $this->error("Die Beschreibung konnte nicht aktualisiert werden"); }
            return true;
        }  

        /**
         * Updates the website of the client.
         * @param String $website The new website.
         * @return Boolean Returns true if the website was changed else false.
         */
        public function website($website){
            if(__CLIENT()->website == $website){ return true; }
            if(!$this->us->updateWebsite($website)){ return $this->error("Die Website konnte nicht aktualisiert werden"); }
            return true;
        }

        /**
         * Updates the pb of the client.
         * @param String[] $pb An array with information from the uploaded file.
         * @return Boolean Returns true if the pb was changed else false.
         */
        public function pb($pb){
            $id = strval(__CLIENT()->id);
            $dir = $_SERVER["DOCUMENT_ROOT"]."/php/files/img/profile/pb/";
            if($pb["error"] == 1){ return $this->error("Das Profilbild ist zu gross - Bitte verwende ein kleineres"); }
            if($pb["error"] != 0){ return $this->error("Das Profilbild konnte nicht hochgeladen werden"); }
            $extension = strtolower(pathinfo($pb['name'])["extension"]);
            if($extension == "jpeg"){ $extension = "jpg"; }
            if(!in_array($extension,["jpg","png"])){ return $this->error("Nur .jpg & .png können als Profilbild hochgeladen werden"); }
            foreach(["jpg","png"] as $ex){ if(file_exists($dir.$id.".".$ex)){ unlink($dir.$id.".".$ex); }}
            if(!move_uploaded_file($pb['tmp_name'], $dir.$id.".".$extension)){ 
                return $this->error("Das Profilbild konnte nicht aktualisiert werden"); 
            }
            return true;
        }

        /**
         * Updates the bg of the client.
         * @param String[] $pbg An array with information from the uploaded file.
         * @return Boolean Returns true if the bg was changed else false.
         */
        public function pbg($pbg){
            $id = strval(__CLIENT()->id);
            $dir = $_SERVER["DOCUMENT_ROOT"]."/php/files/img/profile/bg/";
            if($pbg["error"] == 1){ return $this->error("Das Hintergrundbild ist zu gross - Bitte verwende ein kleineres"); }
            if($pbg["error"] != 0){ return $this->error("Das Hintergrundbild konnte nicht hochgeladen werden"); }
            $extension = strtolower(pathinfo($pbg['name'])["extension"]);
            if($extension == "jpeg"){ $extension = "jpg"; }
            if(!in_array($extension,["jpg","png"])){ return $this->error("Nur .jpg & .png können als Hintergrundbild hochgeladen werden"); }
            foreach(["jpg","png"] as $ex){ if(file_exists($dir.$id.".".$ex)){ unlink($dir.$id.".".$ex); }}
            if(!move_uploaded_file($pbg['tmp_name'], $dir.$id.".".$extension)){
                return $this->error("Das Hintergrundbild konnte nicht aktualisiert werden"); 
            }
            return true;
        }

        /**
         * Triggers an error and sets the latest error message.
         * @param String $msg Message for the error.
         * @return Boolean Returns always false.
         */
        private function error($msg){
            $this->errorMsg = $msg;
            return false;
        }

     }