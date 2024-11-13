<?php

header("Access-Control-Allow-Origin: $currentHost");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Expose-Headers: Access-Token, Uid");

switch ($requestMethod) {
    case 'POST':
        handlePostRequest($pdo, $sessionMan);
        break;
    default:
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => "Invalid request method"
        ]);
        break;
}

function handlePostRequest($pdo, $sessionMan) {
    $input = json_decode(file_get_contents("php://input"), true);

    $headers = getallheaders();
    
    $untrimmedToken = $headers['Authorization'];
    $token = trim(substr($untrimmedToken, 6));

    //echo $token;

    try {
        if (!$sessionMan->checkToken($token)) {
            http_response_code(401);
            echo json_encode([
                "status" => "401", 
                "message" => "Unauthorized"
            ]);
            exit();
        }

        if ($sessionMan->isSessionTimeOut($token)) {
            http_response_code(408);
            echo json_encode([
                "status" => "408", 
                "message" => "Request Timeout"
            ]);
            exit();
        }

        
        http_response_code(200);
        echo json_encode([
            "status" => "200", 
            "message" => "Authorized"
        ]);



    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "400", 
            "message" => $e->getMessage()
        ]);
    }
}