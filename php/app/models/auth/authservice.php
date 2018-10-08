<?php

    namespace App\Models\Auth;
    use \Helpers\Session;
    use \Helpers\Post;

    class AuthService extends \Core\Model {

        public $login = null;
        public $userService = null;

        public $id = -1;
        public $identified = false;

        public function __construct(){
            Parent::__construct();
            $this->login = new \App\Models\Auth\LoginService();
            $this->userService = new \App\Models\Storage\Sql\UserService();
            $this->identified = $this->identify();
        }

        private function identify(){
            if(\Helpers\Post::exists("ph_login_username") && \Helpers\Post::exists("ph_login_password") && \Helpers\Post::exists("ph_login_tkn")){
                return $this->login(\Helpers\Post::get("ph_login_tkn"), \Helpers\Post::get("ph_login_username"), \Helpers\Post::get("ph_login_password"), \Helpers\Post::exists("ph_login_preserve"));
            } else if(\Helpers\Session::exists("login_id") && \Helpers\Session::exists("login_pw") && \Helpers\Session::exists("login_expires")){
                return $this->verifySession(\Helpers\Session::get("login_id"),\Helpers\Session::get("login_pw"),\Helpers\Session::get("login_expires"));
            }
        }

        public function login($token, $username, $password, $preserve){
            $this->login->set($username,$password,$preserve);
            if(!\Helpers\Token::check($token)){ return $this->login->info("Ungültiger Token - Anfrage abgelehnt"); }
            if(\Helpers\Check::email($username)){ $id = $this->userService->getIdByEmail($username); } else { $id = $this->userService->getIdByName($username); }
            if($id < 0){ return $this->login->error("Passwort oder Nutzername falsch"); }
            if(!$this->userService->checkCredentials($id,$password)){ return $this->login->error("Passwort oder Nutzername falsch"); }
            if($this->userService->isBanned($id)){ return $this->login->error("Dein Account wurde gesperrt"); }
            if(!$this->userService->isConfirmed($id)){ return $this->login->info("Bitte bestätige zuerst deinen Account"); }
            $this->initSession($id,$password);
            return true;
        }

        private function initSession($id,$password){
            \Helpers\Session::set("login_id",$id);
            \Helpers\Session::set("login_pw",\Helpers\Hash::ph1($password));
            \Helpers\Session::set("login_expires",time()+3600);
            $this->id = $id;
        }

        private function verifySession($id, $password, $expires){
            if($expires < time()){ return $this->login->info("Bitte melde dich erneut an"); }
            \Helpers\Session::set("login_expires",time()+3600);
            $this->id = $id;
            return true;
        }

        public function logout(){
            \Helpers\Session::delete("login_id");
            \Helpers\Session::delete("login_pw");
            \Helpers\Session::delete("login_expires");
        }

    }

?>