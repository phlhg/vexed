<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Storage\Sql;
    use \Helpers\Password;

    /**
     * Manages access to to the users table.
     */
    class UserService extends \Core\Model {
        
        /**
         * Returns non security related information about the user.
         * @param Int $id The id of the user.
         * @return Mixed[]|Boolean Returns an Array with information about the user if the user was found otherwise False.
         */
        public function get($id){
            $q = $this->db->prepare("SELECT id, username, email, admin, verified, private, banned, description, website, created, conditions FROM ph_users WHERE id = ? LIMIT 1");
            $q->execute(array($id));
            if($q->rowCount() < 1){ return false; }
            return $q->fetch();
        }

        /** 
         * Returns wether the user exists or not.
         * @param Int $id The id of the user.
         * @return Boolean Returns True if the user exists otherwise False.
        */
        public function exists($id){
            $q = $this->db->prepare("SELECT id FROM ph_users WHERE id = ? LIMIT 1");
            $q->execute(array($id));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /** 
         * Returns wether the email is already used or not.
         * @param String $email The email of the user.
         * @return Boolean Returns True if the email is already used otherwise False.
        */
        public function existsEmail($email){
            $q = $this->db->prepare("SELECT id FROM ph_users WHERE email = ? LIMIT 1");
            $q->execute(array($email));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /** 
         * Returns wether the name exists or not.
         * @param String $name The name of the user.
         * @return Boolean Returns True if the name already exists otherwise False.
        */
        public function existsName($name){
            $q = $this->db->prepare("SELECT id FROM ph_users WHERE username = ? LIMIT 1");
            $q->execute(array($name));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /**
         * Creates a new entry in the database.
         * @param String $name The name of the user.
         * @param String $password The hashed(!) password of the user.
         * @param String $email The email to get in touch with the user.
         * @param String $security The unhashed(!) security code.
         * @return Int Returns the id of the new user on success otherwiese returns -1.
         */
        public function create($name, $password, $email, $security){
            $created = time();
            $conditions = \Core\Config::get("conditions_version_last");
            $security = \Helpers\Password::hash_ph1($security);
            $q = \Core\DBM::getMain()->prepare("INSERT INTO ph_users (username, password, email, security, created, conditions) VALUES (?,?,?,?,?,?);");
            if(!$q->execute([$name,$password,$email,$security,$created,$conditions])){ return -1; }
            // MAX() by https://stackoverflow.com/questions/3422168/safest-way-to-get-last-record-id-from-a-table
            $q = \Core\DBM::getMain()->prepare("SELECT MAX(id) AS max_id FROM ph_users");
            if(!$q->execute()){ return -1; };
            return intval($q->fetchObject()->max_id);
        }

        /**
         * Deletes the entry of a User in the database.
         * @param Int $id Id of the user to delete.
         * @return Boolean Retuns True if the user was deleted otherwise False.
         */
        public function delete($id){
            $q = $this->db->prepare("DELETE FROM ph_users WHERE id = ? LIMIT 1");
            $q->execute(array($id));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /* UPDATE */

        /**
         * Sets a new username for the client.
         * @param String The new username.
         * @return Boolean Returns True if the name was updated else false.
         */
        public function updateName($name){
            $q = \Core\DBM::getMain()->prepare("UPDATE ph_users SET username = ? WHERE id = ? LIMIT 1");
            if(!$q->execute(array($name,__CLIENT()->id))){ return false; }
            if($q->rowCount() < 1){ return false; }
            return True;
        }

        /**
         * Sets a new description for the client.
         * @param String The new description.
         * @return Boolean Returns True if the description was updated else false.
         */
        public function updateDescription($description){
            $q = \Core\DBM::getMain()->prepare("UPDATE ph_users SET description = ? WHERE id = ? LIMIT 1");
            if(!$q->execute(array($description,__CLIENT()->id))){ return false; }
            if($q->rowCount() < 1){ return false; }
            return True;
        }

        /**
         * Sets a new description for the client.
         * @param String The new description.
         * @return Boolean Returns True if the description was updated else false.
         */
        public function updateWebsite($website){
            $q = \Core\DBM::getMain()->prepare("UPDATE ph_users SET website = ? WHERE id = ? LIMIT 1");
            if(!$q->execute(array($website,__CLIENT()->id))){ return false; }
            if($q->rowCount() < 1){ return false; }
            return True;
        }

        /**
         * Confirms an account if the code is correct.
         */
        public function confirm($id,$code){
            $code = \Helpers\Password::hash_ph1($password);
            $q = \Core\DBM::getMain()->prepare("UPDATE ph_users SET confirmed = '1', security = '' WHERE id = ? AND security = ? LIMIT 1");
            $q->execute(array($id,$code));
            if($q->rowCount() < 1){ return false; }
            return true;
        }

        /**
         * Checks if the credentials match.
         * @param Int $id The id of the user.
         * @param String $password The unhashed(!) password.
         * @return Boolean Returns True if the credentials are correct otherwise False.
         */
        public function checkCredentials($id,$password){
            $q = $this->db->prepare("SELECT password FROM ph_users WHERE id = ? LIMIT 1");
            $q->execute(array($id));
            if($q->rowCount() < 1){ return false; }
            $hash = $q->fetchObject()->password;
            return \Helpers\Password::check($password,$hash);
        }

        /**
         * Checks if the the user is banned.
         * @param Int $id The id of the user.
         * @return Bool Returns true if the user is banned.
         */
        public function isBanned($id){
            $q = $this->db->prepare("SELECT id FROM ph_users WHERE id = ? AND banned != '0' LIMIT 1");
            $q->execute(array($id));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /**
         * Checks if the user is confirmed.
         * @param Int $id The id of the user.
         * @return Bool Returns true if the user is confirmed.
         */
        public function isConfirmed($id){
            $q = $this->db->prepare("SELECT id FROM ph_users WHERE id = ? AND confirmed = '1' LIMIT 1");
            $q->execute(array($id));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /**
         * Returns an array with public users.
         */
        public function getPublic(){
            $q = \Core\DBM::getMain()->prepare("SELECT id FROM ph_users WHERE private = 0");
            if(!$q->execute()){ return []; };
            return $q->fetchAll(\PDO::FETCH_COLUMN);
        }

        /**
         * Gets the id from the username.
         * @param String $name Name of the user
         * @return Int Returns the id of the user on success otherwise returns -1
         */
        public function getIdByName($name){
            $q = $this->db->prepare("SELECT id FROM ph_users WHERE username = ? LIMIT 1");
            $q->execute(array($name));
            if($q->rowCount() < 1){ return -1; }
            return intval($q->fetchObject()->id);
        }

        /**
         * Gets the id from the email.
         * @param String $email Email of the user
         * @return Int Returns the id of the user on success otherwise returns -1
         */
        public function getIdByEmail($email){
            $q = $this->db->prepare("SELECT id FROM ph_users WHERE email = ? LIMIT 1");
            $q->execute([$email]);
            if($q->rowCount() < 1){ return -1; }
            return intval($q->fetchObject()->id);
        }


        /** REWRITE \/ !! */

        public function getSecurity($id){
            $q = $this->db->prepare("SELECT security FROM ph_users WHERE id = ? LIMIT 1");
            $q->execute([$id]);
            if($q->rowCount() < 1){ return false; }
            return $q->fetchObject()->security;
        }

        public function setSecurity($id,$sec){
            $q = $this->db->prepare("UPDATE ph_users SET security = ? WHERE id = ? LIMIT 1");
            $q->execute([$sec,$id]);
            if($q->rowCount() < 1){ return false; }
            return true;
        }

        public function verifySecurity($id, $sec){
            $q = $this->db->prepare("SELECT id FROM ph_users WHERE id = ? AND security = ? LIMIT 1");
            $q->execute([$id,$sec]);
            if($q->rowCount() > 0){ return true; }
            return false;
        }

    }

?>