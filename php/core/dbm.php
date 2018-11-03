<?php 
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo 2018
     */

    namespace Core;

    /**
     * DataBase-Manager - Manages  multiple database connections at once
     */
    class DBM {

        /** Multidiemnsional array, which stres all added connections 
         * @var \PDO[] $repository */
        private static $repository = [];

        private static $main = "";

        /**
         * Adds a database to the manager.
         * 
         * @param String $identifier Identifier to access the connection after creation.
         * @param String[] $credentials Array with the credentials: Needed keys: 'host', 'database', 'username', 'password'
         */
        public static function add($identifier, $credentials){
            if(count(Self::$repository) < 1){ Self::$main = $identifier; }
            $connection = new \PDO('mysql:host='.$credentials["host"].';dbname='.$credentials["database"],$credentials["username"],$credentials["password"]);
            $connection->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
            //$connection->exec("set CHARSET UTF8");
            self::$repository[$identifier] = $connection;
        }

        /**
         * Checks if a connection with a specific identifier exists.
         * 
         * @param String $identifier Indentifier to search for.
         * 
         * @return Boolean Returns true if a connection exists.
         */
        public static function exists($identifier){
            if(!isset(self::$repository[$identifier])){ return false; }
            return true;
        }

        /**
         * Returns a connection from an identifier
         * 
         * @param String $identifier Indentifier of the connection
         * 
         * @return \PDO Returns a PDO connection if sucessfull.
         */
        public static function get($identifier){
            if(!self::exists($identifier)){ die("DBM - There is not DB with the identifier \"".$identifier."\""); }
            return self::$repository[$identifier];
        }

        /**
         * Set the main Database-Connection
         * 
         * @param String $identifier Identifier of the main database in the manager.
         * 
         */
        public static function setMain($identifier ){
            if(!in_array($identifier,Self::$repository)){ die("The Connection \"".$identifier."\" can't be set as main connection, because it doesn't exists"); }
            Self::$main = $identifier;
        }

        /**
         * Removes a connection
         * 
         * @param String $identifier Identifier of the connection to remove.
         * 
         */
        public static function remove($identifier){
            unset(self::$repository[$identifier]);
        }

        /**
         * Returns the main connection
         * 
         * @return PDO Returns the main connection of the manager.
         */
        public static function getMain(){
            return Self::get(Self::$main);
        }
        
    }

?>