<?php

    namespace App\Controllers;

    class Signup extends \Core\Controller {

        public function index($_URL){
            $this->view("signup/index");
            $this->view->meta->title = "Registrieren";
            $this->view->meta->description = "Erstelle dir einen eigenen Account und finde deine Freunde";
            $this->view->v->signup_form_actcode = "";
            if(\Helpers\Post::exists("ph_signup_actcode") && \Helpers\Post::exists("ph_signup_tkn")){
                if(!\Helpers\Token::check(\Helpers\Post::get("ph_signup_tkn"))){ 
                    $this->view->v->form_info = "Ungültiger Token - Anfrage blockiert";
                    return;
                }
                $actcode = str_replace(" ","",\Helpers\Post::get("ph_signup_actcode"));
                if(!\App\Models\Code::exists("signup",$actcode)){
                    $this->view->v->form_error = "Leider ist der Code ungültig oder gebraucht";
                    return;
                }
                \App::$router->redirect("/signup/".$actcode."/1/");
            }
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
            $creationService = new \App\Models\Account\Creator();
            $actcode = $_URL["actcode"];
            if(!\App\Models\Code::exists("signup",$actcode)){ \App::$router->redirect("/signup/"); return; }
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
                if($signup_data["username"] == "" OR $signup_data["email"] == "" OR $signup_data["password"] == "" OR $signup_data["confirmed"] == false){ \App::$router->redirect("/signup/".$actcode."/4/"); }
                $this->view("signup/confirm");
                $this->view->meta->title = "Bestätigen";
                $signup_data["complete"] = true;
            } else if($step == 4){
                if($signup_data["username"] == "" OR $signup_data["email"] == "" OR $signup_data["password"] == ""){ \App::$router->redirect("/signup/".$actcode."/3/"); }
                $this->view("signup/create");
                $this->view->meta->title = "Erstellen";
                if(\Helpers\Post::exists("ph_signup_cond")){
                    if($creationService->create($signup_data["username"],$signup_data["password"],$signup_data["email"])){
                        \App\Models\Code::redeem("signup",$actcode);
                        $signup_data["confirmed"] = true; 
                        \App::$router->redirect("/signup/".$actcode."/5/");
                    } else {
                        $this->view->v->form_error = $creationService->errorMsg;
                    }
                }
            } else if($step == 3){
                if($signup_data["username"] == "" OR $signup_data["password"] == ""){ \App::$router->redirect("/signup/".$actcode."/2/"); }
                $this->view("signup/email");
                $this->view->meta->title = "E-Mail-Adresse angeben";
                if(\Helpers\Post::exists("ph_signup_email")){
                    $email = \Helpers\Post::get("ph_signup_email");
                    if(\Helpers\Check::email($email)){
                        if(!$creationService->existsEmail($email)){
                            $signup_data["email"] = \Helpers\Post::get("ph_signup_email");
                            \App::$router->redirect("/signup/".$actcode."/4/");
                        } else {
                            $this->view->v->form_error = "Diese E-Mail-Adresse wird bereits verwendet";
                        }
                    } else {
                        $this->view->v->form_error = "Bitte gib eine gültige E-Mail ein";
                    }
                }
            } else if($step == 2 && $signup_data["username"] != ""){
                if($signup_data["username"] == ""){ \App::$router->redirect("/signup/".$actcode."/1/"); }
                $step = 2;
                $this->view("signup/password");
                $this->view->meta->title = "Passwort wählen";
                if(\Helpers\Post::exists("ph_signup_password") && \Helpers\Post::exists("ph_signup_password_confirm")){
                    $signup_data["password"] = \Helpers\Post::get("ph_signup_password");
                    \App::$router->redirect("/signup/".$actcode."/3/");
                }
            } else if($step == 1){
                $this->view("signup/username");
                $this->view->meta->title = "Nutzername wählen";
                if(\Helpers\Post::exists("ph_signup_username")){
                    $username = \Helpers\Post::get("ph_signup_username");
                    if(!$creationService->existsName($username)){
                        $signup_data["username"] = $username;
                        \App::$router->redirect("/signup/".$actcode."/2/");
                    } else {
                        $this->view->v->form_error = "Nutzername ist vergeben";
                    }
                }
            } else {
                \App::$router->redirect("/signup/");
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