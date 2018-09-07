<?php

    namespace Helpers;

    class Pattern {

        public static function username($name){
            if(preg_match('/[A-Za-z0-9\._\-$]{3,25}/i',$name)){ return true; }
            return false;
        }

        public static function email($email){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){ return true; }
            return false;
        }

    }

?>