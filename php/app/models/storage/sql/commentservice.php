<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Storage\Sql;

    /**
     * Manages access to to the comment table.
     */
    class CommentService extends \Core\Model {

        /**
         * Returns information about a comment.
         * @param Int $id Id of a comment
         * @return Mixed[]|Boolean Returns an array with infos on success otherwise returns False.
         */
        public function get($id){
            $q = $this->db->prepare("SELECT id, user, post, content, time FROM ph_comments WHERE id = ? LIMIT 1");
            if(!$q->execute([$id])){ return false; }
            if($q->rowCount() < 1){ return false; }
            return $q->fetch();
        }

        /**
         * Gets all comments for a post.
         * @param Int $post Id of the post.
         * @return Int[] Returns an array of ids
         */
        public function byPost($post){
            $q = $this->db->prepare("SELECT id FROM ph_comments WHERE post = ? ORDER BY time DESC");
            if(!$q->execute([$post])){ return []; }
            return $q->fetchAll(\PDO::FETCH_COLUMN);
        }

        /**
         * Creates a new comment.
         * @param Int $post Id of the post.
         * @param String $content Content of the post. 
         * @return Bool Returns true if the comment was created else false.
         */
        public function create($post,$content){
            $client = __CLIENT()->id;
            $q = $this->db->prepare("INSERT INTO ph_comments (user, post, content, time) VALUES (?,?,?,?);");
            if(!$q->execute([$client,$post,$content,time()])){ return false; }
            $q2 = $this->db->prepare("SELECT id FROM ph_comments WHERE user = ? AND post = ? ORDER BY time DESC LIMIT 1");
            if(!$q2->execute([$client,$post])){ return false; }
            return intval($q2->fetch()["id"]);
        }

        /**
         * Deletes a comment.
         * @param Int $id Id of the comment.
         * @return Bool Returns true if the comment was deleted else false.
         */
        public function delete($id){
            $q = $this->db->prepare("DELETE FROM ph_comments WHERE id = ? LIMIT 1");
            if(!$q->execute([$id])){ return false; }
            if($q->rowCount() < 1){ return false; }
            return true;
        }



    }
?>