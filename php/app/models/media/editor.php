<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Media;

    /**
     * Edits an image resource
     */
    class Editor {

        /** Image rescource 
         * @var Resource $image */
        public $image = null;

        /** The current width of the image
         * @var Int $width */
        public $width = 0;

        /** The current height of the image
         * @var Int $height */
        public $height = 0;

        /** Indicates if an error has occured
         * @var Boolean $error */
        public $error = false;

        /**
         * Edits an image resource
         * @param String $path The path to the image file
         */
        public function __construct($image){
            $this->image = $image;
            $this->width = imagesx($this->image);
            $this->height = imagesy($this->image);
        }

        /**
         * Resizes the bigger site to $max.
         * @param Int $max Maxial dimension in pixels
         * @return Boolean Returns true if the image was resized
         */
        public function maxDim($max){
            if($this->width <= $max && $this->height <= $max){ return true; }
            if($this->width > $this->height){ return $this->resize($max,null); }
            return $this->resize(null,$max);
        }

        /**
         * Resizes the smaller site to $min.
         * @param Int $min Minimal dimension in pixels
         * @return Boolean Returns true if the image was resized
         */
        public function minDim($min){
            if($this->width <= $min && $this->height <= $min){ return true; }
            if($this->width < $this->height){ return $this->resize($min,null); }
            return $this->resize(null,$min);
        }

        /** Crops the image with the orgin at the center of the image. 
         * @param Int $width New width in pixels
         * @param Int $height New height in pixels
         * @return Boolean Returns true if the image was cropped.
         */
        public function cropCenter($width,$height){
            $top = ($this->height - $height) / 2;
            $left = ($this->width - $width) / 2;
            if($top < 0 || $left < 0){ return false; }
            return $this->crop($left,$top,$width,$height);
        }

        /**
         * Resizes the image with the given values.
         * @param Int $width New width in pixels
         * @param Int $height New height in pixels
         * @param Boolean $keepAspectRatio True if the ratio should be preserved
         * @return Boolean Returns true if the image was resized
         */
        public function resize($width,$height,$keepAspectRatio = true){
            if($keepAspectRatio){
                if(empty($height)){ $height = $width / ($this->width / $this->height); }
                if(empty($width)){ $width = $height * ($this->width / $this->height); }
            }
            $image = imagescale($this->image,$width,$height);
            if(!$image){ return $this->error(); }
            $this->image = $image;
            $this->width = $width;
            $this->height = $height;
        }

        /**
         * Crops the image.
         * @param Int $left The margin from the left.
         * @param Int $top The margin from the top.
         * @param Int $width New width in pixels
         * @param Int $height New height in pixels
         * @return Boolean Returns true if the image was rcropped
         */
        public function crop($left,$top,$width,$height){
            $image = imagecrop($this->image, ['x' => $left, 'y' => $top, 'width' => $width, 'height' => $height]);
            if(!$image){ return $this->error(); }
            $this->image = $image;
            $this->width = $width;
            $this->height = $height;
        }

        /**
         * Triggers an error.
         * @return Boolean Returns always false.
         */
        protected function error(){
            $this->error = true;
            return false;
        }
    }