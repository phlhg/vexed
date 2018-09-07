<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo 2018
     */

    /**
     * Generates a securitycode with a specified lenght.
     * @param Int $lenght The amount of chars in the code
     * @return String Retuns a Random Code as a String
     */
    function generateSecurityCode(Int $lenght){
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $chars_len = strlen($chars)-1;
        $code = "";
        for($i = 0; $i < $lenght; $i++){
            $index = rand(0,$chars_len);
            $code .= substr($chars,$index,1);
        }
        return $code;
    }

    /**
     * Formats a security code to be better readable
     * @param String $code The security code
     * @return String A security code with spaces
     */
    function formatSecurityCode(String $code){
        $return = preg_replace('([\dA-Z]{3})','$0 ',$code);
        return $return;
    }
?>