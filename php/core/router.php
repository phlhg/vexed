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
        public $repository = [];

        /** Url the router currently searches a route for. 
         * @var String  $url */
        public $url = "";

        /** Current route taken by the router.
         * @var Route $route */
        public $route = null;

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
        public function set($pattern, $action, $methodParams=[], $controllerParams=[]){
            $this->repository[] = new Route($pattern,$action,$methodParams,$controllerParams);
            return end($this->repository);
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
        public function find($url){
            $this->url = $url;
            $this->route = null;
            foreach($this->repository as $route){
                if($route->match($url)){
                    $this->route = $route;
                        $this->route->execute($url);
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
        public function run($url){
            try {
                $this->find($url);
            } catch( \Core\Error $e){
                $this->setRoute("Error/Exception",[$e->getDetails()]);
            }

            if($this->route == null){ $this->setRoute("Error/E404"); }
            return $this->route->controller->view->getRender();
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
        public function setRoute($action, $methodParams=[], $controllerParams=[]){
            $this->route = null;
            $this->route = new \Core\Route("",$action,$methodParams,$controllerParams);
            $this->route->execute($this->url);
            return true;
        }

        /**
         * Redirects the current request to another url
         * 
         * @todo add functionality
         */
        public function redirect($url){
            header("Location: ".$url);
        }

        public function reload(){
            header("Refresh:0");
        }


    }