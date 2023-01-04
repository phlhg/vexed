<?php

    namespace Helpers;

    class Check {

        public static function username($name){
            if(preg_match('/^[A-Za-z0-9\._\-$]{3,25}$/i',$name) === 1){ return true; }
            return false;
        }

        public static function email($email){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){ return true; }
            return false;
        }

        public static function emailProvider($email){
            $ex = explode("@",$email);
            if(count($ex) < 2){ return false; }
            return checkdnsrr($ex[1]);
        }

        public static function isAjax(){
            if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) OR $_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest"){
                return false;
            }
            return true;
        }

    }

?>