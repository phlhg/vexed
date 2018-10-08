<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo 2018
     */

    namespace Core;

    /**
     * Default controller-class with basic methods.
     */
    class Controller {

        /** Stores the common database-connection for the whole controller. 
         * @var \PDO $db */
        protected $db = null;

        /** Stores the client-instance. 
         * @var \App\Models\Client $client */
        public $client = null;

        /** Stores the view for the request. 
         * @var \Core\View $view */
        public $view = null;

        /** 
         * Constructor - Loads the client-instance 
        */
        public function __construct(){
            $this->client = \App::$client;
        }

        /**
         * Loads a new view to the controller.
         * @param String $view Name of the view
         */
        public function view(String $view){
            $this->view = new \Core\View($view);
        }
    }