<?php

    namespace App\Controllers;

    class Signup extends \Core\Controller {

        public function index($_URL){
            $this->view = new \Core\View("signup/index");
            $this->view->meta->title = "Registrieren";
            $this->view->meta->description = "Erstelle dir einen eigenen Account und finde deine Freunde";
            $this->view->v->signup_form_actcode = "";
            if(\Helpers\Post::exists("ph_signup_actcode") && \Helpers\Post::exists("ph_signup_tkn")){
                if(!\Helpers\Token::check(\Helpers\Post::get("ph_signup_tkn"))){ 
                    $this->view->v->signup_form_info = "Ungültiger Token - Anfrage blockiert";
                    return;
                }
                $actcode = str_replace(" ","",\Helpers\Post::get("ph_signup_actcode"));
                if($actcode != "ABCDEFGHI"){
                    $this->view->v->signup_form_error = "Ungültiger Aktivierungscode (".$actcode.")";
                    return;
                }
                \Core\Router::redirect("/signup/".$actcode."/1/");
            }
        }

        public function advanced($_URL){
            $actcode = $_URL["actcode"];
            if($actcode != "ABCDEFGHI"){ \Core\Router::redirect("/signup/"); return; }
            $step = $_URL["step"];
            $signup_data = array(
                "username" => "",
                "email" => "",
                "password" => "",
                "confirmed" => false,
                "complete" => false,
                "time" => time()
            );

            if(\Helpers\Session::exists("signup_data")){ $signup_data = \Helpers\Session::get("signup_data"); }

            if($step == 5 && $signup_data["username"] != "" && $signup_data["email"] != "" && $signup_data["password"] != ""  && $signup_data["confirmed"] == true){ 
                if($signup_data["username"] == "" OR $signup_data["email"] == "" OR $signup_data["password"] == "" OR $signup_data["confirmed"] == false){ \Core\Router::redirect("/signup/".$actcode."/4/"); }
                $this->view = new \Core\View("signup/confirm");
                $this->view->meta->title = "Bestätigen";
                $signup_data["complete"] = true;
            } else if($step == 4){
                if($signup_data["username"] == "" OR $signup_data["email"] == "" OR $signup_data["password"] == ""){ \Core\Router::redirect("/signup/".$actcode."/3/"); }
                $this->view = new \Core\View("signup/create");
                $this->view->meta->title = "Erstellen";
                if(\Helpers\Post::exists("ph_signup_cond")){
                    $signup_data["confirmed"] = true; 
                    \Core\Router::redirect("/signup/".$actcode."/5/");
                }
            } else if($step == 3){
                if($signup_data["username"] == "" OR $signup_data["password"] == ""){ \Core\Router::redirect("/signup/".$actcode."/2/"); }
                $this->view = new \Core\View("signup/email");
                $this->view->meta->title = "E-Mail-Adresse angeben";
                if(\Helpers\Post::exists("ph_signup_email")){
                    $signup_data["email"] = \Helpers\Post::get("ph_signup_email");
                    \Core\Router::redirect("/signup/".$actcode."/4/");
                }
            } else if($step == 2 && $signup_data["username"] != ""){
                if($signup_data["username"] == ""){ \Core\Router::redirect("/signup/".$actcode."/1/"); }
                $step = 2;
                $this->view = new \Core\View("signup/password");
                $this->view->meta->title = "Passwort wählen";
                if(\Helpers\Post::exists("ph_signup_password") && \Helpers\Post::exists("ph_signup_password_confirm")){
                    $signup_data["password"] = \Helpers\Post::get("ph_signup_password");
                    \Core\Router::redirect("/signup/".$actcode."/3/");
                }
            } else if($step == 1){
                $this->view = new \Core\View("signup/username");
                $this->view->meta->title = "Nutzername wählen";
                if(\Helpers\Post::exists("ph_signup_username")){
                    $username = \Helpers\Post::get("ph_signup_username");
                    if($username != "phlhg"){
                        $signup_data["username"] = $username;
                        \Core\Router::redirect("/signup/".$actcode."/2/");
                    } else {
                        $this->view->v->form_error = "Nutzername ist vergeben";
                    }
                }
            } else {
                \Core\Router::redirect("/signup/");
            }

            $this->view->v->signup_form_username = $signup_data["username"];
            $this->view->v->signup_form_password = $signup_data["password"];
            $this->view->v->signup_form_email = $signup_data["email"];
            $this->view->v->signup_step = $step-1;
            $this->view->v->signup_step_max = 5;
            \Helpers\Session::set("signup_data",$signup_data);
            if($signup_data["complete"] == true){ \Helpers\Session::delete("signup_data"); }
        }
    }

?>