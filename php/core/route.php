<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo 2018
     * @version 1.0.0
     */

    namespace Core;
    
    /** Stores information about a route and executes the action */
    class Route {

        /** Action to be executed. Actions are always constructed like "controller/method".
         * @var String $action */
        public $action = "";

        /** Pattern url has to match to be executed.
         *  Variables are added by barckets like this: /url/{name}/{id}/.
         * @var String $pattern */
        public $pattern = "";

        /** Parameters used in the constructor of the controller
         * @var Mixed[] $parameters */
        public $parameters = [];

        /** Stores the name, the RegEx and the value of each variable.
         * @var Array[] $variables */
        public $variables = [];

        /**
         * Constructs a possible route withe the supplied parameters
         * 
         * @since 1.0.0
         * 
         * @param String $pattern Pattern url has to match to be executed. Variables are added by brackets "{name}"
         * @param String $action Action to be executed. Actions are always constructed like "controller/method"
         * @param Mixed[] $parameters Parameters to be used in the constructor of the controller
         * 
         * @return Self Returns itself for chaining
         */

        public function __construct(String $pattern, String $action, Mixed $parameters=null){
            $this->pattern = $pattern;
            $this->$action = $action;
            $this->$parameters = ($parameters !== null ? $parameters : []);

            $this->extractVariables();

            return $this;
        }


        /**
         * Returns a regular expression for the pattern and the variables supplied
         * 
         * @since 1.0.0
         * 
         * @return String Regular expression of the pattern 
         */
        public function bakeRegex(){
            $regex = str_replace("/","\/",$this->pattern);
            foreach($this->variables as $variable){
                $regex = str_replace("{".$variable["name"]."}",'(?P<'.$variable["name"].'>'.$variable["regex"].')',$regex);
            }

            return '/^'.$regex.'$/i';
        }

        /**
         * Checks if the route matches the given url.
         * 
         * @since 1.0.0
         * 
         * @param String $url The url to check with the pattern.
         * 
         * @return Boolean Returns true if the route matches.
         */
        public function match(String $url){
            echo $url."<br/>";
            echo htmlspecialchars($this->bakeRegex())."<br/>";
            $preg = preg_match($this->bakeRegex(),$url,$matches);
            print_r($matches);
            return $preg;
        }

        /**
         * Extracts the variables from the pattern and stores them in @variables
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
         * Modifies the regular expressions the variables have to match
         * 
         * @since 1.0.0
         * 
         * @param String[] $variables Array is structured like: ["var" => "regex", "var2" => "regex2" ]. (Regular expression is supplied without wrapping slashes and modifiers!)
         *
         * @return Self Returns itself for chaining
         */
        public function where(Array $variables){
            foreach($variables as $name => $regex){
                $this->setVariableRegex($name,$this->type2Regex($regex));
            }

            return $this;
        }

        /**
         * If the supplied type exists, its regular expression is returned. Otherwise it returns the supplied $type.
         * 
         * @param String $type Type possible values are: int | string
         * 
         * @todo add more types.
         * 
         * @return String Returns a regular expression if a type was found, otherwise the supplied $type parameter.
         */
        public function type2Regex(String $type){
            $regex = $type;
            switch($type){
                case 'int':
                    $regex = '\d+';
                    break;
                case 'string':
                    $regex = '[\w\s_]+';
                    break;
            }
            return $regex;
        }

        //Variable Management

        /**
         * Creates a new variable
         * 
         * @since 1.0.0
         * 
         * @param String $name Name of the new variable
         */
        public function addVariable(String $name){
            $this->variables[urlencode($name)]["name"] = $name;
            $this->variables[urlencode($name)]["regex"] = '.+';
            $this->variables[urlencode($name)]["value"] = null;
        }

        /**
         * Sets a new regular expression for a specific variable
         * 
         * @since 1.0.0
         * 
         * @param String $name Name of the affected variable
         * @param String $regex Regular expression without wrapping slashes and modifiers
         */
        public function setVariableRegex(String $name, String $regex){
            $this->variables[urlencode($name)]["regex"] = $regex;
        }

        /**
         * Checks if a variable exists
         * 
         * @param String $name Name of the variable
         * 
         * @return Boolean Return true if the variable exists
         */
        public function existsVariable(String $name){
            return isset($this->variables[$name]);
        }
        
    }