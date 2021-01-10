<?php

// Headers
header('Access-Control-Allow-Origin: http://localhost:4200');
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Max-Age: 3600");
header("Content-Type: text/json; charset=UTF-8");

// Import Core libraries
include './Core/Utils.php';
include './Core/Response.php';
include './Core/Sql.php';

// All functions have side effect of changing
// Therefore get the response from Response::getResponse()

// TODO:
// authenticate requests

// Initialize response 
Response::initResponse();
date_default_timezone_set('Africa/Johannesburg');

// all request go to $_SERVER['REDIRECT_QUERY_STRING']

// Check if query parameters are not 0
if (!isset($_SERVER['REDIRECT_QUERY_STRING'])){
    Response::setResponse("Not enough parameters for 'class' and 'func'");
    echo json_encode( Response::getResponse());
    exit(0);
}

// Get the url query from the $_SERVER global variable
$query = $_SERVER['REDIRECT_QUERY_STRING'];
// Get query parameters as key-value pairs
$queryParams = Utils::separateURLQuery($query);
// Sanitise request
$queryParams = Utils::sanitiseURLQuery($queryParams);
// Check if validation fails
if(!Utils::validateURLQuery($queryParams)){
    echo json_encode(Response::getResponse());
    exit(0);
};

// Run request
Utils::runURLQuery();

// Send the response back
echo json_encode(Response::getResponse());
exit(0);

?>
