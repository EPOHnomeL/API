<?php

// Utilities class required for prosessing URL query
class Utils{

    // Key words that are required
   private const KEY_WORDS = [ "class", "func" ];

    // Properties
    private static $_objName = '';
    private static $_funcName = '';

    // Separates URL queries into key-value pairs
    static function separateURLQuery($urlQuery){

        // Seperate queries
        $urlQuery = explode('&', $urlQuery);
        $queryParams = array();

        foreach($urlQuery as $value){
            // Get key-value pairs
            $param = explode('=', $value);
            $queryParams[$param[0]] = $param[1];
        }
        return $queryParams;
    }

    // Sanitises URL queries
    static function sanitiseURLQuery($queryParams){
        foreach($queryParams as $key=>$value){
            // sanitise requests
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            $queryParams[$key] = $value;   
        }
        return $queryParams;
    }

    // Validates key-value pairs accourding to requirements
    static function validateURLQuery($queryParams){
        
        // TODO validate URL for class and func
        if(count($queryParams) < 2){
            Response::setResponse("Not enough parameters for 'class' and 'func'");
            return false;
        }

        // Find 'class' and 'func'
        foreach($queryParams as $key=>$value){
            if(!in_array($key, Utils::KEY_WORDS)){
                Response::setResponse("Cannot find url query parameter 'class' or 'func'");
                return false;
            }
        }

        // Find class as a file
        $classPath = './Classes/' . $queryParams['class'] . '.php';
        if(!file_exists($classPath)){
            Response::setResponse("Cannot find url query parameter 'class' file");
            return false;
        }

        // Include class the request asked for
        include $classPath;
        // See if class exists
        if(!class_exists($queryParams['class'])){
            Response::setResponse("Cannot find class");
            return false;
        }

        // Check if function exists
        $obj = new $queryParams['class']();
        $funcName = $queryParams['func'];
        if(!method_exists($obj,  $funcName)){
            Response::setResponse("Cannot find function in class");
            return false;
        }

        // Return the validation succeeded and change properties
        self::$_objName = $queryParams['class'];
        self::$_funcName = $queryParams['func'];
        return true;
    }   

    // Runs the function on the obj
    static function runURLQuery(){
        $obj = new self::$_objName();
        $funcName = self::$_funcName;
        $obj->$funcName(); 
    }    

    static function getUserDetails(){                   
        $json = file_get_contents('php://input');
        $user = json_decode($json, true);

        $username = array_key_exists('username', $user) ? Sql::sanitise($user['username']) : '';
        $password = array_key_exists('password', $user) ? Sql::sanitise($user['password']) : '';
        $email = array_key_exists('email', $user) ? Sql::sanitise($user['email']) : '';
        $role = array_key_exists('role', $user) ? Sql::sanitise($user['role']) : '';

        return [$username, $password, $email, $role];
    }
}