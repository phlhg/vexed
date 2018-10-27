<?php

    namespace Helpers;

    Class File {
        
        public static function exists($id){
            return (isset($_FILES[$id]) && $_FILES[$id]["error"] == 0);
        }

        public static function get($id){
            return $_FILES[$id];
        }

    }

?>