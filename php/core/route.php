<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo 2018
     * @version 1.0.0
     */

    namespace Core;
    
    /** Stores information about a route and executes the action */
    class Route {

        /** Instance of the initialized controller 
         * @var Controller $controller */
        public $controller = null;

        /** Name of the controller that handles the request.
         * @var String $nameController */
        public $nameController = "";

        /** Name of the method in the controller that handles the request.
         * @var String $nameMethod */
        public $nameMethod = "";

        /** Pattern the url has to match for the route to be selected.
         *  Variables are added by barckets like: /url/{name}/{id}/.
         * @var String $pattern */
        public $pattern = "";

        /** Parameters of the method executed in the controller.
         * @var Mixed[] $methodParams */
        public $methodParams = [];

        /** Parameters for the construction of the controller.
         * @var Mixed[] $controllerParams */
        public $controllerParams = [];

        /** Stores the name, the RegEx and the value of each variable in a multidimensional array.
         * @var Array[] $variables */
        public $variables = [];

        /**
         * Constructs a new route with the the supplied parameters.
         * 
         * @since 1.0.0
         * 
         * @param String $pattern Pattern the url has to match for the route to be selected. Variables are added by brackets: "{name}".
         * @param String $action Action to be executed. Actions are structured: "controller/method"
         * @param Mixed[] $methodParams Parameters for method executed in the controller
         * @param Mixed[] $controllerParams Parameters for the construction of the controller
         * 
         * @return Self Returns itself for chaining
         */

        public function __construct($pattern, $action, $methodParams=[], $controllerParams=[]){
            $this->pattern = $pattern;
            $action = explode("/",$action);
            if(count($action) < 2){ throw new Error("Invalid action supplied"); }
            $this->nameController = $action[0];
            $this->nameMethod = $action[1];
            $this->methodParams = $methodParams;
            $this->controllerParams = $controllerParams;

            $this->extractVariables();

            return $this;
        }


        /**
         * Returns a regular expression for the pattern and the variables of the route.
         * 
         * @since 1.0.0
         * 
         * @return String Regular expression of the pattern 
         */
        public function getRegex(){
            $regex = str_replace("/","\/",$this->pattern);
            foreach($this->variables as $variable){
                $regex = str_replace("{".$variable["name"]."}",'(?P<'.urlencode($variable["name"]).'>'.$variable["regex"].')',$regex);
            }

            return '/^'.$regex.'$/i';
        }

        /**
         * Checks if the route fits the given url.
         * 
         * @since 1.0.0
         * 
         * @param String $url Url that gets compared with the pattern.
         * 
         * @return Boolean Returns true if the route matches.
         */
        public function match($url){
            $preg = preg_match($this->getRegex(),$url,$matches);
            return $preg;
        }

        /**
         * Executes the route by initializing the controller and running the method supplied in "action".
         * 
         * @param String $url Variables get extracted from this url
         * 
         */
        public function execute($url){
            $this->extractValues($url);
            $nameController = '\App\Controllers\\'.$this->nameController;
            $nameMethod = $this->nameMethod;
            $_URL = $this->getValues();
        
            $methodParams = $this->methodParams;
            array_unshift($methodParams,$_URL);

            $this->controller = new $nameController($this->controllerParams);
            call_user_func_array([$this->controller,$nameMethod],$methodParams);
            $this->controller->view->render();
        }

        /**
         * Extracts the variables from the pattern and stores them as an array in the "variable"-property
         * 
         * @since 1.0.0
         * 
         */
        public function extractVariables(){
            preg_match_all('/{(\w+)}/i',$this->pattern,$matches);
            $variables = $matches[1];
            foreach($variables as $variable){
                $this->addVariable($variable);
            }
        }

        /**
         * @todo
         */
        public function extractValues($url){
            preg_match($this->getRegex(),$url,$matches);
            foreach($this->variables as $variable){
                $name = urlencode($variable["name"]);
                if(!empty($matches[$name])){
                    $this->setVariable($variable["name"],$matches[$name]);
                }
            }
        }

        /**
         * @todo
         */
        public function getValues(){
            $return = array();
            foreach($this->variables as $variable){
                $return[$variable["name"]] = $variable["value"];
            }
            return $return;
        }

        /**
         * Modifies the regular expressions the variables have to match
         * 
         * @since 1.0.0
         * 
         * @param String[] $settings Modifies the RegEx the variables have to match. Ex: ["var" => "regex", "var2" => "regex2" ]. (Regular expression is supplied without wrapping slashes and modifiers!)
         *
         * @return Self Returns itself for chaining
         */
        public function where($settings){
            foreach($settings as $name => $regex){
                $this->setVariableRegex($name,$this->type2Regex($regex));
            }

            return $this;
        }

        /**
         * If the supplied type exists, its regular expression is returned. Otherwise it returns the supplied $type.
         * 
         * @param String $type Possible values for type are: int | string | nickname
         * 
         * @todo add more types.
         * 
         * @return String Returns a regular expression if a type was found, otherwise the supplied $type parameter.
         */
        public function type2Regex($type){
            $regex = $type;
            switch($type){
                case 'int':
                    $regex = '\d+';
                    break;
                case 'string':
                    $regex = '[\w\s_]+';
                    break;
                case 'nickname':
                    $regex = '[A-Za-z0-9\._\-$]+';
                    break;
            }
            return $regex;
        }

        //Variable Management

        /**
         * Creates a new variable for the pattern
         * 
         * @since 1.0.0
         * 
         * @param String $name Name of the new variable
         */
        public function addVariable($name){
            $this->variables[urlencode($name)]["name"] = $name;
            $this->variables[urlencode($name)]["regex"] = '.+';
            $this->variables[urlencode($name)]["value"] = null;
        }

        /**
         * @todo
         */
        public function setVariable($name, $value){
            $this->variables[urlencode($name)]["value"] = $value;
        }

        /**
         * Sets a new regular expression for a specific variable in the pattern
         * 
         * @since 1.0.0
         * 
         * @param String $name Name of the affected variable
         * @param String $regex New regular expression (Without wrapping slashes and modifiers!)
         */
        public function setVariableRegex($name, $regex){
            $this->variables[urlencode($name)]["regex"] = $regex;
        }

        /**
         * Checks if a variable exists in the list of variables
         * 
         * @param String $name Name of the variable
         * 
         * @return Boolean Returns true if the variable exists
         */
        public function existsVariable($name){
            return isset($this->variables[$name]);
        }
        
    }