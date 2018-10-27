<?php

    namespace Helpers;

    Class Get {

        /**
         * Checks if an GET element exists.
         * @param String $id Identifier of the element.
         * @return Boolean Returns True if the elemant exists.
         */
        public static function exists($id){
            return isset($_GET[$id]);
        }

        /**
         * Checks if multiple GET elements exist.
         * @param String[] $array Array of identifiers to check.
         * @return Boolean Returns True if all elements exist.
         */
        public static function exist($array){
            $exist = true;
            foreach($array as $id){
                if(!Self::exists($id)){ $exist = false; }
            }
            return $exist;
        }

        /**
         * Returns a GET element.
         * @param String $id Identifier of the element.
         * @return Mixed Returns the content of the element.
         */
        public static function get($id){
            return $_GET[$id];
        }

    }

?>