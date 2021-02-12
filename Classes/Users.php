<?php

class Users {
    // Empty constructor to instanciate object
    function _construct() {}

    // Creates a user and add it to the database
    function createUser(){
        // Gets the user details
        list($username, $password, $email, $role) = Utils::getUserDetails();  

        // Initialize varibles
        $confirmed = 1;
        $tokenExpiry = 0;
        $dat = date('Y-m-d H:i:s');

        // Check if username already exists
        $result = Sql::getUserField($username, 'Email');
        if (!empty($result)){
            Response::setResponse("Username already used");
            return;    
        }

        // Insert user into database
        $result = Sql::execute(
            "INSERT INTO users(Username, `Password`, Email, Email_Confirmed, `Role`, Token_Expiry, Last_Login) ".
            "VALUES('$username', '$password', '$email', '$confirmed', '$role', '$tokenExpiry', '$dat')"
        );
        // Check if response failed
        if($result === false){
            Response::setResponse("ERROR: Could not create new user");
            return;
        }
        // Send suceeded response back
        Response::setResponse('User created', true); 
    }

    // Gets all the current user in the database
    function getAllUsers() {
        // Executes the query
        $result = Sql::execute('SELECT * FROM users');
        // Check if response failed
        if($result === false){
            Response::setResponse("ERROR: Could not get all user");
            return;
        }
        // Send suceeded response back
        Response::setResponse("Users retrieved", true, $result);
    }

    // Deletes a user
    function deleteUser() {
        // code...
        Response::setResponse("User deleted", true);  
    }

    // Update Username and email
    function updateUserDetails() {
        // Get the contents of the response from the frontend               
        $json = file_get_contents('php://input');
        // Decode it as an array
        $result = json_decode($json, true);

        // Get username and token data
        list($username) = Utils::getUserDetails(); 
        $newUsername = array_key_exists('newUsername', $result) ? Sql::sanitise($result['newUsername']) : '';
        $newEmail = array_key_exists('newEmail', $result) ? Sql::sanitise($result['newEmail']) : '';
        // replace username with token
        
        $result = Sql::update(
            "UPDATE users SET Username = '$newUsername', Email = '$newEmail' WHERE Username = '$username'"
        );
        // Check if result failed
        if(!$result){
            Response::setResponse("Fail to update user information");
            return;
        }
        // Send suceeded response back
        Response::setResponse("User details updated", true);
    }

}