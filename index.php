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
include './Core/Auth.php';

// Array of function where the user is not going to be authorized
const EXCEPTIONS_AUTHORIZE = array(
    "createUser",
    "login",
);

// Initialize response anf timezone
Response::initResponse();
date_default_timezone_set('Africa/Johannesburg');

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
    echo json_encode(Response::getResponse(), JSON_PRETTY_PRINT);
    exit(0);
};

// Variable to see if fucntion is exception
$isException = false;
// Loop through exceptions and check if function is one.
for ($i=0; $i < count(EXCEPTIONS_AUTHORIZE) ; $i++) { 
    if(EXCEPTIONS_AUTHORIZE[$i] === Utils::getFunctionName()){
        $isException = true;
        break;    
    } else {
        $isException = false;
    }
}

// Check if function is exception
if($isException){
    // Run request
    Utils::runURLQuery();
    // Send the response back
    echo json_encode(Response::getResponse(), JSON_PRETTY_PRINT);
    exit(0);
}

// Run Authorize and set response accourdingly
$result = Auth::authorize();
if($result['success']){
    // Run request
    Utils::runURLQuery();
} else {
    // Set response equel to the result of the authorization
    Response::$response = $result;
}

// Send the response back
echo json_encode(Response::getResponse(), JSON_PRETTY_PRINT);
exit(0);

?>
