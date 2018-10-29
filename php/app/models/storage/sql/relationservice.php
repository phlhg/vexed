<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Storage\Sql;

    /**
     * Loads, updates and deletes entries from the friends-table.
     */
    class RelationService extends \Core\Model {

        /**
         * Returns an array with ids of followers.
         * @param Int $user Id of the user to load the followers.
         * @return Mixed[] Returns an array with ids on success otherwise an empty array.
         */
        public function getFollowers(Int $user){
            $q = \Core\DBM::getMain()->prepare("SELECT user FROM ph_relations WHERE follow = ? AND state = ?");
            if(!$q->execute([$user,\App\Models\Account\Relation::FOLLOWING])){ return []; };
            return $q->fetchAll(\PDO::FETCH_COLUMN);
        }

        /**
         * Returns an array with ids of subscriptions.
         * @param Int $user Id of the user to load the subscriptions.
         * @return Mixed[] Returns an array with ids on success otherwise an empty array.
         */
        public function getSubscriptions(Int $user){
            $q = \Core\DBM::getMain()->prepare("SELECT follow FROM ph_relations WHERE user = ? AND state = ?");
            if(!$q->execute([$user,\App\Models\Account\Relation::FOLLOWING])){ return []; };
            return $q->fetchAll(\PDO::FETCH_COLUMN);
        }

        /**
         * Returns the current relation form a user to the client.
         * @param Int $user User to get the relation.
         * @return Int Returns the current relation on success otherwise STRANGER.
         */
        public function getState(Int $user){
            $client = \App::$client->id;
            if($client == $user){ return \App\Models\Account\Relation::ME; }
            $q = \Core\DBM::getMain()->prepare("SELECT state FROM ph_relations WHERE user = ? AND follow = ? LIMIT 1");
            if(!$q->execute([$client, $user])){ return \App\Models\Account\Relation::STRANGER; };
            if($q->rowCount() < 1){ return \App\Models\Account\Relation::STRANGER; }
            return intval($q->fetch()["state"]);
        }

        /**
         * Creates a Relation.
         * @param Int $follow The id of the user to follow.
         * @return Boolean Returns True on success otherwise False.
         */
        public function create(Int $follow){
            $client = \App::$client->id;
            $q = \Core\DBM::getMain()->prepare("INSERT INTO ph_relations (user, follow, state, date) VALUES (?,?,?,?);");
            if(!$q->execute([$client,$follow,\App\Models\Account\Relation::REQUESTED,time()])){ return false; }
            if($q->rowCount() < 1){ return false; }
            return true;
        }

        /**
         * Sets a state for a Relation.
         * @param Int $follow The id of the followed user.
         * @param Int $state The new state of the realtion.
         */
        public function setState(Int $follow, Int $state){
            $client = \App::$client->id;
            $q = $this->db->prepare("UPDATE ph_relations SET state = ? WHERE user = ? AND follow = ? LIMIT 1");
            if(!$q->execute([$state,$client,$follow])){ return false; };
            return true;
        }

        /**
         * Accepts a request.
         * @param Int $follower The id of the accepted follower.
         * @return Boolean Returns True on success otherwise False.
         */
        public function accept(Int $follower){
            $client = \App::$client->id;
            $q = $this->db->prepare("UPDATE ph_relations SET state = ? WHERE user = ? AND follow = ? AND state = ? LIMIT 1");
            $q->execute([\App\Models\Account\Relation::FOLLOWING,$follower,$client,\App\Models\Account\Relation::REQUESTED]);
            if($q->rowCount() < 1){ return false; }
            return true;
        }

        /**
         * Denies a request.
         * @param Int $follower The id of the denied follower.
         * @return Boolean Returns True on success otherwise False.
         */
        public function deny(Int $follower){
            $client = \App::$client->id;
            $q = $this->db->prepare("UPDATE ph_relations SET state = ? WHERE user = ? AND follow = ? AND state = ? LIMIT 1");
            $q->execute([\App\Models\Account\Relation::STRANGER,$follower,$client,\App\Models\Account\Relation::REQUESTED]);
            if($q->rowCount() < 1){ return false; }
            return true;
        }

        /**
         * Deletes a Relation.
         * @param Int $follow The id of the user to unfollow.
         * @return Boolean Returns True if the Friendship was deleted otherwise false.
         */
        public function delete(Int $follow){
            $client = \App::$client->id;
            $q = $this->db->prepare("DELETE FROM ph_relations WHERE user = ? AND follow = ? LIMIT 1");
            if(!$q->execute([$client,$follow])){ return false; }
            if($q->rowCount() < 1){ return false; }
            return true;
        }

    }

?>