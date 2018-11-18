<?php

    namespace App\Controllers;

    class Signup extends \Core\Controller {

        private $creator;
        private $actcode;
        private $step;
        private $sud;
        private $error = "";

        public function index($_URL){
            $this->view("signup/index");
            $this->view->meta->title = "Registrieren";
            $this->view->meta->description = "Erstelle dir einen eigenen Account und finde deine Freunde";
            $this->view->v->signup_form_actcode = "";
            if(!\Helpers\Post::exist(["ph_signup_actcode","ph_signup_tkn"])){ return; }
            if(!\Helpers\Token::check(\Helpers\Post::get("ph_signup_tkn"))){ return $this->view->v->form_error = "Ungültiger Token - Anfrage blockiert"; }
            $actcode = str_replace(" ","",\Helpers\Post::get("ph_signup_actcode"));
            if(!\App\Models\Code::exists("signup",$actcode)){ return $this->view->v->form_error = "Leider ist der Code ungültig oder gebraucht"; }
            \App::$router->redirect("/signup/".$actcode."/1/");
        }

        public function confirm($_URL){
            $id = $_URL["id"];
            $seccode = $_URL["seccode"];
            $user = new \App\Models\Account\User($id);
            if($user->confirm($seccode)){
                $this->view("signup/confirm/success");
                $this->view->meta->title = "Bestätigt";
            } else {
                $this->view("signup/confirm/fail");
                $this->view->meta->title = "Ungültig";
            }
        }

        public function advanced($_URL){
            $this->creator = new \App\Models\Account\Creator();
            $this->validator = new \App\Models\Account\Validator();
            $this->actcode = $_URL["actcode"];
            $this->step = intval($_URL["step"]);

            $this->sud = ["username" => "", "email" => "", "password" => "", "confirmed" => false, "complete" => false, "time" => time()];
            if(\Helpers\Session::exists("signup_data")){ $this->sud = \Helpers\Session::get("signup_data"); }

            if($this->step == 5 && $this->sud["username"] != "" && $this->sud["email"] != "" && $this->sud["password"] != ""  && $this->sud["confirmed"] == true){ 
                $this->step5();
            } else if($this->step == 4){
                $this->step4();
            } else if($this->step == 3){
                $this->step3();
            } else if($this->step == 2 && $this->sud["username"] != ""){
                $this->step2();
            } else if($this->step == 1){
                $this->step1();
            } else {
                \App::$router->redirect("/signup/");
            }

            //variables
            $this->view->v->signup_form_username = $this->sud["username"];
            $this->view->v->signup_form_password = $this->sud["password"];
            $this->view->v->signup_form_email = $this->sud["email"];
            $this->view->v->signup_step = $this->step-1;
            $this->view->v->signup_step_max = 5;
            $this->view->v->form_error = ($this->error != "" ? $this->error : "");
            $this->view->meta->description = "[✔] Erstelle dir einen eigenen Account auf VEXED";

            //Signup session
            \Helpers\Session::set("signup_data",$this->sud);
            if($this->sud["complete"] == true){ \Helpers\Session::delete("signup_data"); }
        }

        private function step1(){
            //USERNAME
            if(!\App\Models\Code::exists("signup",$this->actcode)){ return \App::$router->redirect("/signup/"); }
            $this->view("signup/username");
            $this->view->meta->title = "Nutzername wählen";
            if(!\Helpers\Post::exists("ph_signup_username")){ return; }
            $username = \Helpers\Post::get("ph_signup_username");
            if($this->validator->usedUsername($username)){ return $this->error("Nutzername ist vergeben"); }
            $this->sud["username"] = $username;
            \App::$router->redirect("/signup/".$this->actcode."/2/");
        }

        private function step2(){
            //PASSWORD
            if(!\App\Models\Code::exists("signup",$this->actcode)){ return \App::$router->redirect("/signup/"); }
            $this->view("signup/password");
            $this->view->meta->title = "Passwort wählen";
            if(!\Helpers\Post::exist(["ph_signup_password","ph_signup_password_confirm"])){ return; }
            if(\Helpers\Post::get("ph_signup_password") != \Helpers\Post::get("ph_signup_password_confirm")){ return $this->error("Passwörter entsprechen sich nicht"); }
            $this->sud["password"] = \Helpers\Post::get("ph_signup_password");
            \App::$router->redirect("/signup/".$this->actcode."/3/");
        }

        private function step3(){
            //EMAIL
            if(!\App\Models\Code::exists("signup",$this->actcode)){ return \App::$router->redirect("/signup/"); }
            if($this->sud["username"] == "" OR $this->sud["password"] == ""){ \App::$router->redirect("/signup/".$this->actcode."/2/"); }
            $this->view("signup/email");
            $this->view->meta->title = "E-Mail";
            if(!\Helpers\Post::exists("ph_signup_email")){ return; }
            $email = \Helpers\Post::get("ph_signup_email");
            if(!$this->validator->isEmail($email)){ return $this->error("Bitte gib eine gültige E-Mail ein"); }
            if(!$this->validator->existsEmailProvider($email)){ return $this->error("Bitte gib eine gültige E-Mail ein <i>[dns]</i>"); }
            if($this->validator->usedEmail($email)){ return $this->error("Diese E-Mail-Adresse wird bereits verwendet"); }
            $this->sud["email"] = \Helpers\Post::get("ph_signup_email");
            \App::$router->redirect("/signup/".$this->actcode."/4/");
        }

        private function step4(){
            //CREATE / CONDITIONS
            if(!\App\Models\Code::exists("signup",$this->actcode)){ return \App::$router->redirect("/signup/"); }
            if($this->sud["username"] == "" OR $this->sud["email"] == "" OR $this->sud["password"] == ""){ \App::$router->redirect("/signup/".$this->actcode."/3/"); }
            $this->view("signup/create");
            $this->view->meta->title = "Erstellen";
            if(!\Helpers\Post::exists("ph_signup_cond")){ return; }
            if(!$this->creator->create($this->sud["username"],$this->sud["password"],$this->sud["email"])){ $this->error($this->creator->errorMsg); }
            \App\Models\Code::redeem("signup",$this->actcode);
            $this->sud["confirmed"] = true; 
            \App::$router->redirect("/signup/".$this->actcode."/5/");
        }

        private function step5(){
            //CONFIRM
            if(!\Helpers\Session::exists("signup_data")){ return \App::$router->redirect("/signup/"); }
            if($this->sud["username"] == "" OR $this->sud["email"] == "" OR $this->sud["password"] == "" OR $this->sud["confirmed"] == false){ \App::$router->redirect("/signup/".$actcode."/4/"); }
            $this->view("signup/confirm");
            $this->view->meta->title = "Bestätigen";
            $this->sud["complete"] = true;
        }

        private function error($msg){
            $this->error = $msg;
            return false;
        }

    }