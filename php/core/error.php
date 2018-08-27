<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo 2018
     */

    namespace Core;

    /**
     * Throwable custom exception class
     */
    class Error extends \Exception {

        public function __construct(...$args){
            parent::__construct(...$args);
        }

        /**
         * Returns an array with details [code,message,file,line] of the exception.
         * 
         * @return Mixed[] Returns an array with details of the exception.
         */
        public function getDetails(){
            $trace = $this->getTrace();
            $code = str_replace("\\","_",$trace[0]["class"])."_".$trace[0]["function"]."_[".implode("_",$trace[0]["args"])."]";
            $code = strtoupper($code);
            return array(
                "code" => $code,
                "dev" => $this->getMessage(),
                "file" => $this->getFile(),
                "line" => $this->getLine()
            );
        }

    }

?>