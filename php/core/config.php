<?php

    namespace Core;

    class Config {

        private static $settings = null;
        private static $loaded = false;

        public static function loadIfNeeded(){
            if(!self::$loaded){
                require $_SERVER["DOCUMENT_ROOT"]."php/config.php";
                self::$settings = $_CONFIG;
                self::$loaded = true;
            }
        }

        public static function get($property){
            self::loadIfNeeded();
            if(!self::exists($property)){ return false; }
            return self::$settings[$property];
        }

        public static function set($property,$value){
            if(!self::exists($property)){ return false; }
            self::$settings[$property] = $value;
            return true;
        }

        public static function exists($property){
            return isset(self::$settings[$property]);
        }
    }

?>