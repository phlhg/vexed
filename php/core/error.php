<?php

    namespace Core;

    class Error extends \Exception {

        public function __construct(...$args){
            parent::__construct(...$args);
        }

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