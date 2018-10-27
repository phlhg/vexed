<?php 
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */
    namespace App\Models\Ajax;

    /** Defines enumarators for the response of ajax */
    class Response {
        const OK = 0;
        const DENIED = 1;
        const WARNING = 2;
        const ERROR = 3;
    }

?>