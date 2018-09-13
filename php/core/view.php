<?php

    namespace Core;

    class View {

        private $view = "";
        public $cache = "";

        public $v = array();
        public $meta = null;
        public $client = null;
        public $menu = "core/html/menu/default";
        public $template = "";
        public $format = "text/html";

        public $scripts = [];
        public $styles = [];

        public function __construct($name,$template="html"){
            $this->view = $name;
            $this->template = $template;
            $this->client = \App\Models\Client::get();
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

        public function addScript($url){
            $this->scripts[] = $url;
        }

        public function addStyle($url){
            $this->styles[] = $url;
        }

        public function setFormat($format){
            $this->format = $format;
        }

        public function render(){
            $this->clearCache();
            require $_SERVER["DOCUMENT_ROOT"]."php/app/templates/".strtolower($this->template).".php";
        }

        public function getRender(){
            header("Content-Type: ".$this->format);
            return $this->cache;
        }

        public function clearCache(){
            $this->cache = "";
        }

        public function exists($name){
            $file = $_SERVER["DOCUMENT_ROOT"]."php/app/views/".strtolower($name).".php";
            return is_readable($file);
        }

        public function getView($name){
            $file = $_SERVER["DOCUMENT_ROOT"]."php/app/views/".strtolower($name).".php";
            if(!is_readable($file)){ throw new \Core\Error('View-Datei "/php/app/views/'.strtolower($name).'.php" could not be found'); }
            $this->cache .= $this->load($file);
        }

        public function get($name){
            $file = $_SERVER["DOCUMENT_ROOT"]."php/app/views/".strtolower($name).".php";
            if(!is_readable($file)){ throw new \Core\Error('View-Datei "/php/app/views/'.strtolower($name).'.php" could not be found'); }
            $view = $this;
            require $file;
        }

        private function load($__file){
            $content = "";
            $c = ob_start();
                $view = $this;
                require $__file;
            $content = ob_get_contents();
            ob_end_clean();
            if(!$content){ echo "FEHLER"; }
            return $content;
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