<?php

    namespace Core;

    class Model {

        protected $db = null;

        public function __construct(){
            $this->db = DBM::get("ph");
        }
    }