<?php

    namespace Helpers;
    use \Core\Config;

    class Password {

        public static function hash_ph1($string){
            return hash("sha256",$string);
        }

        public static function hash($string){
            $salt = Config::get("hash_salt");
            $string = $salt.$string;
            return password_hash($string,PASSWORD_DEFAULT);
        }

        public static function check($string,$hash){
            $salt = Config::get("hash_salt");
            $string = $salt.$string;
            return password_verify($string, $hash);
        }
    }
?>