<?php

class Products {
    // Empty constructor to instanciate object
    function _construct() {}

    function createProduct(){

        // Get variables from request
        $username = Utils::getSentField('username');
        $userId = Utils::getUserId($username);
        $name = Utils::getSentField('name');
        $category = Utils::getSentField('category');
        $quantity = Utils::getSentField('quantity');
        $date = date('Y-m-d H:i:s');

        // Insert product into database
        $result = Sql::execute(
            "INSERT INTO products(`Name`, Category, Quantity, CreatedOn, CreatedBy) ".
            "VALUES('$name', '$category', '$quantity', '$date', '$userId')"
        );
        // Check if response failed
        if($result === false){
            Response::setResponse("ERROR: Could not create new product");
            return;
        }
        // Send suceeded response back
        Response::setResponse('Product created', true); 
    }

    
}