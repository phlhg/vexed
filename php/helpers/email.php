<?php

    namespace Helpers;
    use \Core\Config;

    class Email {

        private $content = "";
        private $render = "";
        private $header = "";
        public  $to = "";
        public  $to_user = null;
        private $from = "";
        private $fromName = "";
        public  $subject = "";

        public function __construct($to,$subject){
            $this->to = $to;
            $this->to_user = \App\Models\Account\User::getByEmail($this->to);
            $this->from = \Core\Config::get("email_noreply");
            $this->fromName = \Core\Config::get("email_noreply_name");
            $this->subject = $subject;
        }

        private function generateHeader(){
            $header[] = 'From: '.$this->fromName.' < '.$this->from.' >';
            $header[] = 'MIME-Version: 1.0';
            $header[] = 'Content-type: text/html; charset=utf-8';
            $this->header = implode("\n",$header);
        }

        public function add($string){
            $this->content .= $string;
        }

        public function render(){
            $this->view = new \Core\View("extra/email/template");
            $this->view->setTemplate("none");
            $this->view->v->subject = $this->subject;
            $this->view->v->content = $this->content;
            $this->view->v->to_email = $this->to;
            $this->view->v->to_user = $this->to_user;
            $this->view->render();
        }

        public function send(){
            $this->generateHeader();
            $this->render();
            return mail($this->to,$this->subject,$this->view->getRender(),$this->header);
        }
        
    }

?>