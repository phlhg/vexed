<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Storage\Sql;

    /**
     * Loads, updates and deletes entries from the media-table.
     */
    class MediaService extends Core\Model {

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



    }

?>