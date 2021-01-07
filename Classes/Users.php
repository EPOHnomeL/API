<?php

class Users {
    function _construct() {}

    function updateUser() {
        Response::setResponse(true, "User updated");
    }

    function getAllUsers() {
        $users = array();
        Response::setResponse(true, "Users retrieved", $users);
    }

    function deleteUser() {
        Response::setResponse(false, "User deleted");
        return;   // Is this really nessaray?    
    }
}