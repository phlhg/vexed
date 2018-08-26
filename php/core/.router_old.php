<?php

    namespace Core;

    /**
     * Registers and finds a fitting route for a request
     * 
     * @author Philippe Hugo <info@phlhg.ch>
     * @version 1.0
     * 
     * @var Closure $action Stores the function to execute
     * @var Array $params Defines the REGEX of each parameter given in $pattern
     * @var String $pattern Defines the patter the request has to match
     * 
     * @var Array $repository Contains all instances of the class
     */

    class Router {

        public $action = '';
        public $pattern = '';
        public $urlParams = [];
        public $parameters = [];

        public static $repository = [];
        public static $dbconnection = null;

        /**
         * Constructs a new instance of the class
         * 
         * @since 1.0.0
         * 
         * @param String $action Defines which controller and method the route uses
         * @param String $pattern Defines the patter the request has to match
         * @param Mixed[] $parameters Defines parameters for the construction of the controller
         * 
         * @return Self Return itself for chaining
         */

        public function __construct(String $pattern, String $action, Array $parameters = []){
            $this->action = $action;
            $this->pattern = $pattern;
            $this->parameters = $parameters;

            self::$repository[] = $this;

            return $this;
        }

        /**
         * Checks if the Route matches the given URL
         * 
         * @since 1.0.0
         * 
         * @param String $url Defines the url to match
         * @return Bool Retuns true if the url matches and false if it doesn't
         */

        public function matches(String $url){
            $regex = '/^'.preg_replace('/{([\w]+)}/i','(?P<$1>[^\/]+)',str_replace("/","\/",$this->pattern)).'$/i';
            
            if(!@preg_match($regex,$url,$matches)){ return false; }

            foreach ($this->urlParams as $param => $data){
                if(!@preg_match($data["pattern"],$matches[$param])){ return false; }
            }

            return true;
        }

        /**
         * Shows a 404-Page
         * 
         * @since 1.0.0
         * 
         */

        public static function notFound(){
            $controller = new \App\Controllers\Error();
            $controller->E404();
            return $controller->view->render();
        }

        /**
         * Runs the action if the route is taken
         * 
         * @since 1.0.0
         * 
         * @param String $url Defines the url to extract the parameters from
         * @return Mixed Retuns the return value of the $this->action function
         */

        public function execute(String $url){
            $matches = $this->getParametersFromUrl($url);
            print_r($matches);
            $action = explode("/",$this->action);
            $controllerName = '\App\Controllers\\'.$action[0];
            $methodName = $action[1];
            try {
                $this->controller = new $controllerName();
                $this->controller->$methodName(...$this->parameters);
                return $this->controller->view->render();
            } catch( \Core\Error $e){
                return Self::REDIRECT("Error/Exception",[$e->getDetails()]);
            } catch( Exception $e){
                return $e->getInfo();
            }
        }



        public function getParametersFromUrl($url){
            $matches = [];
            $regex = '/'.preg_replace('/{[\w]+}/i','([^\/]+)',str_replace("/","\/",$this->pattern)).'/i';
            @preg_match($regex,$url,$matches);
            if(count($matches) > 0){ array_shift($matches); }
            return $matches;
        }

        /**
         * Searches for a matching route and handles execution
         * 
         * @since 1.0.0
         * 
         * @param String $url Defines the url the route needs to match
         * @return String Returns the rendered content of the matching Route
         */

        public static function run(String $url){
            $route = self::find($url);
            if($route == false){ return self::notFound(); }
            return $route->execute($url);
        }

        /**
         * Defines patterns parameters have to match
         * 
         * @since 1.0.0
         * 
         * @param Array|String $params Contains parameter-names as index and parameter-patterns as value
         * @return Self Returns itself to allow chaining
         */

        public function where($params){
            foreach($params as $param => $pattern){
                if(@preg_match($pattern, null) === false){
                    switch($pattern){
                        case 'int':
                            $res = '/^\d+$/i';
                            break;
                        case 'string':
                            $res = '/^[\w\s_]+$/i';
                            break;
                        default:
                            $res = '/^.+$/i';
                    }
                    $this->urlParams[$param]["pattern"] = $res;
                } else {
                    $this->urlParams[$param]["pattern"] = $pattern;
                }
            }
        }

        /**
         * Finds the matching Route to take
         * 
         * @since 1.0.0
         *  
         * @param String $url Defines a url to look for in the route repository
         * @return Bool|Self Returns the matching route or false if no match was found
         */

        public static function find($url){
            $found = -1;
            $amount = count(self::$repository);
            $i = 0;
            while($found < 0 && $i < $amount){
                if(self::$repository[$i]->matches($url)){ 
                    $found = $i;
                }
                $i++;
            }

            if($found < 0){ return false; }

            return self::$repository[$found];
        }
        
        /**
         * Defines the action for a Request (Does the Same as the constructor)
         * 
         * @since 1.0.0
         * 
         * @param String $action Defines which controller and method the route uses
         * @param String $pattern Defines the patter the request has to match
         * @param Array $parameters Defines the parameters for the construction of the controller
         * 
         * @return Self Returns the created Route for manipulation
         */

        public static function SET(String $pattern, String $action, Array $parameters = []){
            return new Self($pattern,$action);
        }

        public static function REDIRECT(String $action,Array $parameters = []){
            $route = new Self("", $action, $parameters);
            return $route->execute("");
        }

    }
?>