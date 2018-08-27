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
         * @param Mixed[]   $methodParams Array of parameters for the method in the controller
         * @param Mixed[]   $controllerParams Array of parameters for the constructor of the controller
         */
        public static function set(String $pattern, String $action, Array $methodParams=[], Array $controllerParams=[]){
            self::$repository[] = new Route($pattern,$action,$methodParams,$controllerParams);
            return end(self::$repository);
        }

        /**
         * Find a matching route for the supplied url
         * 
         * @since 1.0.0
         * 
         * @param String $url Url to find a Route with
         * 
         * @return Boolean Returns true if a route has matched
         */
        public static function find(String $url){
            self::$route = null;
            foreach(self::$repository as $route){
                if($route->match($url)){
                    self::$route = $route;
                        self::$route->execute();
                    return true;
                }
            }
            return false;
        }

        /**
         * Runs the router and returns the content of the matching Route.
         * 
         * @param String $url Url the route has to match.
         * 
         * @return String Returns the content of the matching route
         */
        public static function run(String $url){
            try {
                self::find($url);
            } catch( \Core\Error $e){
                self::setRoute("Error/Exception",[$e->getDetails()]);
            }

            if(self::$route == null){ self::setRoute("Error/E404"); }
            return self::$route->controller->view->getRender();
        }

        /**
         * Forces a new route for the current request
         * 
         * @since 1.0.0
         * 
         * @param String    $action Action to be executed. Actions are always constructed like "controller/method"
         * @param Mixed[]   $methodParams Array of parameters for the method in the controller
         * @param Mixed[]   $controllerParams Array of parameters for the constructor of the controller
         */
        public static function setRoute(String $action, Array $methodParams=[], Array $controllerParams=[]){
            self::$route = new \Core\Route("",$action,$methodParams,$controllerParams);
            self::$route->execute();
            return true;
        }

        /**
         * Redirects the current request to another url
         * 
         * @todo add functionality
         */
        public static function redirect(String $url){

        }


    }