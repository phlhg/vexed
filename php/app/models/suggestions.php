<?php 
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models;

    class Suggestions extends \Core\Model {
        
        public static function profiles($max = 4){
            if(count(\App::$client->subscriptions) < 1){ return Self::popularUsers($max); }
            return Self::knownUsers($max);
        }

        public static function knownUsers($max = 4){
            $suggested = [];
            $weight = [];

            foreach(\App::$client->subscriptions as $id){
                $sub = new \App\Models\Account\User($id);
                foreach($sub->subscriptions as $oid){
                    if($oid != \App::$client->id && !in_array($oid,\App::$client->subscriptions)){
                        if(isset($weight[$oid])){
                            $weight[$oid]++;
                        } else {
                            $weight[$oid] = 1;
                        }
                    }
                }
            }

            asort($weight);

            foreach($weight as $id => $weight){
                $suggested[] = $id;
            }

            return array_slice($suggested,0,$max);
        }

        public static function popularUsers($max = 4){
            $rs = new \App\Models\Storage\Sql\RelationService();
            return $rs->getPopular($max);
        }

        public static function popularPosts($max = 4){
            $vs = new \App\Models\Storage\Sql\VoteService();
            return $vs->getPopular($max);
        }

    }

?>