<?php

class Users {
    // Empty constructor to instanciate object
    function _construct() {}

    function createUser(){
        
        list($username, $password, $email, $role) = Utils::getUserDetailsFromFrontend();  

        $confirmed = 1;
        $tokenExpiry = 0;
        $dat = date('Y-m-d H:i:s');

        $result = Sql::execute(
            "INSERT INTO users(Name, Password, Email, Email_Confirmed, Role, Token_Expiry, Last_Login) ".
            "VALUES('$username', '$password', '$email', '$confirmed', '$role', '$tokenExpiry', '$dat')"
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

    function test(){
        Response::setResponse("TEST SUCCEEDED", true);    
    }
}