<?php

    namespace Helpers;

    class Token {

        private static $token = "";
        private static $session = "token";

        public static function init(){
            if(!Self::exists()) Self::create();
            Self::load();
        }

        private static function create(){
            Self::$token = hash("sha256",uniqid("as_",true));
            $_SESSION[Self::$session] = Self::$token;
        }

        private static function load(){
            Self::$token = $_SESSION[Self::$session];
        }

        private static function exists(){
            return isset($_SESSION[Self::$session]);
        }

        public static function get(){
            return Self::$token;
        }

        public static function check(String $reference){
            if($reference == Self::$token) return true;
            return false;
        }

    }

?>