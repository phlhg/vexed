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

        public static function create($name,$password,$email){
            if(\App\Models\UserService::existsName($name) OR \App\Models\UserService::existsName($email)){ return false; }
            if(!\Helpers\Pattern::username($name) OR !\Helpers\Pattern::email($email)){ return false; }
            $password_hashed = \Helpers\Hash::ph1($password);
            $admin = (\App\Models\UserService::count() == 0 ? 1 : 0);
            $created = time();
            $conditions = \Core\Config::get("conditions_version_last");
            $security = generateSecurityCode(12);
            $security_hashed = \Helpers\Hash::ph1($security);
            $q = \Core\DBM::getMain()->prepare("INSERT INTO ph_users (username, password, email, admin, security, created, conditions) VALUES (?,?,?,?,?,?,?);");
            if(!$q->execute([$name,$password_hashed,$email,$admin,$security_hashed,$created,$conditions])){ return false; }
            // MAX() by https://stackoverflow.com/questions/3422168/safest-way-to-get-last-record-id-from-a-table
            $q = \Core\DBM::getMain()->prepare("SELECT MAX(id)AS max_id FROM ph_users");
            $q->execute();
            $id = intval($q->fetchObject()->max_id);
            //SEND MAIL
            $email = new \App\Models\Email($email,"Dein Account wurde erstellt");
            $email->add('<h2>Account erstellt</h2>
            <p>
                Hallo <strong>'.strtolower($name).'</strong><br/>
                <br/>
                Um deine Registrierung abzuschliessen, benutze bitte den folgenden Aktivierungslink<br/>
                <br/>
                <strong style="font-size: 25px;"><a href="http://antisocial.vs/signup/confirm/'.$id.'/'.$security.'/">antisocial.vs/signup/confirm/'.$id.'/'.$security.'/</a></strong><br/>
                <br/>
                <br/>
                <h3>Keinen Account erstellt?</h3>
                Wenn du diesen Account nicht erstellt hast, kannst du dieses Mail ignorieren. Unbestätigte Accounts werden in der Regel nach 7 Tagen gelöscht.
            </p>');
            if(!$email->send()){ return false; };
            return true;
        }

    }
?>