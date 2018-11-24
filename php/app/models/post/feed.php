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
        }
        
        private function getSubscriptions(){
            $subs = (\App::$client->subscriptions ? \App::$client->subscriptions : []);
            $subs[] = \App::$client->id;
            return $subs;
        }

        public function loadLatest($last=0){
            $this->postlist = $this->postservice->getLatest($this->getSubscriptions(),$last);
        }

        public function loadPrevious($prev=0){
            $this->postlist = $this->postservice->getPrevious($this->getSubscriptions(),$prev);
        }

        public function loadInit(){
            $this->postlist = $this->postservice->getInit($this->getSubscriptions());
        }

    }

?>