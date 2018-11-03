<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace Core;

    /**
     * Manages the output rendering.
     */
    class View {

        /** Holds the name of the view
         * @var String $view */
        private $view = "";

        /** Holds the name of the template
         * @var String $template */
        public $template = "html";

        /** Holds the type of format
         * @var String $format */
        private $format = "text/html";

        /** List of additional scripts to load 
         * @var String[] $scripts */
        private $scripts = [];

        /** List of additional stylesheets to load
         * @var String[] $styles */
        private $styles = [];

        /** Holds the variable repository
         * @var Repository $v */
        public $v = null;
        
        /** Holds the meta repository with additional info about the page
         * @var Repository $v */
        public $meta = null;

        /** Holds information about the client 
         * @var \App\Model\Client $client */
        public $client = null;

        /** Stores the output after the rendering.
         * @var String $cache */
        private $cache = "";

        /** 
         * Create a new View
         * @param String $name Name of the view
         */
        public function __construct($name){
            $this->view = $name;
            $this->client = \App::$client;
            $this->meta = new Repository();
                $this->meta->title = "";
                $this->meta->title_appendix = Config::get("default_meta_title_appendix");
                $this->meta->description = "...";
                $this->meta->keywords = Config::get("default_meta_keywords");
                $this->meta->menu = Config::get("default_meta_menu");
                $this->meta->image = Config::get("default_meta_image");
            $this->v = new Repository();
            return $this;
        }

        //PAGE

        /** 
         * Formats the title with an appendix
         * @return String Returns the page title with the title appendix
        */
        public function getTitle(){
            return $this->meta->title.$this->meta->title_appendix;
        }

        /**
         * Formats the list of the stored keywords
         * @return String Returns all stored keywords separated by a comma (" ,").
         */
        public function getKeywords(){
            return implode(" ,",$this->meta->keywords);
        }

        /**
         * Adds a keyword to the storage.
         * @param String $keyword A keyword for the page.
         * @return Self Returns itself for chaining
         */
        public function addKeywords($keyword){
            array_pop($this->meta->keywords);
            array_unshift($this->meta->keywords,$keyword);
            return $this;
        }

        //INTERN

        /**
         * Sets the template of the view.
         * @param String $template Name of the template
         */
        public function setTemplate($template){
            $this->template = $template;
        }

        /**
         * Sets the format of the view.
         * @param String $format A valid content-type (ex. 'text/html', 'image/png')
         */
        public function setFormat($format){
            $this->format = $format;
        }

        /**
         * Adds an additional script to the page.
         * @param String The url of the script to add.
         */
        public function addScript($url){
            $this->scripts[] = $url;
        }

        /**
         * Adds an additional stylesheet to the page.
         * @param String The url of the stylesheet to add.
         */
        public function addStyle($url){
            $this->styles[] = $url;
        }

        /**
         * Renders the page out of the view.
         */
        public function render(){
            $this->cache = "";
            require $_SERVER["DOCUMENT_ROOT"]."/php/app/templates/".strtolower($this->template).".php";
        }

        /**
         * Sets the format of the page and returns the rendered page.
         * @return String Returns the rendered page.
         */
        public function getRender(){
            header("Content-Type: ".$this->format);
            return $this->cache;
        }

        /**
         * Retrieves the location of a view-file in the file-system.
         * @param String $name Name of the view
         * @return String Returns the Location of the view-file
         */
        private function getLocation($name){
           return $_SERVER["DOCUMENT_ROOT"]."/php/app/views/".strtolower($name).".php";
        }

        /**
         * Load a view-file internal and append it to the render.
         * @param String $name Name of the view
         */
        private function getView($name){
            if(!is_readable($this->getLocation($name))){ throw new \Core\Error('View-Datei "'.strtolower($name).'.php" could not be found'); }
            $this->cache .= $this->load($this->getLocation($name));
        }

        /** Load a view-file external
         * @param String $name Name of the view
         */
        public function get($name){
            if(!is_readable($this->getLocation($name))){ throw new \Core\Error('View-Datei "'.strtolower($name).'.php" could not be found'); }
            $view = $this;
            $_var = $this->v;
            $_meta = $this->meta;
            require $this->getLocation($name);
        }

        /** Capture the output of a view-file.
         * @param String $__file Location of the view-file
         * @return String Returns the output of the file.
        */
        private function load($__file){
            $content = "";
            $c = ob_start();
                $view = $this;
                $_var = $this->v;
                $_meta = $this->meta;
                require $__file;
            $content = ob_get_contents();
            ob_end_clean();
            if(!$content){ echo "FEHLER"; }
            return $content;
        }

    }

    /**
     * Manages a list of elements.
     */
    class Repository {

        /**
         * Returns the search property if it exists otherwise returns "[property]".
         */
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