<?php

    namespace Helpers;
    use \Core\Config;

    class Password {

        public static function hash_ph1($string){
            return hash("sha256",$string);
        }

        public static function hash($string){
            return password_hash(Self::hashfactory($string),PASSWORD_DEFAULT);
        }

        private static function hashfactory($string){
            $salt = Config::get("hash_salt");
            return $salt.$string;
        }

        public static function check($string,$hash){
            return password_verify(Self::hashfactory($string), $hash);
        }
    }
?>