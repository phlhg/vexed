<?php

    namespace App\Models\Account;
    use \Core\Config;

    class User {

        protected $userservice;
        protected $relationservice;

        public $exists = false;

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
        public $verified = false;
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
            $this->exists = true;
            $this->name = htmlspecialchars($info["username"]);
            $this->displayName = htmlspecialchars($this->name);
            $this->email = $info["email"];
            $this->admin = ($info["admin"] == "1" ? true : false);
            $this->verified = ($info["verified"] == "1" ? true : false);
            $this->private = ($info["private"] == "1" ? true : false);
            $this->banned = intval($info["banned"]);
            $this->description = htmlspecialchars($info["description"]);
            $this->website = htmlspecialchars($info["website"]);
            $this->created = intval($info["created"]);
            $this->conditions = intval($info["conditions"]);

            $this->relation = $this->relationservice->getState($this->id);
            $this->followers = $this->relationservice->getFollowers($this->id);
            $this->subscriptions = $this->relationservice->getSubscriptions($this->id);
        }

        public function confirm($seccode){
            $q = \Core\DBM::getMain()->prepare("UPDATE ph_users SET confirmed = '1', security = '' WHERE id = ? AND security = ? LIMIT 1");
            $q->execute(array($this->id,\Helpers\Password::hash_ph1($seccode)));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /* RELATIONS */

        /**
         * Follows this user.
         * @return Boolean Returns True if the user is followed.
         */
        public function follow(){
            if($this->relation != \App\Models\Account\Relation::STRANGER){ return false; }
            if($this->private){
                $this->relationservice->create($this->id);
                if(!$this->relationservice->setState($this->id,\App\Models\Account\Relation::REQUESTED)){
                    $this->relation = \App\Models\Account\Relation::STRANGER;
                    return false;
                }
                $this->relation = \App\Models\Account\Relation::REQUESTED;
                return true;
            } else {
                $this->relationservice->create($this->id);
                if(!$this->relationservice->setState($this->id,\App\Models\Account\Relation::FOLLOWING)){
                    $this->relation = \App\Models\Account\Relation::STRANGER;
                    return false;
                }
                $this->relation = \App\Models\Account\Relation::FOLLOWING;
                return true;
            }
        }

        /* Auto-Login */
        public function setAutologCookie(){
            $security = $this->userservice->getSecurity();
            if($security == ""){ $security = $this->newAutolog(); }
            \Helpers\Cookie::set("ph_autolog",$this->id."//".$security);
        }

        public function newAutolog(){
            $security = "";
            $chars = "abcdefghijklmnopqrstuvwxyz1234567890";
            $l = strlen($chars);
            for($i = 0; $i < 30; $i++){
                $security .= $chars[rand(0,($l-1))];
            }
            if(!$this->userservice->setSecurity($security)){ return false; };
            return $security;
        }

        public function verifyAutologCookie($sec){
            $hash = $this->userservice->getSecurity();
            if($hash != "" && hash("sha256",$sec) == $security){ return true; }
            return false;
        }

        /**
         * Unfollows this user.
         * @return Boolean Returns True if the user was unfollowed.
         */
        public function unfollow(){
            if($this->relation != \App\Models\Account\Relation::FOLLOWING){ return false; }
            if(!$this->relationservice->delete($this->id)){
                $this->relation = \App\Models\Account\Relation::FOLLOWING;
                return false;
            }
            $this->relation = \App\Models\Account\Relation::STRANGER;
            return true;
        }

        /**
         * Accepts a relation request from this user.
         * @return Boolean Returns True if the request was accepted.
         */
        public function relationAccept(){
            return $this->relationservice->accept($this->id);
        }

        /**
         * Denies a relation request from this user.
         * @return Boolean Returns True if the request was denied.
         */
        public function relationDeny(){
            return $this->relationservice->deny($this->id);
        }

        /* HTML */

        public function toHtml($showrelation=true){
            $html = '<div class="ph_profile_banner_small">
                <a class="link" href="/p/'.$this->name.'/"></a>
                <div class="pb" style="background-image: url(/img/pb/'.$this->id.'/);"></div>
                <span class="name">'.$this->displayName.'</span>
                <span class="description">'.($this->verified ? '<span title="Verifizierter Nutzer" class="ph_inline_icon small"><i class="material-icons">check</i></span> ':'').preg_replace('/\n/i',' ',$this->description).'</span>
                <div class="ph_fs_button" data-rel="'.$this->relation.'" data-id="'.$this->id.'">
                    <span class="edit"><span class="txt">Bearbeiten </span><i class="material-icons">create</i></span>
                    <span class="follow"><span class="txt">Folgen </span><i class="material-icons">add</i></span>
                    <span class="requested"><span class="txt">Angefragt </span><i class="material-icons">send</i></span>
                    <span class="following"><span class="txt">Abonniert </span><i class="material-icons">done</i></span>
                </div>
            </div>';
            return $html;
        }

        /* STATIC */

        public static function getByName($name){
            $userService = new \App\Models\Storage\Sql\UserService();
            return new Self($userService->getIdByName($name));
        }

        public static function getByEmail($email){
            $userService = new \App\Models\Storage\Sql\UserService();
            return new Self($userService->getIdByEmail($email));
        }

        public static function hasEmail($email){
            $userService = new \App\Models\Storage\Sql\UserService();
            return $userService->existsEmail($email);
        }
    }
?>