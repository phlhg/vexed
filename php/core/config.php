<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo 2018
     */

    namespace Core;

    /**
     * Returns properties of the configuration, located in '/php/config.php'.
     */
    class Config {

        /** Contains the array of properties loaded from the configuration
         * @var Mixed[] $settings */
        private static $settings = null;

        /** Indicates if the configuration was loaded already 
         * @var Boolean $loaded */
        private static $loaded = false;

        /** 
         * Loads the configuration if it wasn't loaded yet
         * 
         * @return Boolean Returns true if the configuration was loaded.
         */
        public static function load(){
            if(self::$loaded){ return false; }
                require $_SERVER["DOCUMENT_ROOT"]."php/config.php";
                self::$settings = $_CONFIG;
                self::$loaded = true;
                return true;
        }

        /**
         * Returns the value of a property.
         * 
         * @param String $property Name of the property
         * 
         * @return Mixed Returns the value of the property.
         */
        public static function get(String $property){
            self::load();
            if(!self::exists($property)){ return false; }
            return self::$settings[$property];
        }

        /**
         * Temporary overrides the value of a porperty.
         * 
         * @param String $property Name of the property
         * @param Mixed $value New value of the property
         * 
         * @return Boolean Returns true if the property was overridden. Returns false if it doesn't exist.
         */
        public static function set(String $property, Mixed $value){
            if(!self::exists($property)){ return false; }
            self::$settings[$property] = $value;
            return true;
        }

        /**
         * Checks if a property exists.
         * 
         * @param String $property Name of the property
         * 
         * @return Boolean Returns true if the property exists, otherwise returns false.
         */
        public static function exists(String $property){
            return isset(self::$settings[$property]);
        }
    }

?>