<?php

class Auth{
    // Empty constructor to instanciate object
    function _construct() {}

    private const TOKEN_EXPIRY_MINUTES = 20;
    private const TOKEN_LENGTH = 6;

    function login(){

        // Get information from frontend
        list($username, $password) = Utils::getUserDetails();

        // Check to see if user details are correct
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

        // Get email from database
        $email = self::getUserField($username, 'Email');

        // Successfully login user and send user details back to frontend
        $values = array( 
            'username'=> $username,
            'token' => $token,
            'email' => $email, );
        Response::setResponse("User session successfully created", true, $values);
    }

    function logout(){

        // Get username from fronend
        list($username) = Utils::getUserDetails();

        // Clear token
        $result = self::storeToken($username, '', 0);
        if($result === false){
            Response:: setResponse("Failed to clear user token");
            return;
        }
        // Send token to user
        Response::setResponse("User successfully Logged Out", true);     
    }

    function authorize(){

        // Get the contents of the response from the frontend               
        $json = file_get_contents('php://input');
        // Decode it as an array
        $result = json_decode($json, true);

        // Get username and token data
        list($username) = Utils::getUserDetails();
        $tokenIn = $result['token'];
        $token = self::getUserField($username, 'Token');
        // Check if tokens match
        if($tokenIn !== $token){
            Response::setResponse("Could not autorize token");
            return;
        }        

        // Get token Expiry and time
        $tokenExpiry = self::getUserField($username, 'Token_Expiry');
        $timeIn = time();
        // Check if time expired
        if($timeIn >= $tokenExpiry){
            Response::setResponse("Session Expired");
            return;
        }
        // Send response back
        Response::setResponse("User authorized", true);
    }

    private static function checkUserDetails($username, $password){
        
        // Get details of user via SQL
        $result = Sql::execute(
            "SELECT * FROM users WHERE Username = '$username'"
        );
        // Check if response failed
        if($result === false){
            return false;
        }
        // Check if passwords match
        if($result['Password'] !== $password){  
            return false;
        }
        return true;
    }    

    private static function getUserField($username, $field){
        // Execute the query
        $result = Sql::execute(
            "SELECT $field FROM users WHERE Username = '$username'"
        );
        // return the field as a value
        return $result["$field"];
    }
    
    private static function storeToken($username, $token, $tokenExpiry){
        // Update token values
        $result = Sql::update(
            "UPDATE users SET Token = '$token', Token_Expiry = '$tokenExpiry' WHERE Username= '$username'"
        );
        // Returns boolean
        return $result;
    }

    private static function updateLastLogin($username){
        // Get current datetime
        $dat = date('Y-m-d H:i:s');
        // Update last login
        $result = Sql::update(
            "UPDATE users SET Last_Login = '$dat' WHERE Username = '$username'"
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