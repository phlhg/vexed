<?php

    namespace App\Models\Post;

    class VOTE {

        const NEUTRAL = 0;
        const UP = 1;
        const DOWN = -1;
        
        public static function format($votes){
            if($votes >= 0){ return $votes."+"; }
            if($votes < 0){ return "-".abs($votes); }
        }

    }

?>