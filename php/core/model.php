<?php

    namespace Core;

    class Model {

        protected $db = null;

        public function __construct(\PDO $db = null){
            if(!isset($db)){ $db = DBM::getMain(); }
            $this->db = $db;
        }
    }