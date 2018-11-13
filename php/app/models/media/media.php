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

        /**
         * Loads, updates and deletes media
         */
        public function __construct($id){
            $this->ms = new \App\Models\Storage\Sql\MediaService();
        }
    }

?>
