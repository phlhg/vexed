<?php

    namespace App\Controllers;

    class Error extends \Core\Controller {

        public function E404(){
            header("HTTP/1.0 404 Not Found");
            $this->view("errors/404");
            $this->view->meta->title = "404 Seite nicht gefunden";
        }

        public function Exception($_URL,$details){
            header("HTTP/1.0 503 Service Unavailable");
            $this->view("errors/exception");
            $this->view->meta->title = "Etwas ist schief gelaufen";
            $this->view->v->error_details = $details;
        }
        
    }

?>