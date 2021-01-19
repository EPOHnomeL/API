<?php

class Auth{
    // Empty constructor to instanciate object
    function _construct() {}

    private const TOKEN_EXPIRY_MINUTES = 20;
    private const TOKEN_LENGTH = 6;

    function login(){

        list($username, $password) = Utils::getHttpPayload();

        $validUser = self::checkUserDetails($username, $password);

        if(!$validUser){
            Response::setResponse("Wrong username or password");
            return;
        }
        // Proceed as validated user
        
        // Create token and expiry
        list($token, $tokenExpiry) = self::generateToken();
        // Store token
        $result = self::storeToken($username, $token, $tokenExpiry);
        if($result === false){
            Response:: setResponse("Failed to store user token");
            return;
        }
        // Update Last Login
        $result = self::updateLastLogin($username);
        if($result === false){
            Response:: setResponse("Failed to update Last_Login");
            return;
        }
        // Send token to user
        Response::setResponse("User session successfully created", true, [$username, $token]);
    }

    function logout(){

        list($username) = Utils::getHttpPayload();

        // Clear token
        $result = self::storeToken($username, '', 0);
        if($result === false){
            Response:: setResponse("Failed to clear user token");
            return;
        }
        // Send token to user
        Response::setResponse("User successfully Logged Out", true);     
    }

    function authorise(){

    }

    private static function checkUserDetails($username, $password){
        
        $result = Sql::execute("SELECT * FROM users WHERE `Name` = '$username'");
        if($result === false){
            return false;
        }
        if($result['Password'] !== $password){  
            return false;
        }
        return true;
    }    
    
    private static function storeToken($username, $token, $tokenExpiry){
        $result = Sql::update(
            "UPDATE users SET Token = '$token', Token_Expiry = '$tokenExpiry' WHERE `Name`= '$username'"
        );
        return $result;
    }

    private static function updateLastLogin($username){
        $dat = date('Y-m-d H:i:s');
        $result = Sql::update(
            "UPDATE users SET Last_Login = '$dat' WHERE `Name` = '$username'"
        );
        return $result;
    }
    
    private static function generateToken(){
        //  Set expiration time (millisecs)
        $expiryTime = 1000 * (time() + 60 * self::TOKEN_EXPIRY_MINUTES);
        // Create JWT
        $jwt = bin2hex(random_bytes(self::TOKEN_LENGTH / 2));
        return [$jwt, $expiryTime];
    }    
}