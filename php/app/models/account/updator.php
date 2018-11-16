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
            if(htmlspecialchars_decode(__CLIENT()->name) == $name){ return true; }
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
            if(htmlspecialchars_decode(__CLIENT()->description) == $description){ return true; }
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
            if(htmlspecialchars_decode(__CLIENT()->website) == $website){ return true; }
            foreach(\Core\Config::get("blacklist_websites") as $key){ if(strpos(strtolower($website),$key) > -1){ return $this->error("Diese Website kann nicht verlinkt werden"); }}
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
            //UPLOAD
            $uploader = new \App\Models\Media\Uploader();
            $uploader->accept(\Core\Config::get("user_pb_types"));
            if(!$uploader->fetch($pb,"Das Profilbild")){ return $this->error($uploader->errorMsg); }
            //IMAGE
            $image = \App\Models\Media\Image::loadUpload($uploader->get());
            $image->editor->minDim(300);
            $image->editor->cropCenter(300,300);
            if($image->error){ return $this->error("Fehler beim komprimieren"); };
            //DELETE
            foreach(\Core\Config::get("user_pb_types") as $ex){ if(file_exists($dir.$id.".".$ex)){ unlink($dir.$id.".".$ex); }}
            //SAVE
            if(!$image->save("/img/profile/pb/".$id)){ return $this->error("Fehler beim speichern"); };
            $image->editor->maxDim(20);
            if($image->error){ return $this->error("Fehler beim komprimieren"); };
            foreach(\Core\Config::get("user_pb_types") as $ex){ if(file_exists($dir.$id."_tiny.".$ex)){ unlink($dir.$id."_tiny.".$ex); }}
            if(!$image->save("/img/profile/pb/".$id."_tiny",true)){ return $this->error("Fehler beim speichern"); };
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
            //UPLOAD
            $uploader = new \App\Models\Media\Uploader();
            $uploader->accept(\Core\Config::get("user_bg_types"));
            if(!$uploader->fetch($pbg,"Der Hintergrund")){ return $this->error($uploader->errorMsg); }
            //IMAGE
            $image = \App\Models\Media\Image::loadUpload($uploader->get());
            $image->editor->maxDim(720);
            if($image->error){ return $this->error("Fehler beim komprimieren"); };
            //DELETE
            foreach(\Core\Config::get("user_bg_types") as $ex){ if(file_exists($dir.$id.".".$ex)){ unlink($dir.$id.".".$ex); }}
            //SAVE
            if(!$image->save("/img/profile/bg/".$id)){ return $this->error("Fehler beim speichern"); };
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