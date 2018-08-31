<?php

    namespace App\Models;
    use User;
    use Auth;

    class Client extends \App\Models\User {

        private static $instance = null;
        private static $loaded = false;

        public $auth = null;

        public function __construct(...$args){
            parent::__construct($args);
            $this->auth = new \App\Models\Auth();
            $this->auth->login(); 
        }

        public static function get(){
            if(self::$loaded == false){
                self::$loaded = true;
                self::$instance = new self(); 
            }
            return self::$instance;
        }

        public function authenticate(){
            if($this->auth->isGuest){
                $this->auth->displayLogin();
                return false;
            }
            return true;
        }

        public function isLoggedIn(){
            return !$this->auth->isGuest;
        }
    }
?>