<?php

    namespace Helpers;

    Class Cookie {

        private static $prefix = "ph_";

        public static function exists($id){
            return isset($_COOKIE[Self::$prefix.$id]);
        }

        public static function get($id){
            return $_COOKIE[Self::$prefix.$id];
        }

        public static function set($id,$value,$expire=24,$path="/"){
            setCookie(Self::$prefix.$id,$value,time()+3600*$expire,$path);
        }

    }

?>