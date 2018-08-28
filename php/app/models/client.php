<?php
    
    namespace App\Models;
    use User;

    class Client extends \App\Models\User {

        private static $instance = null;
        private static $loaded = false;

        public $loggedIn = false;
        public $isGuest = true;

        public function __construct(...$args){
            parent::__construct($args);
            $this->loginClient(); 
        }

        public static function get(){
            if(self::$loaded == false){
                self::$instance = new self(); 
                self::$loaded = true;
            }
            return self::$instance;
        }

        public function login(){
            if(!$this->loggedIn()){ $this->displayLogin(); return false; }
            return true;
        }

        public function loggedIn(){
            return false;
        }

        public function displayLogin(){
            \Core\Router::setRoute("login/index");
        }

        public function loginClient(){
            if(isset($_SESSION["ph_login_id"]) && isset($_SESSION["ph_login_pw"]) && isset($_SESSION["ph_login_time"])){
                $this->verifySession($_SESSION["ph_login_id"],$_SESSION["ph_login_pw"],$_SESSION["ph_login_time"]);
            } else if(isset($_POST["ph_login_username"]) && isset($_POST["ph_login_password"])){
                $this->initSession($_POST["ph_login_username"],$_POST["ph_login_password"]);
            }
        }

        public function initSession($username,$password){
            //unset($_POST["ph_login_username"]);
            //\Core\Router::setRoute("login/index",["Passwort oder Nutzername falsch",null,$username,$password]);
        }

        public function verifySession($id,$username,$time){

        }

        public function logout(){
            unset($_SESSION["ph_login_id"]);
            unset($_SESSION["ph_login_pw"]);
        }
    }
?>