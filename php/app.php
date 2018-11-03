<?php

    class App {

        public static $router = null;
        public static $client = null;

        public function __construct(){

            \Helpers\Token::init();

            Self::$client = new \App\Models\Account\Client();
            Self::$router = new \Core\Router();

            $this->loadRoutes();
        }

        private function loadRoutes(){
            require_once $_SERVER["DOCUMENT_ROOT"]."/php/routes.php";
        }

        public function run(){
            echo Self::$router->run(explode("?",$_SERVER["REQUEST_URI"])[0]);
        }
    
    }
?>