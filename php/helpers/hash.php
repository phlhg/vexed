<?php

    namespace Helpers;
    use \Core\Config;

    class Hash {

        public static function ph1($string){
            $salt = Config::get("hash_salt");
            $string = $salt.$string;
            $hashed = hash("sha256",$string);
            return $hashed;
        }

    }
?>