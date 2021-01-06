<?php

include './Core/Utils.php';

// TODO:
// validate all requests   (keep architecture in mind) 
// authenticate requests

// all request go to here
$query = $_SERVER['REDIRECT_QUERY_STRING'];
// Get query parameters as key-value pairs
$queryParams = Utils::seperateURLQuery($query);
// Sanitise request
$queryParams = Utils::sanitiseURLQuery($queryParams);
// Validate request
$response = Utils::validateURLQuery($queryParams);

echo json_encode($response);
exit(0);

?>
