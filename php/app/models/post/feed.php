<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Post;

    /**
     * Handles the generation of a post feed.
     */
    class Feed {

        private $postservice;

        public $postlist = [];

        public function __construct(){
            $this->postservice = new \App\Models\Storage\Sql\PostService();
            $this->load();
        }
        
        private function getSubscriptions(){
            $subs = \App::$client->subscriptions;
            $subs[] = \App::$client->id;
            return $subs;
        }

        public function load(){
            $this->postlist = $this->postservice->getByUsers($this->getSubscriptions());
        }

    }

?>