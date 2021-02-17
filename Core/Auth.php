<?php

class Auth{
    private const TOKEN_EXPIRY_MINUTES = 20;
    private const TOKEN_LENGTH = 6;

    static function authorize(){

        $result = array(
            'success' => true,
            'message' => "Successfully authorized user",
        );
        // Get username and token data
        $username = Utils::getSentField('username');
        // Get the token from the database
        $tokenIn = Utils::getSentField('token');
        // Check if user is in database
        if(!Sql::inDatabase($username, 'Username', 'users')){
            Response::setResponse('User is not in databse');
            return;
        }
        $token = Sql::getField($username, 'Token');

        // Check if tokens match
        if($tokenIn !== $token){
            $result = array(
                'success' => false,
                'message' => "Invalid token"
            ); 
            return $result;
        }        

        // Get token Expiry and time
        $tokenExpiry = Sql::getField($username, 'Token_Expiry');
        $timeIn = time();
        // Check if time expired
        if($timeIn >= $tokenExpiry){
            $result = array(
                'success' => true,
                'message' => "Token Expired"
            );
            return $result;
        }
        // Send response back
         return $result;
    }

    static function checkUserDetails($username, $password){
        
        // Get details of user via SQL
        $result = Sql::execute(
            "SELECT * FROM users WHERE Username = '$username'"
        );
        // Check if response failed
        if($result === false || empty($result)){
            return false;
        }
        // Check if passwords match
        if($result['Password'] !== $password){  
            return false;
        }
        return true;
    }    

    static function storeToken($username, $token, $tokenExpiry){
        // Update token values
        $result = Sql::update(
            "UPDATE users SET Token = '$token', Token_Expiry = '$tokenExpiry' WHERE Username= '$username'"
        );
        // Returns boolean
        return $result;
    }

    static function updateLastLogin($username){
        // Get current datetime
        $dat = date('Y-m-d H:i:s');
        // Update last login
        $result = Sql::update(
            "UPDATE users SET Last_Login = '$dat' WHERE Username = '$username'"
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