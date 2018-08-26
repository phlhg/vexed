<?php

    namespace Core;

    class View {

        private $view = "";

        public $v = array();
        public $meta = null;

        public function __construct($name){
            $this->view = $name;
            $this->meta = new Repository();
                $this->meta->title = "";
                $this->meta->title_appendix = Config::get("default_meta_title_appendix");
                $this->meta->description = "...";
                $this->meta->keywords = Config::get("default_meta_keywords");
                $this->meta->menu = Config::get("default_meta_menu");
                $this->meta->image = Config::get("default_meta_image");
                $this->meta->themecolor = Config::get("default_meta_themecolor");
            $this->v = new Repository();
            return $this;
        }

        public function getTitle(){
            return $this->meta->title.$this->meta->title_appendix;
        }

        public function getKeywords(){
            return implode(" ,",$this->meta->keywords);
        }

        public function addKeywords($keyword){
            array_pop($this->meta->keywords);
            array_unshift($this->meta->keywords,$keyword);
            return $this;
        }

        public function render(){
            ob_start();
                $this->get("page/header");
                $this->get($this->view);
                $this->get("page/footer");
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }

        public function exists($name){
            $file = $_SERVER["DOCUMENT_ROOT"]."php/app/views/".strtolower($name).".php";
            return is_readable($file);
        }

        public function get($name){
            $file = $_SERVER["DOCUMENT_ROOT"]."php/app/views/".strtolower($name).".php";
            if(!is_readable($file)){ throw new Error('View-Datei "/php/app/views/'.strtolower($name).'.php" could not be found'); }
            $this->load($file);
        }

        private function load($__file){
            $view = $this;
            require_once $__file;
        }

    }

    class Repository {

        public function __get($property){
            if(!isset($this->$property)){ return '['.$property.']'; }
            return $this->$property;
        }

        public function __set($property,$value){
            $this->$property = $value;
            return $this;
        }

    }
?>