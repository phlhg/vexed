<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Account;
    use \Core\Config;
    use \Helpers\Email;
    use \Helpers\Password;

    /**
     * Handles the process of creating a new Account.
     */
    class Creator extends \Core\Model {

        /** Holds the returned id.
         * @var Int $id */
        public $id = -1;

        /** Holds the supplied name.
         * @var String $name */
        private $name = "";

        /** Holds the supplied hashed (!) password.
         * @var String $password */
        private $password = "";

        /** Holds the supplied email
         * @var String $email */
        private $email = "";

        /** Holds the generated security code */
        private $security = "";

        /** Holds wether there is an error or not.
         * @var Boolean $error */
        public $error = false;

        /** Human readable error message 
         * @var String $errorMsg */
        public $errorMsg = "";

        /** The userservice for the current class 
         * @var UserService $us */
        private $us;

        /** The validator for new account properties 
         * @var Validator $validator*/
        private $validator;
        
        /**
         * Handles the process of creating a new account.
         */
        public function __construct(){
            $this->userService = new \App\Models\Storage\Sql\UserService();
            $this->validator = new \App\Models\Account\Validator();
        }

        /**
         * Start process of creation.
         * @return Boolean Returns True if the creation was successfull otherwise returns False.
         */
        public function create($name,$password,$email){
            $this->name = $name;
            $this->email = $email;
            $this->password = \Helpers\Password::hash($password);
            return $this->checkInput();
        }

        /**
         * Procedure of creation - Checks the supplied information.
         * @return Boolean Returns False if an error occurs.
         */
        private function checkInput(){
            if(!$this->validator->isUsername($this->name)){ return $this->error("Nutzername enthält ungültige Zeichen"); }
            if(!$this->validator->isEmail($this->email)){ return $this->error("Ungültige E-Mail-Adresse [1]"); }
            if(!$this->validator->existsEmailProvider($this->email)){ return $this->error("Ungültige E-Mail-Adresse [2]"); }
            if($this->validator->usedUsername($this->name)){ return $this->error("Dieser Name ist leider vergeben"); }
            if($this->validator->usedEmail($this->email)){ return $this->error("Diese E-Mail-Adresse ist bereits mit einem Konto verknüpft"); }
            return $this->addToDb();
        }

        /**
         * Procedure of creation - Adds an entry to the database.
         * @return Boolean Returns False if an error occurs.
         */
        private function addToDb(){
            $this->security = generateSecurityCode(12);
            $this->id = $this->userService->create($this->name,$this->password,$this->email,$this->security);
            if($this->id < 0){ return $this->error("Nutzer konnte nicht erstellt werden"); }
            return $this->sendEmail();
        }

        /**
         * Procedure of creation - Sends an E-Mail with a confirmation link to the user.
         * @return Boolean Returns False if an error occurs.
         */
        private function sendEmail(){
            $mail = new \Helpers\Email($this->email,"Dein Account wurde erstellt");
            $mail->add('<h2>Account erstellt</h2>
            <p>
                Hallo <strong>'.strtolower($this->name).'</strong><br/>
                <br/>
                Um deine Registrierung abzuschliessen, benutze bitte den folgenden Aktivierungslink<br/>
                <br/>
                <strong style="font-size: 25px;"><a href="http://'.\Core\Config::get("application_domain").'/signup/confirm/'.$this->id.'/'.$this->security.'/">'.\Core\Config::get("application_domain").'/signup/confirm/'.$this->id.'/'.$this->security.'/</a></strong><br/>
                <br/>
                <br/>
                <h3>Keinen Account erstellt?</h3>
                Wenn du diesen Account nicht erstellt hast, kannst du dieses Mail ignorieren. Unbestätigte Accounts werden in der Regel nach 7 Tagen gelöscht.
            </p>');
            if(!$mail->send()){ 
                $this->userService->delete($this->id); 
                return $this->error("Leider ist ein Problem mit dem Mail-Service aufgetreten - Versuche es erneut.");
            };
            $info = new \Helpers\Email("info@phlhg.ch",$this->name." ist VEXED beigetreten");
            $info->add('<h2>'.$this->name.'</h2>');
            $info->add('<p>ist am '.date("d.m.Y (H:i)").' beigetreten</p>');
            $info->send();
            return true;
        }

        /**
         * Logs an error. (Use return to terminate after the error)
         * @param String $msg Human readable error information.
         * @return Boolean Returns always false
         */
        private function error($msg){
            $this->error = true;
            $this->errorMsg = $msg;
            return false;
        }

    }

?>