<?php

    namespace Core;

    class Controller {

        protected static $_loaded = [];
        protected $db = null;
        public $client = null;
        public $view = null;

        public function __construct(){
            $this->db = DBM::get("ph");
            $this->client = new \App\Models\Client($this->db);
        }

        public function showLogin(){
            $this->view = new \Core\View("login/index");
            $this->view->meta->title = "Anmelden";
        }

        public function Login(){
            if(!$this->client->isLoggedIn()){ $this->showLogin(); return false; }
            return true;
        }

        public function isLoggedIn(){
            return $this->client->isLoggedIn();
        }

    }