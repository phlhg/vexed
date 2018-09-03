<?php

    namespace App\Models;

    class Auth {

        public $id = -1;
        public $isGuest = true;

        public $login = array(
            "displayed" => false,
            "error" => "",
            "info" => "",
            "username" => "",
            "password" => "",
            "remember" => false
        );

        public function __construct(){
            $this->login = (object) $this->login;
        }

        public function displayLogin(){
            if(!$this->login->displayed){
                $this->login->displayed = true;
                \Core\Router::setRoute("login/index",[$this->login->error,$this->login->info,$this->login->username,$this->login->password,$this->login->remember]);
            }
        }

        public function login(){
            if(isset($_POST["ph_login_username"]) && isset($_POST["ph_login_password"]) && isset($_POST["ph_login_tkn"])){
                return $this->initSession($_POST["ph_login_tkn"],$_POST["ph_login_username"],$_POST["ph_login_password"],isset($_POST["ph_login_preserve"]));
            } else if(isset($_SESSION["ph_login_id"]) && isset($_SESSION["ph_login_pw"]) && isset($_SESSION["ph_login_time"])){
                return $this->verifySession($_SESSION["ph_login_id"],$_SESSION["ph_login_pw"],$_SESSION["ph_login_time"]);
            } else {
                return $this->initGuest();
            }
        }

        public function initGuest(){
            $_SESSION["ph_login_id"] = -1;
            $_SESSION["ph_login_pw"] = "";
            $_SESSION["ph_login_time"] = time();
            $this->loggedIn = true;
            $this->isGuest = true;
        }

        public function initSession($token,$username,$password,$remember = false){
            $this->login->username = $username;
            $this->login->password = $password;
            $this->login->remember = $remember;
            //Check Token
            if(!\Helpers\Token::check($token)){ $this->login->info = "Ungültiger Token - Anfrage abgelehnt"; return; }
            //Check User
            $this->login->error = "Passwort oder Nutzername falsch"; return;
            $this->displayLogin();
        }

        public function verifySession($id,$username,$time){
            if($id < 0){ $this->isGuest = true; }
        }

        public function logout(){
            unset($_SESSION["ph_login_id"]);
            unset($_SESSION["ph_login_pw"]);
            unset($_SESSION["ph_login_time"]);
        }

    }