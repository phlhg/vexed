<?php

    namespace App\Controllers;

    class Error extends \Core\Controller {

        public function E404(){
            header("HTTP/1.0 404 Not Found");
            $this->view = new \Core\View("errors/404",$this->client);
            $this->view->meta->title = "404 Fehler";
        }

        public function Exception($_URL,$details){
            header("HTTP/1.0 503 Service Unavailable");
            $this->view = new \Core\View("errors/exception",$this->client);
            $this->view->meta->title = "Etwas ist schief gelaufen";
            $this->view->v->error_details = $details;
        }
        
    }

?>