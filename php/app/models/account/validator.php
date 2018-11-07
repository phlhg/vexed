<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Account;

    /**
     * Validates new data in realation to the user.
     */
    class Validator {

        /** Holds the userservice.
         * @var UserService $us */
        private $us;

        /**
         * Validates data for an account.
         */
        public function __construct(){
            $this->us = new \App\Models\Storage\Sql\UserService();
        }

        /* EMAIL */
        /**
         * Checks if the string given in $email is an email.
         * @param String $email A String to check.
         * @return Boolean Returns true if $email is an email else returns false.
         */
        public function isEmail($email){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){ return true; }
            return false;
        }

        /**
         * Checks if the email given in $email is already linked to an account.
         * @param String $email A valid email-adress to check.
         * @return Boolean Returns true if $email is linked to an account.
         */
        public function usedEmail($email){
            return $this->us->existsEmail($email);
        }

        /**
         * Checks if the mail-provider exists. (by checkdnsrr)
         * @param String $email A valid email-adress to check.
         * @return Boolean Returns true if the provider was found.
         */
        public function existsEmailProvider($email){
            $ex = explode("@",$email);
            if(count($ex) < 2){ return false; }
            return checkdnsrr($ex[1]);
        }

        /* NAME */
        /**
         * Checks if $name matches the criteria of a username.
         * @param String $name A Possible username.
         * @return Boolean Returns true if the username is possible.
         */
        public function isUsername($name){    
            if(preg_match('/^[A-Za-z0-9\._\-$]{3,25}$/i',$name) === 1){ return true; }
            return false;
        }

        /**
         * Checks if a username is already used by an account.
         * @param String $name A Username to check.
         * @return Boolean Returns true if the username is linked to an account.
         */
        public function usedUsername($name){
            return $this->us->existsName($name);
        }
    }

?>