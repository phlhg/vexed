<?php

    namespace App\Models\Account;
    use User;
    use Auth;

    class Client extends \App\Models\Account\User {

        private static $instance = null;
        private static $loaded = false;

        public $auth = null;

        public function __construct(){
            $this->authservice = new \App\Models\Auth\AuthService();
            if($this->authservice->identified){
                $this->id = $this->authservice->id;
                //WORKAROUND: To have access to the id of the client from within the constructor of the user class.
                \App::$client = (object)["id" => $this->id];
                parent::__construct($this->id);
            }
        }

        public function authenticate($preferences = []){
            if(!$this->authservice->identified){
                return $this->authservice->login->display();
            }
            return true;
        }

        public function isLoggedIn(){
            return $this->authservice->identified;
        }

        public function logout(){
            $this->authservice->logout();
        }
    }
?>