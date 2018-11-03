<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Controllers;

    class etc extends \Core\Controller {

        public function about($_URL){
            $this->view("about/index");
            $this->view->meta->title = "Über";
        }

    }
?>