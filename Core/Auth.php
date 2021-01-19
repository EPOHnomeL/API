<?php

class Auth{

    private const TOKEN_EXPIRY_MINUTES = 20;
    private const TOKEN_LENGTH = 6;

    static function checkUserDetails($username, $password){
        
        $result = Sql::execute("SELECT * FROM users WHERE `Name` = '$username'");
        if($result === false){
            return false;
        }
        if($result['Password'] !== $password){  
            return false;
        }
        return true;
    }    
    
    static function storeToken($username, $token, $tokenExpiry){
        $result = Sql::execute(
            "UPDATE users SET (Token = '$token', Token_Expiry = $tokenExpiry) WHERE `Name`= '$username'"
        );
        return $result;
    }
    
    static function generateToken(){
        //  Set expiration time (millisecs)
        $expiryTime = 1000 * (time() + 60 * self::TOKEN_EXPIRY_MINUTES);
        // Create JWT
        $jwt = bin2hex(random_bytes(self::TOKEN_LENGTH / 2));
        return [$jwt, $expiryTime];
    }
    
}