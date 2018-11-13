<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Storage\Sql;

    /**
     * Loads, updates and deletes entries from the media-table.
     */
    class MediaService extends \Core\Model {

        /**
         * Loads information of specific media.
         * @param Int $id The id of the media.
         * @return Mixed[]|Boolean Returns an Array with information if the media was found otherwise False.
         */
        public function get($id){
            $q = $this->db->prepare("SELECT id, post, type, size FROM ph_media WHERE id = ? LIMIT 1");
            $q->execute([$id]);
            if($q->rowCount() < 1){ return false; }
            return $q->fetch();
        }

        public function ofPost($id){
            $q = $this->db->prepare("SELECT id FROM ph_media WHERE post = ? LIMIT 1");
            $q->execute([$id]);
            if($q->rowCount() < 1){ return []; }
            return $q->fetchAll(\PDO::FETCH_COLUMN);
        }

        public function create($post,$type,$size){
            $id = __CLIENT()->id;
            $q = \Core\DBM::getMain()->prepare("INSERT INTO ph_media (post, type, size, user, time) VALUES (?,?,?,?,?);");
            if(!$q->execute([$post,$type,$size,$id,time()])){ return false; }
            if($q->rowCount() < 1){ return false; }
            $q2 = $this->db->prepare("SELECT id FROM ph_media ORDER BY id DESC LIMIT 1");
            if(!$q2->execute()){ return false; }
            return $q2->fetch()["id"];
        }

        public function delete($id){
            $q = \Core\DBM::getMain()->prepare("DELETE FROM ph_media WHERE id = ?");
            if(!$q->execute([$id])){ return false; }
            if($q->rowCount() < 1){ return false; }
            return true;
        }

    }

?>