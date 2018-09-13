<?php

    namespace Core;

    class App {

        public $router = null;

        public function __construct(){
            $this->check4Update();
            $this->loadRoutes();
            \Helpers\Token::init();
        }

        private function loadRoutes(){
            require_once $_SERVER["DOCUMENT_ROOT"]."php/routes.php";
        }
        
        public function render(){
            return \Core\Router::run(explode("?",$_SERVER["REQUEST_URI"])[0]);
        }

        public function check4Update(){
            $file = $_SERVER["DOCUMENT_ROOT"]."php/config/version";
            if(!file_exists($file)){
                $this->createEnv();
                file_put_contents($file,\Core\Config::get("application_version"));
                return 0;
            }
            $server_version = file_get_contents($file);
            $app_version = \Core\Config::get("application_version");
            if($app_version != $server_version){
                $this->updateEnv();
                file_put_contents($file,\Core\Config::get("application_version"));
                return 1;
            } 
            return -1;
        }

        public function updateEnv(){
            $db = DBM::getMain();
            require_once $_SERVER["DOCUMENT_ROOT"]."php/config/db_update.php";
        }

        public function createEnv(){
            $db = DBM::getMain();
            require_once $_SERVER["DOCUMENT_ROOT"]."php/config/db_init.php";
        }
    
    }
?>