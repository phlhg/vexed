<?php

    namespace App\Controllers;

    class Media extends \Core\Controller {

        public function index($_URL){
            if(!$this->client->authenticate()){ return false; }
            $this->view("out");
            $this->view->setTemplate("none");
            $id = $_URL["id"];
            if(!isset($id) && is_int($id)){ $id = 0; }
            $folder = $_SERVER["DOCUMENT_ROOT"].'/php/files/media/';
            $filetype = false;
            $filetypes = ["jpg","png","gif"];
            $tiny = "";
            if(\Helpers\Get::exists("tiny")){ $tiny = "_tiny"; }
            foreach($filetypes as $type){
                if(is_readable($folder.$id.$tiny.'.'.$type)){
                    $filetype = $type;
                }
            }

            if($filetype == false){ $filetype = "jpg"; $id = "0"; }
            $this->view->setFormat("image/".$filetype);
            $this->view->v->content = file_get_contents($folder.$id.$tiny.'.'.$filetype);

            //CACHE
            //by https://stackoverflow.com/questions/6214965/force-browser-to-cache-images
            //by https://stackoverflow.com/questions/1971721/how-to-use-http-cache-headers-with-php
            @session_cache_limiter('private_no_expire');
            $this->view->header('Pragma: private');
            if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) < filemtime($folder.$id.$tiny.'.'.$filetype)){ $this->view->header('HTTP/1.1 304 Not Modified'); return; }
            $this->view->header('Cache-control: private, max-age='.(60*60*24*30));
            $this->view->header('Expires: '.gmdate(DATE_RFC1123,time()+60*60*24*30));
            $this->view->header('Last-Modified: '.gmdate(DATE_RFC1123,filemtime($folder.$id.$tiny.'.'.$filetype)));
        }

    }

?>