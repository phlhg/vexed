<?php

    namespace Helpers;

    Class File {
        
        public static function exists($id){
            return isset($_FILES[$id]) && $_FILES[$id]["error"] != 4;
        }

        public static function error($id){
            if(!Self::exists($id)){ return 4; }
            return Self::exists($id)["error"];
        }

        public static function get($id){
            return $_FILES[$id];
        }

    }

?>