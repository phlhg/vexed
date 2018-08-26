<?php

    namespace Core;

    class Model {

        protected static $_loaded = [];
        protected $db = null;

        public function __construct($db){
            $this->db = $db;
        }

        public static function get($name,$params = []){
            Self::load($name);
            return new $name(...$params);
        }

        public static function load($name){
            if(!in_array($name,self::$_loaded)){
                $file = $_SERVER["DOCUMENT_ROOT"].'app/models/'.strtolower(filter_var($name, FILTER_SANITIZE_URL)).'.model.php';
                if(!is_readable($file)){ throw new PHException("Model \"".$name."\" not found"); }
                require_once $file;
                self::$_loaded[] = $name;
            }
        }

    }