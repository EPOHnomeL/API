<?php

class Utils{

    // Key words that are required
    const KEY_WORDS = [ "class", "func" ];

    public static function separateURLQuery($urlQuery){

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

    public static function sanitiseURLQuery($queryParams){
        foreach($queryParams as $key=>$value){
            // sanitise requests
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            $queryParams[$key] = $value;   
        }
        return $queryParams;
    }

    public static function validateURLQuery($queryParams){
        
        // TODO validate URL for class and func
        if(count($queryParams) < 2){
            Response::setResponse(false, "Not enough parameters for 'class' and 'func'");
            return;
        }

        // Find 'class' and 'func'
        foreach($queryParams as $key=>$value){
            if(!in_array($key, Utils::KEY_WORDS)){
                Response::setResponse(false, "Cannot find url query parameter 'class' or 'func'");
                return;
            }
        }

        // Find class as a
        $classPath = './Classes/' . $queryParams['class'] . '.php';
        if(!file_exists($classPath)){
            Response::setResponse(false, "Cannot find url query parameter 'class' file");
            return;
        }

        include $classPath;
        // See if class exists
        if(!class_exists($queryParams['class'])){
            Response::setResponse(false, "Cannot find class");
            return;
        }

        // See if function exists
        $obj = new $queryParams['class']();
        if(!method_exists($obj, $queryParams['func'])){
            Response::setResponse(false, "Cannot find function in class");
            return;
        }

        $funcName = $queryParams['func'];
        $obj->$funcName();
        return;
    }   
    
}