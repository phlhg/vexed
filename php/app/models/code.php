<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models;

    class Code extends \Core\Model {

        private $codeService = null;

        public $exists = false;

        public $id = -1;

        public $description = "";

        public $type = "";

        public $code = "";

        public $uses = 1;

        public $uses_init = 1;

        public $expires = 0;

        public function __construct($id){
            $this->codeService = new \App\Models\DB\CodeService();
            $data = $this->codeService->get($id);
            if($data == false){ return; }
            $this->exists = true;
            $this->id = $data["id"];
            $this->description = $data["description"];
            $this->type = $data["type"];
            $this->code = $data["code"];
            $this->uses = $data["uses"];
            $this->uses_init = $data["uses_init"];
            $this->expires = $data["expires"];
        }

        public static function exists($type,$code){
            $codeService = new \App\Models\DB\CodeService();
            return $codeService->exists($type,$code);
        }

        public static function use($type,$code){
            $codeService = new \App\Models\DB\CodeService();
            return $codeService->use($type,$code);
        }
        

    }

?>