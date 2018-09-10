<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\DB;
    use \Helpers\Hash;

    /**
     * Manages access to to the users table.
     */
    class UserService extends \Core\Model {

        /**
         * Returns non security related information about the user.
         * @param Int $id The id of the user.
         * @return Mixed[]|Boolean Returns an Array with information about the user if the user was found otherwise False.
         */
        public function get(Int $id){
            $q = $this->db->prepare("SELECT id, username, email, admin, verified, private, banned, description, created, conditions FROM ph_users WHERE id = ? LIMIT 1");
            $q->execute(array($id));
            if($q->rowCount() < 1){ return false; }
            return $q->fetchArray();
        }

        /** 
         * Returns wether the user exists or not.
         * @param Int $id The id of the user.
         * @return Boolean Returns True if the user exists otherwise False.
        */
        public function exists(Int $id){
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
        public function existsEmail(String $email){
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
        public function existsName(String $name){
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
        public function create(String $name, String $password, String $email, String $security){
            $created = time();
            $conditions = \Core\Config::get("conditions_version_last");
            $security = \Helpers\Hash::ph1($security);
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
        public function delete(Int $id){
            $q = $this->db->prepare("DELETE FROM ph_users WHERE id = ? LIMIT 1");
            $q->execute(array($id));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /**
         * Confirms an account if the code is correct.
         */
        public function confirm($id,$code){
            $code = \Helpers\Hash::ph1($password);
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
            $password = \Helpers\Hash::ph1($password);
            $q = $this->db->prepare("SELECT id FROM ph_users WHERE id = ? AND password = ? LIMIT 1");
            $q->execute(array($id,$password));
            if($q->rowCount() < 1){ return false; }
            return true;
        }


    }

?>