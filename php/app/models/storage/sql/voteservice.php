<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Storage\Sql;

    /**
     * Loads, updates and deletes entries from the vote-table.
     */
    class VoteService extends \Core\Model {

        public function getVotes($post){
            $q = $this->db->prepare("SELECT SUM(vote) as votes FROM ph_votes WHERE post = ?");
            if(!$q->execute([$post])){ return 0; }
            if($q->rowCount() < 1){ return 0; }
            return intval($q->fetch()["votes"]);
        }

        public function getUpVotes($post){
            $q = $this->db->prepare("SELECT user FROM ph_votes WHERE post = ? AND vote > 0");
            if(!$q->execute([$post])){ return []; }
            return $q->fetchAll(\PDO::FETCH_COLUMN);
        }

        public function getClient($post){
            $client = __CLIENT()->id;
            $q = $this->db->prepare("SELECT vote  FROM ph_votes WHERE post = ? AND user = ? LIMIT 1");
            if(!$q->execute([$post,$client])){ return 0; }
            if($q->rowCount() < 1){ return 0; }
            return intval($q->fetch()["vote"]);
        }

        public function set($post,$value){
            if(!$this->exists($post)){ $this->create($post); }
            return $this->setVote($post,$value);
        }

        public function setVote($post,$value){
            $client = __CLIENT()->id;
            $q = $this->db->prepare("UPDATE ph_votes SET vote = ? WHERE post = ? AND user = ? LIMIT 1");
            if(!$q->execute([$value,$post,$client])){ return false; }
            if($q->rowCount() < 1){ return false; }
            return true;
        }

        public function exists($post){
            $client = __CLIENT()->id;
            $q = $this->db->prepare("SELECT id FROM ph_votes WHERE post = ? AND user = ? LIMIT 1");
            if(!$q->execute([$post,$client])){ return false; }
            if($q->rowCount() < 1){ return false; }
            return true;
        }

        public function create($post){
            $client = __CLIENT()->id;
            $q = \Core\DBM::getMain()->prepare("INSERT INTO ph_votes (user, post, vote, time) VALUES (?,?,?,?);");
            if(!$q->execute([$client,$post,\App\Models\Post\Vote::NEUTRAL,time()])){ return false; }
            if($q->rowCount() < 1){ return false; }
            return true;
        }

        public function delete($post){
            $client = __CLIENT()->id;
            $q = \Core\DBM::getMain()->prepare("DELETE FROM ph_votes WHERE user = ? AND post = ? LIMIT 1");
            if(!$q->execute([$client,$post])){ return false; }
            return true;
        }

        public function getPopular($max){
            $max = intval($max);
            $q = $this->db->prepare("SELECT post FROM ph_votes GROUP BY post ORDER BY ABS(SUM(vote)) DESC, id DESC LIMIT ".$max);
            if(!$q->execute([$max])){ return []; }
            return $q->fetchAll(\PDO::FETCH_COLUMN);
        }

        public function deleteFromPost($post){
            $q = $this->db->prepare("DELETE FROM ph_votes WHERE post = ?");
            if(!$q->execute([$post])){ return false; }
            return true;
        }

    }

?>