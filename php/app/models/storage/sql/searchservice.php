<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Storage\Sql;

    /**
     * Manages access to to the users table.
     */
    class SearchService extends \Core\Model {

        public function find($query){
            $q = $this->db->prepare("SELECT id FROM ph_users WHERE username LIKE ? OR description LIKE ? OR website LIKE ? LIMIT 20");
            if(!$q->execute([$query,$query,$query])){ return false; };
            if($q->rowCount() < 1){ return []; }
            return $q->fetchAll(\PDO::FETCH_COLUMN);
        }

    }

?>