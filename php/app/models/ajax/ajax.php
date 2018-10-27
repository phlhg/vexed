<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Ajax;

    class Ajax extends \Core\Model {

        public $rspn = \App\Models\Ajax\Response::OK;
        public $info = "";
        public $value = null;

        public $f = "";

        function __construct(){
            
        }

        public function bake(){
            //Standartisiertes Layout!
            //array "type" "info" "value"
            $json = array(
                "rspn" => $this->rspn,
                "info" => $this->info,
                "value" => $this->value
            );
            return json_encode($json);
        }
    }

?>