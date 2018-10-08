<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Storage\Sql;

    /**
     * Manages access to to the code table.
     */
    class CodeService extends \Core\Model {

        /**
         * Returns information about a code.
         * @param Int $id Id of a code
         * @return Mixed[]|Boolean Returns an array with infos on success otherwise returns False.
         */
        public function get(Int $id){
            $q = $this->db->prepare("SELECT id, description, type, code, uses, uses_init, expires FROM ph_codes WHERE id = ? LIMIT 1");
            $q->execute(array($id));
            if($q->rowCount() < 1){ return false; }
            return $q->fetch();
        }

        /**
         * Returns the Ids of all codes.
         * @return Mixed[]|Boolean Returns an array with infos on success otherwise returns False.
         */
        public function getAll(){
            $q = $this->db->prepare("SELECT id FROM ph_codes");
            $q->execute();
            if($q->rowCount() < 1){ return false; }
            return $q->fetchAll();
        }

        /**
         * Uses a code one time if it exists.
         * @param String $type Type of the code used
         * @param String $code Code to look for
         * @return Boolean Returns True if the code was used otherwise returns False.
         */
        public function use($type,$code){
            $q = $this->db->prepare("UPDATE ph_codes SET uses = uses - 1 WHERE type = ? AND code = ? AND uses > 0 AND expires > ? LIMIT 1");
            $q->execute(array($type,$code,time()));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /**
         * Checks wether a code exists or not
         * @param String $type Type to look for
         * @param String $code Code to look for
         * @return Boolean Returns True if the code exists otherwise returns False.
         */
        public function exists($type,$code){
            $q = $this->db->prepare("SELECT id FROM ph_codes WHERE type = ? AND code = ? AND uses > 0 AND expires > ? LIMIT 1");
            $q->execute(array($type,$code,time()));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /**
         * Creates a new code.
         * @param String $type Type of the new code.
         * @param String $code Code for the activation.
         * @param Int $time Amount of seconds the code is valid after creation.
         * @param Int $uses Amount of times a code can be used.
         */
        public function create(String $type, String $code, Int $time, Int $uses=1, String $description=''){
            $expires = time() + $time;
            $q = $this->db->prepare("INSERT INTO ph_codes (description, type, code, uses, uses_init, expires) VALUES (?,?,?,?,?,?)");
            $q->execute(array($description,$type,$code,$uses,$uses,$expires));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /**
         * Removes a code from the database.
         * @param Int $id Id of the code to remove
         * @return Boolean Returns True if the code was deleted otherwise returns False.
         */
        public function delete($id){
            $q = $this->db->prepare("DELETE FROM ph_codes WHERE id = ? LIMIT 1");
            $q->execute(array($id));
            if($q->rowCount() > 0){ return true; }
            return false;
        }

        /**
         * Creates the table for the service.
         * @param \PDO $db Database connection.
         */
        public static function init($db){
            $db->prepare("
                CREATE TABLE ph_codes (
                    id int(10) NOT NULL AUTO_INCREMENT,
                    description varchar(255),
                    type varchar(50),
                    code varchar(21),
                    uses int(4) DEFAULT 1,
                    uses_init int(4) DEFAULT 1,
                    expires int(20),
                    PRIMARY KEY(id)
                );
            ")->execute();
        }

    }
