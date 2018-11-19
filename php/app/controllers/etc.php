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

        public function conditions($_URL){
            $this->view("conditions/index");
            $this->view->meta->title = "Nutzungsbedingungen";
            $this->view->meta->description = "Rechtliches zur Nutzung des Netzwerkes";

            $this->view->v->last_edit = date("d.m.Y",\Core\Config::get("conditions_version_last"));
            $content = file_get_contents(\Core\Config::get("storage_root")."/conditions.txt");
            $this->view->v->conditions_content = $content;
        }

    }
?>