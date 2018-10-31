<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Storage\Sql;

    /**
     * Loads, updates and deletes entries from the post-table.
     */
    class PostService extends \Core\Model {

        /**
         * Loads information from a specific post.
         * @param Int $id The id of the post.
         * @return Mixed[]|Boolean Returns an Array with information if the post was found otherwise False.
         */
        public function get(Int $id){
            $q = $this->db->prepare("SELECT id, user, type, description, date FROM ph_posts WHERE id = ? LIMIT 1");
            $q->execute([$id]);
            if($q->rowCount() < 1){ return false; }
            return $q->fetch();
        }

        
        /**
         * Loads all posts.
         * @deprecated Only temporary
         * @return Int[] Returns an Array with post ids.
         */
        public function getAll(){
            $q = $this->db->prepare("SELECT id, user, type, description, date FROM ph_posts ORDER BY date DESC");
            $q->execute();
            if($q->rowCount() < 1){ return []; }
            return $q->fetchAll(\PDO::FETCH_COLUMN);
        }

        /**
         * Loads all Posts of a user.
         * @param Int $id The id of the user.
         * @return Int[] Returns an array with ids of posts.
         */
        public function byUser(Int $id){
            $q = $this->db->prepare("SELECT id FROM ph_posts WHERE user = ? ORDER BY date DESC");
            if($q->execute([$id])){ return []; }
            return $q->fetchAll(\PDO::FETCH_COLUMN);
        }


        /**
         * Creats a text-post.
         * @param String $text The formatted content of the post.
         * @return Boolean Returns True if the post was created otherwise False.
         */
        public function createText(String $text){
            $id = \App::$client->id;
            $q = \Core\DBM::getMain()->prepare("INSERT INTO ph_posts (user, type, description, date) VALUES (?,?,?,?);");
            $q->execute([$id,\App\Models\Post\Type::TEXT,$text,time()]);
            if($q->rowCount() < 1){ return false; }
            return true;
        }

    }

?>