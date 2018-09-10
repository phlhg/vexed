<?php

    namespace App\Models;
    use \Core\Config;

    class User extends \Core\Model {

        public $id = -1;
        public $name = "";

        public function __construct($id){
            parent::__construct();
            $this->id = $id;
        }

        public function confirm($seccode){
            $q = \Core\DBM::getMain()->prepare("UPDATE ph_users SET confirmed = '1', security = '' WHERE id = ? AND security = ? LIMIT 1");
            $q->execute(array($this->id,\Helpers\Hash::ph1($seccode)));
            if($q->rowCount() > 0){ return true; }
            return false;
        }
    }
?>