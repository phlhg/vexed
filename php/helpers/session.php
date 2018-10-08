<?php

    namespace Helpers;

    Class Session {

        private static $prefix = "ph_";

        public static function exists($id){
            return isset($_SESSION[Self::$prefix.$id]);
        }

        public static function get($id){
            return $_SESSION[Self::$prefix.$id];
        }

        public static function set($id,$value){
            $_SESSION[Self::$prefix.$id] = $value;
        }

        public static function delete($id){
            unset($_SESSION[Self::$prefix.$id]);
        }

    }

?>