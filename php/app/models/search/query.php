<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Search;

    /**
     * Handles the process of creating a new post.
     */
    class Query {

        private $ss;

        public $query = "";
        public $error = false;
        public $errorMsg = "";
        public $results = [];

        public function __construct($query){
            $this->ss = new \App\Models\Storage\Sql\SearchService();
            $this->query = Self::bake($query);
        }

        public function search(){
            $res = $this->ss->find($this->query);
            if($res === false){ return $this->error("Es ist ein Fehler aufgetreten"); }
            $this->results = $res;
            return true;
        }

        public static function bake($query){
            //abcd -> %a%b%c%d%
            return "%".implode("%",str_split(str_replace(" ","",$query)))."%";
        }

        private function error($msg){
            $this->error = true;
            $this->errorMsg = $msg;
            return false;
        }

    }

?>