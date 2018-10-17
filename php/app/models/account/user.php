<?php

    namespace App\Models\Account;
    use \Core\Config;

    class User {

        protected $userservice;

        public $id = -1;
        public $name = "guest";
        public $displayName = "Gast";
        protected $email = "";
        public $admin = "";
        protected $verified = false;
        protected $private = false;
        protected $banned = false;
        public $description = "";
        public $website = "";
        public $followers = 0;
        public $following = 0;
        public $posts = 0;
        public $created = 0;
        public $conditions = 0;

        public function __construct($id){
            $this->userservice = new \App\Models\Storage\Sql\UserService();
            $this->id = $id;
            $this->load();
        }

        public function confirm($seccode){
            $q = \Core\DBM::getMain()->prepare("UPDATE ph_users SET confirmed = '1', security = '' WHERE id = ? AND security = ? LIMIT 1");
            $q->execute(array($this->id,\Helpers\Hash::ph1($seccode)));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        public function load(){
            $info = $this->userservice->get($this->id);
            if($info == false){ return false; }
            $this->name = $info["username"];
            $this->displayName = $this->name;
            $this->email = $info["email"];
            $this->admin = ($info["admin"] == "1" ? true : false);
            $this->verified = ($info["verified"] == "1" ? true : false);
            $this->private = ($info["private"] == "1" ? true : false);
            $this->banned = intval($info["banned"]);
            $this->description = $info["description"];
            $this->website = $info["website"];
            $this->created = intval($info["created"]);
            $this->conditions = intval($info["conditions"]);
        }

        public static function getByName($name){
            $userService = new \App\Models\Storage\Sql\UserService();
            return new Self($userService->getIdByName($name));
        }

        public static function hasEmail($email){
            $userService = new \App\Models\Storage\Sql\UserService();
            return $userService->existsEmail($email);
        }
    }
?>