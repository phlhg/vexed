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
            // by https://stackoverflow.com/questions/9504608/request-string-without-get-arguments-in-php/9504722
            echo Self::$router->run(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
        }
    
    }
?>