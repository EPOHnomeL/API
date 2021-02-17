<?php

// Does administrative jobs like logging
class Admin {
    // Empty constructor to instanciate object
    function _construct() {}

     // Logs the user in and creates session
     function login(){
        // Get information from frontend
        $username = Utils::getSentField('username');
        $password = Utils::getSentField('password');

        // Check to see if user details are correct
        $validUser = Auth::checkUserDetails($username, $password);
        if(!$validUser){
            Response::setResponse("Wrong username or password");
            return;
        }
        // Proceed as validated user
        
        // Create token and expiry
        list($token, $tokenExpiry) = Auth::generateToken();

        // Store token
        $result = Auth::storeToken($username, $token, $tokenExpiry);
        if($result === false){
            Response:: setResponse("Failed to store user token");
            return;
        }

        // Update Last Login
        $result = Auth::updateLastLogin($username);
        if($result === false){
            Response:: setResponse("Failed to update Last_Login");
            return;
        }

        // Get email from database
         $email = Sql::getField($username, 'Email');

        // Successfully login user and send user details back to frontend
        $values = array( 
            'username'=> $username,
            'token' => $token,
            'email' => $email, 
        );
        Response::setResponse("User session successfully created", true, $values);
    }

    // Logs the user out 
    function logout(){
        // Get username from fronend
        $username = Utils::getSentField('username');

        // Check if user exists         TODO : Remove
        $result = Sql::getField($username, 'Email');
        if (empty($result)){
            Response::setResponse("Username does not exist");
            return;    
        }

        // Clear token
        $result = Auth::storeToken($username, '', 0);
        if($result === false){
            Response:: setResponse("Failed to clear user token");
            return;
        }
        // Send token to user
        Response::setResponse("User successfully Logged Out", true);     
    }

    // Authorizing user using username and token.
    function authorizeUser(){

        // Run authorize function
        $result = Auth::authorize();

        // Set values of user
        $values = '';
        if($result['success']){
            $username = Utils::getSentField('username');
            $email = Sql::getField($username, 'Email');
            $values = array(
                'username' => $username,
                'email' => $email
            );
        }
        // Send response back
        Response::setResponse($result['message'], $result['success'], $values);        
    }

}