<?php

class Users {
    // Empty constructor to instanciate object
    function _construct() {}

    function createUser(){
        $json = file_get_contents('php://input');
        $newUser = json_decode($json, true);

        // Values to insert into table
        $name = Sql::sanitise($newUser['username']);
        $password = Sql::sanitise($newUser['password']);
        $email = Sql::sanitise($newUser['email']);
        $confirmed = 1;
        $role = $newUser['role'];        
        $tokenExpiry = 500;
        $dat = date('Y-m-d H:i:s');

        $result = Sql::execute(
            "INSERT INTO users(Name, Password, Email, Email_Confirmed, Role, Token_Expiry, Last_Login) ".
            "VALUES('$name', '$password', '$email', '$confirmed', '$role', '$tokenExpiry', '$dat')"
        );
        if($result === false){
            Response::setResponse("ERROR: Could not create new user");
            return;
        }
        Response::setResponse('User created', true); 
    }

    function updateUser($val) {
        // code...
        Response::setResponse("User updated", true);
    }

    function getAllUsers() {
        $result = Sql::execute('SELECT * FROM users');
        if($result === false){
            Response::setResponse("ERROR: Could not get all user");
            return;
        }
        Response::setResponse("Users retrieved", true, $result);
    }

    function deleteUser() {
        // code...
        Response::setResponse("User deleted", true);  
    }

    function login(){
        
        $json = file_get_contents('php://input');
        $user = json_decode($json, true);

        $username = Sql::sanitise($user['username']);
        $password = Sql::sanitise($user['password']);

        $validUser = Auth::checkUserDetails($username, $password);

        if(!$validUser){
            Response::setResponse("Wrong username or password");
            return;
        }
        // Proceed as validated user
        
        // Create token and expiry
        list($token, $tokenExpiry) = Auth::generateToken();
        // store token
        $result = Auth::storeToken($username, $token, $tokenExpiry);
        if($result === false){
            Response:: setResponse("Failed to store user token");
            return;
        }

        // send token to user
        Response::setResponse("User session successfully created", true, $token);
    }

    function logout(){

    }

    function authorise(){

    }

    function test(){
        Response::setResponse("TEST SUCCEEDED", true);    
    }
}