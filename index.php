<?php

include './Core/Utils.php';
include './Core/Response.php';

// TODO:
// authenticate requests

// Initialize response 
Response::initResponse();

// all request go to here
if (!isset($_SERVER['REDIRECT_QUERY_STRING'])){
    Response::setResponse(false, "Not enough parameters for 'class' and 'func'");
    echo json_encode( Response::getResponse());
    exit(0);
}

$query = $_SERVER['REDIRECT_QUERY_STRING'];
// Get query parameters as key-value pairs
$queryParams = Utils::separateURLQuery($query);
// Sanitise request
$queryParams = Utils::sanitiseURLQuery($queryParams);
// Validate request
Utils::validateURLQuery($queryParams);

echo json_encode(Response::getResponse());
exit(0);

?>
