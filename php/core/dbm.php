<?php 

    namespace Core;

    class DBM {

        private static $repository = [];

        public static function add( String $shortname, Array $data){
            $connection = new \PDO('mysql:host='.$data["host"].';dbname='.$data["database"],$data["username"],$data["password"]);
            $connection->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
            $connection->exec("set CHARSET UTF8");
            self::$repository[$shortname] = $connection;
        }

        public static function exists( String $shortname){
            if(!isset(self::$repository[$shortname])){ return false; }
            return true;
        }

        public static function get( String $shortname){
            if(!self::exists($shortname)){ die("DBM - There is not DB with the shortname \"".$shortname."\""); }
            return self::$repository[$shortname];
        }

        public static function remove( String $shortname){
            unset(self::$repository[$shortname]);
        }

    }

?>