<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Account;
    use \Core\Config;
    use \Helpers\Email;
    use \Helpers\Hash;

    /**
     * Handles the process of creating a new Account.
     */
    class CreatorService extends \Core\Model {

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

        /** The userservice for the current class */
        private $userService = null;
        
        /**
         * Handles the process of creating a new account.
         * @param String $name The nickname for the new account.
         * @param String $password The unhashed password to login with.
         * @param String $email The email to get in touch with the user.
         */
        public function __construct($name,$password,$email){
            $this->userService = new \App\Models\DB\UserService();
            $this->name = $name;
            $this->email = $email;
            $this->password = \Helpers\Hash::ph1($password);
        }

        /**
         * Start process of creation.
         * @return Boolean Returns True if the creation was successfull otherwise returns False.
         */
        public function go(){
            return $this->checkInput();
        }

        /**
         * Procedure of creation - Checks the supplied information.
         * @return Boolean Returns False if an error occurs.
         */
        private function checkInput(){
            if(!\Helpers\Check::username($this->name)){ return $this->error("Nutzername enthält ungültige Zeichen"); }
            if(!\Helpers\Check::email($this->email)){ return $this->error("Ungültige E-Mail-Adresse [1]"); }
            if(!\Helpers\Check::emailProvider($this->email)){ return $this->error("Ungültige E-Mail-Adresse [2]"); }
            if($this->userService->existsName($this->name)){ return $this->error("Dieser Name ist leider vergeben"); }
            if($this->userService->existsEmail($this->email)){ return $this->error("Diese E-Mail-Adresse ist bereits mit einem Konto verknüpft"); }
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
                <strong style="font-size: 25px;"><a href="http://antisocial.vs/signup/confirm/'.$this->id.'/'.$this->security.'/">antisocial.vs/signup/confirm/'.$this->id.'/'.$this->security.'/</a></strong><br/>
                <br/>
                <br/>
                <h3>Keinen Account erstellt?</h3>
                Wenn du diesen Account nicht erstellt hast, kannst du dieses Mail ignorieren. Unbestätigte Accounts werden in der Regel nach 7 Tagen gelöscht.
            </p>');
            if(!$mail->send()){ 
                $this->userService->delete($this->id);
                return $this->error("Leider ist ein Problem mit dem Mail-Service aufgetreten - Versuche es erneut.");
            };
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