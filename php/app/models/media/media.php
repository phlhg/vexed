<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Media;

    /**
     * Loads, updates and deletes media.
     */
    class Media {

        /** Holds the MediaService
          * @var MediaService $ms */
        public $ms;

        public $id = -1; 

        public $exists = false;

        public $post = -1;

        public $type = "";

        public $size = 0;

        public $width = 0;
        
        public $height = 0;

        public $user = -1;

        public $time = 0;

        /**
         * Loads, updates and deletes media
         */
        public function __construct($id){
            $this->ms = new \App\Models\Storage\Sql\MediaService();
            $this->id = $id;
            $this->load();
        }
        
        public function load(){
            $data = $this->ms->get($this->id);
            if(!$data){ return false;}
            $this->id = intval($data["id"]);
            $this->exists = true;
            $this->post = intval($data["post"]);
            $this->type = $data["type"];
            $this->size = intval($data["size"]);
            $this->width = intval($data["width"]);
            $this->height = intval($data["height"]);
            $this->user = intval($data["user"]);
            $this->time = intval($data["time"]);
            $this->path = "/img/media/".$this->id."/";
            return true;
        }

        public function delete(){
            if(!(__CLIENT()->id == $this->user || __CLIENT()->admin == true)){ return false; }
            $dir = \Core\Config::get("storage_root")."/media/";
            unlink($dir.$this->id.".".$this->type);
            unlink($dir.$this->id."_tiny.".$this->type);
            $this->ms->delete($this->id);
        }


    }

?>
