<?php

//header("Access-Control-Allow-Origin: CURRENT_HOST");
//header("Content-Type: application/json");
//header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//header("Access-Control-Expose-Headers: Access-Token, Uid");

//echo $finalPath[1];

switch ($requestMethod) {
    case 'POST':
        handlePOSTRequest($pdo, $sessionMan);
        break;
    default:
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => "Invalid request method"
        ]);
        break;
}

// Function to handle POST requests
// Handles the autorization of the user and gives back answear, if token is valid or not
function handlePOSTRequest($pdo, $sessionMan) {
    //$input = json_decode(file_get_contents("php://input"), true);

    // Check if user has cookie called Authorization
    if (!checkCookieToken()) {
        http_response_code(401);
        exit();
    }
    $untrimmedToken = $_COOKIE['Authorization'];
    $token = trimToken($untrimmedToken);
    
    try {
        // Check if token is not valid
        if (!$sessionMan->checkToken($token)) {
            http_response_code(401);
            echo json_encode([
                "status" => "401", 
                "message" => "Unauthorized"
            ]);
            exit();
        }
        // Check if token is timed out
        if ($sessionMan->isSessionTimeOut($token)) {
            http_response_code(401);
            echo json_encode([
                "status" => "401", 
                "message" => "User session timed out"
            ]);
            exit();
        }
        // If everything is ok, return 200 status code
        http_response_code(200);
        echo json_encode([
            "status" => "200", 
            "message" => "Authorized"
        ]);
        exit();
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "400", 
            "message" => $e->getMessage()
        ]);
    }
}