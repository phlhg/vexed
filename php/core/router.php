<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo 2018
     * @version 1.0.0
     **/

    namespace Core;

    /**
     * Registers routes and finds a fitting route for a request
     */
    class Router {
        /** Contains all registered routes in an array
         * @var Route[] $repository */
        public static $repository = [];

        /** Url the router currently searches a route for. 
         * @var String  $url */
        public static $url = [];

        /** Current route taken by the router.
         * @var Route $route */
        public static $route = null;

        /**
         * Sets a route for a pattern and stores it in the repository
         * 
         * @since 1.0.0
         * 
         * @param String    $pattern Pattern url has to match to be executed. 
         *  Variables are added with brackets like this: /url/{name}/{id}/
         * @param String    $action Action to be executed. Actions are always constructed like "controller/method"
         * @param Mixed[]   $parameters Parameters to be used in the constructor of the controller
         * 
         */
        public static function set(String $pattern, String $action, Mixed $parameters=null){
            $newRoute = new Route($pattern,$action,$parameters);
            self::$repository[] = $newRoute;
            return $newRoute;
        }

        /**
         * 
         */
        public static function find(String $url){
            foreach(self::$repository as $route){


            }
        }

        public static function run(String $url){

        }


    }