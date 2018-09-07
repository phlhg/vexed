<?php

    namespace App\Models;

    class UserService {

        public static $db = "ph";

        public static function getById($id){
            $q = \Core\DBM::get(Self::$db)->prepare("SELECT id FROM ph_users WHERE id = ? LIMIT 1");
            $q->execute([$id]);
            if($q->rowCount() < 1){ return false; }
            $id = $q->fetchObject()->id;
            return new User($id);
        }

        public static function existsId($id){
            if(Self::getById($id) == false){ return false; }
            return true; 
        }

        public static function getByName($name){
            $q = \Core\DBM::get(Self::$db)->prepare("SELECT id FROM ph_users WHERE username = ? LIMIT 1");
            $q->execute([$name]);
            if($q->rowCount() < 1){ return false; }
            $id = $q->fetchObject()->id;
            return new User($id);
        }

        public static function existsName($name){
            if(Self::getByName($name) == false){ return false; }
            return true; 
        }

        public static function getByEmail($email){
            $q = \Core\DBM::get(Self::$db)->prepare("SELECT id FROM ph_users WHERE email = ? LIMIT 1");
            $q->execute([$email]);
            if($q->rowCount() < 1){ return false; }
            $id = $q->fetchObject()->id;
            return new User($id);
        }

        public static function existsEmail($email){
            if(Self::getByEmail($email) == false){ return false; }
            return true; 
        }

        public static function count(){
            $q = \Core\DBM::get(Self::$db)->prepare("SELECT COUNT(id) AS amount FROM ph_users");
            if(!$q->execute()){ return -1; }
            $obj = $q->fetchObject();
            return intval($obj->amount);
        }

    }
?>