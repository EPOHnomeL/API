<?php

class Users {
    // Empty constructor to instanciate object
    function _construct() {}

    function createUser(){
        $json = file_get_contents('php://input');
        $newUser = json_decode($json, true);

        $role = 'cashier';
        $confirmed = 1;
        $tokenExpiry = 500;
        $dat = date('Y-m-d H:i:s');

        Sql::insertIntoStatement( array(            // This is gross
            'Name' => $newUser['username'],
            'Password' => $newUser['password'],
            'Email' => $newUser['email'],
            'Email_Confirmed' => $confirmed,
            'Role' => $role,
            'Token_Expiry' => $tokenExpiry, 
            'Last_Login' => $dat
        ));

        Response::setResponse('User created', true); 
}

    function updateUser() {
        // code...
        Response::setResponse("User updated", true);
    }

    function getAllUsers() {
        $users = array();
        Sql::execute('SELECT * FROM users');
        Response::setResponse("Users retrieved", true, $users);
    }

    function deleteUser() {
        // code...
        Response::setResponse("User deleted", true);  
    }

    function test(){
        Response::setResponse("TEST SUCCEEDED", true);    
    }
}