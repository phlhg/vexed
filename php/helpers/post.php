<?php

    namespace Helpers;

    Class Post {


        public static function exists($id){
            return isset($_POST[$id]);
        }

        public static function get($id){
            return $_POST[$id];
        }

    }

?>