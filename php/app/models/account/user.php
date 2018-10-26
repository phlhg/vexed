<?php

    namespace App\Models\Account;
    use \Core\Config;

    class User {

        protected $userservice;
        protected $relationservice;

        public $id = -1;
        public $name = "guest";
        public $displayName = "Gast";
        public $description = "";
        public $website = "";
        public $posts = 0;

        public $relation = \App\Models\Account\Relation::STRANGER;
        public $followers = 0;
        public $subscriptions = 0;

        protected $email = "";
        public $admin = "";
        protected $verified = false;
        protected $private = false;
        protected $banned = false;
        public $created = 0;
        public $conditions = 0;

        public function __construct($id){
            $this->userservice = new \App\Models\Storage\Sql\UserService();
            $this->relationservice = new \App\Models\Storage\Sql\RelationService();
            $this->id = $id;
            $this->load();
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

            $this->relation = $this->relationservice->getState($this->id);
            $this->followers = $this->relationservice->getFollowers($this->id);
            $this->subscriptions = $this->relationservice->getSubscriptions($this->id);
        }

        public function confirm($seccode){
            $q = \Core\DBM::getMain()->prepare("UPDATE ph_users SET confirmed = '1', security = '' WHERE id = ? AND security = ? LIMIT 1");
            $q->execute(array($this->id,\Helpers\Hash::ph1($seccode)));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /* RELATIONS */

        /**
         * Follows this user.
         */
        public function follow(){
            if($this->relation != \App\Models\Account\Relation::STRANGER){ return false; }
            if($this->private){
                $this->relationservice->create($this->id);
                return $this->relationservice->setState($this->id,\App\Models\Account\Relation::REQUESTED);
            } else {
                $this->relationservice->create($this->id);
                return $this->relationservice->setState($this->id,\App\Models\Account\Relation::FOLLOWING);
            }
        }

        /**
         * Unfollows this user.
         */
        public function unfollow(){
            if($this->relation != \App\Models\Account\Relation::FOLLOWING){ return false; }
            return $this->relationservice->delete($this->id);
        }

        /**
         * Accepts a relation request from this user.
         */
        public function accept(){
            return $this->relationservice->accept($this->id);
        }

        /* STATIC */

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