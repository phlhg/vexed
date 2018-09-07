<?php

    namespace App\Models;
    use \Core\Config;

    class Email {

        private $content = "";
        private $render = "";
        private $header = "";
        public  $to = "";
        private $from = "";
        private $fromName = "";
        public  $subject = "";

        public function __construct($to,$subject){
            $this->to = $to;
            $this->from = \Core\Config::get("email_noreply");
            $this->fromName = \Core\Config::get("email_noreply_name");
            $this->subject = $subject;
        }

        private function generateHeader(){
            $header[] = 'From: '.$this->fromName.' <From: '.$this->from.'>';
            $header[] = 'MIME-Version: 1.0';
            $header[] = 'Content-type: text/html; charset=utf-8';
            $this->header = implode("\n",$header);
        }

        public function add($string){
            $this->content .= $string;
        }

        public function render(){
            $this->view = new \Core\View("extra/email/template","none");
            $this->view->v->subject = $this->subject;
            $this->view->v->content = $this->content;
            $this->view->v->to_email = $this->to;
            $this->view->v->to_name = "phlhg";
            $this->view->render();
        }

        public function send(){
            $this->generateHeader();
            $this->render();
            file_put_contents($_SERVER["DOCUMENT_ROOT"]."php/files/logs/email/".uniqid().".html",$this->view->getRender());
            return mail($this->to,$this->subject,$this->view->getRender(),$this->header);
        }
        
    }

?>