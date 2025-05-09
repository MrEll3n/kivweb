<?php

//require_once 'path/to/SessionManager.php'; // Replace 'path/to/SessionManager.php' with the actual file path

// Return: the fullUri: string and the endpoint: array
function getApiPath(): array {
    // Get the request URI
    $requestUri = $_SERVER['REQUEST_URI'];

    // Find the position of 'index.php' and '? in the URI
    $indexPhpPos = strpos($requestUri, 'index.php');
    $queryPos = strpos($requestUri, '?') ? strpos($requestUri, '?') : 0; 

    // If 'index.php' is found, extract the path after it
    if ($indexPhpPos != false) {
        // Calculate the position right after 'index.php'
        $pathStartPos = $indexPhpPos + strlen('index.php/');
        
        // Extract the path after 'index.php/'
        $temp_endpoint = substr($requestUri, $pathStartPos, abs($queryPos-$pathStartPos));

        $endpoint = explode('/', $temp_endpoint);
        foreach ($endpoint as $key => $value) {
            $endpoint[$key] = trim($value, '/');
        }

        //echo''. $endpoint[0] .''. $endpoint[1];

        $query = '';
        
        if ($queryPos != 0) {
            // Extract the query string
            $stringQuery = substr($requestUri, $queryPos+1);
            $query = explode('&', $stringQuery);
        }

        //echo $endpoint[0];

        return [$requestUri, $endpoint, $query];
    }

    // If 'index.php' is not found, return an empty string or null
    return [];
}

function checkPermissions() {
    if (!isset($_COOKIE['Authorization'])) {
        return false;
    }
    return true;    
}

/*
function getPermissions() {
    if (!checkCookieToken()) {
        return null;
    }
    $sessionMan = new SessionManager();

    $untrimmedToken = $_COOKIE['Authorization'];
    $token = trimToken($untrimmedToken);
    $token = $_COOKIE['Authorization'];

    if(!$sessionMan->checkToken($token)) {
        return null;
    }

    if (!$sessionMan->checkSessionToken($token)) {
        return null;
    }

    $userId = $sessionMan->getUserId($token);
    
    $info = array();
    $res = http_get("http://localhost:8080/kivweb/backend/api/index.php/users/"."$userId"."/perm_id", array("Cookie" => "Authorization"), $info);
    echo $info['response_code'];

}
*/

function checkCookieToken() {
    if (!isset($_COOKIE['Authorization'])) {
        return false;
    }
    return true;


}

function trimToken($untrimmedToken) {
    if (trim(substr($untrimmedToken, 0, 6)) == "Bearer") {
        $token = trim(substr($untrimmedToken, 7));
    } else {
        $token = $untrimmedToken;
    }

    return $token;
}