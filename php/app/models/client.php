<?php
    
    namespace App\Models;
    use User;

    class Client extends \App\Models\User {

        private static $instance = null;
        private static $loaded = false;

        public $loggedIn = false;

        public $login_error = "";
        public $login_info = "";

        public $login_username = "";
        public $login_password = "";
        public $login_remember = false;

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
            if(!$this->loggedIn() ){ $this->displayLogin(); return false; }
            return true;
        }

        public function loggedIn(){
            return false;
        }

        public function displayLogin(){
            
            \Core\Router::setRoute("login/index",[$this->login_error,$this->login_info,$this->login_username,$this->login_password,$this->login_remember]);
        }

        public function loginClient(){
            if(isset($_SESSION["ph_login_id"]) && isset($_SESSION["ph_login_pw"]) && isset($_SESSION["ph_login_time"])){
                $this->verifySession($_SESSION["ph_login_id"],$_SESSION["ph_login_pw"],$_SESSION["ph_login_time"]);
            } else if(isset($_POST["ph_login_username"]) && isset($_POST["ph_login_password"]) && isset($_POST["ph_login_preserve"])){
                $this->initSession($_POST["ph_login_username"],$_POST["ph_login_password"],$_POST["ph_login_preserve"]);
            }
        }

        public function initSession($username,$password,$remember){
            $this->login_error = "Passwort oder Nutzername falsch";
            $this->login_username = $username;
            $this->login_password = $password;
            $this->login_remember = ($remember == "on" ? true : false);
        }

        public function verifySession($id,$username,$time){

        }

        public function logout(){
            unset($_SESSION["ph_login_id"]);
            unset($_SESSION["ph_login_pw"]);
        }
    }
?>