<?php

class Response{

    static function initResponse(){
        Response::setResponse(true, 'Success');
    }

    static function setResponse($suc, $msg, $val = array()){
        $_SESSION['RESPONSE'] = array(
            'success' => $suc,
            'message' => $msg,
            'values' => $val
        );
    }

    static function getResponse(){
        return $_SESSION['RESPONSE'];
    }

}