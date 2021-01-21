<?php

class Response{

    public static $response;

    // Initialize response
    static function initResponse(){
        self::setResponse('Success', true);
    }

    // Sets the response Success is false by default
    static function setResponse($msg, $suc = false, $val = array()){
        self::$response = array(
            'success' => $suc,
            'message' => $msg,
            'values' => $val
        );
    }

    // Get the reponse
    static function getResponse(){
        return self::$response;
    }
}