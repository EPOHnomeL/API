<?php

class Utils{

    // Key words that are required
    const KEY_WORDS = [ "class", "func" ];

    public static function seperateURLQuery($urlQuery){

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

        // Initialize response variable
        $response = array(
            'success' => true,
            'message' => 'Success',
            'values' => array(),
        );
        
        // TODO validate URL for class and func
        if(count($queryParams) != 2){
            $response = array(
                'success' => false,
                'message' => "Not enough parameters for 'class' and 'func'",
                'values' => array(),   
            );   
        } else {
            // Find 'class' and 'func'
            foreach($queryParams as $key=>$value){
                if(!in_array($key, Utils::KEY_WORDS)){
                    $response = array(
                        'success' => false,
                        'message' => "Cannot find 'class' and 'func'",
                        'values' => array(),
                    ); 
                }
            }
        }
        return $response;
    }
    
    
}