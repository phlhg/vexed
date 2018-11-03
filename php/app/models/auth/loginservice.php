<?php

    namespace App\Models\Auth;

    class LoginService {

        private $username = "";
        private $password = "";
        private $preserve = false;

        public $error = "";
        public $info = "";

        private $displayed = false;

        public function set($username, $password, $preserve){
            $this->username = $username;
            $this->password = $password;
            $this->preserve = $preserve;
        }

        public function error($error){
            $this->error = $error;
            return false;
        }

        public function info($info){
            $this->info = $info;
            return false;
        }

        public function display(){
            if(!$this->displayed){
                $this->displayed = true;
                \App::$router->setRoute("login/index",[$this->error,$this->info,$this->username,$this->password,$this->preserve]);
            }
            return false;
        }

    }

?>