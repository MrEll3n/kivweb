<?php

//header("Access-Control-Allow-Origin: CURRENT_HOST");
//header("Content-Type: application/json");
//header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//header("Access-Control-Expose-Headers: Access-Token, Uid");

//echo $finalPath;

require_once "../utils.php";

[$finalPath, $endpoint, $getQueries] = getApiPath();
//echo $endpoint[1];

switch ($requestMethod) {
    case 'GET':
        //echo $endpoint[1];
        handleGETRequest($pdo, $sessionMan);
        break;
    case 'POST':
        //echo $endpoint[1];
        handlePOSTRequest($pdo, $sessionMan, $endpoint);
        break;
    default:
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => "Invalid request method"
        ]);
        break;
}

function handleGETRequest($pdo, $sessionMan) {
    try {
        if (!checkCookieToken()) {
            http_response_code(401);
            exit();
        }
        
        $untrimmedToken = $_COOKIE['Authorization'];
        $token = trimToken($untrimmedToken);
        
        echo json_encode([
            "status" => "200", 
            "message" => "Success",
            "data" => $token
        ]);
        exit();

    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => "Invalid request method"
        ]);
        exit();
    }
}

// Function to handle POST requests
// Handles the autorization of the user and gives back answear, if token is valid or not
function handlePOSTRequest($pdo, $sessionMan, $endpoint) {
    if (!isset($endpoint[1])){
        http_response_code(401);
        echo json_encode([
            "status" => "401", 
            "message" => 'Wrong endpoint'
        ]); 
        exit();
    }
    
    if ($endpoint[1] == "refresh") {
        // Check if user has cookie called Authorization
        if (!checkCookieToken()) {
            http_response_code(401);
            exit();
        }
        $untrimmedToken = $_COOKIE['Authorization'];
        $token = trimToken($untrimmedToken);
        
        $currentUserId = $sessionMan->getUserId($token);
        //echo $currentUserId;

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
                    "message" => "Unauthorized"
                ]);
                exit();
            }


            $sessionMan->createSession($currentUserId);
            http_response_code(200);
            echo json_encode([
                "status" => "200", 
                "message" => "Authorized"
            ]);
            
            // Set the cookie
            $cookieName = 'Authorization';
            $bearerToken = "Bearer " . $sessionMan->getToken($currentUserId);
            $cookieExpire = time() + 3600; // 1 hour
            $cookiePath = '/'; // Entire domain

            // Ensure SameSite=None for cross-site requests and Secure for HTTPS
            setcookie($cookieName, $bearerToken, [
                'expires' => $cookieExpire,
                'path' => $cookiePath,
                'secure' => false,    // Only send cookie over HTTPS
                'httponly' => true,  // Accessible only through the HTTP protocol
                'samesite' => 'Lax' // Allow cross-site requests
            ]);
            exit();

        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode([
                "status" => "400", 
                "message" => $e->getMessage()
            ]);
            exit();
        }
    } else if ($endpoint[1] == "checkExpiration") {
        try {
            if (!checkCookieToken()) {
                http_response_code(401);
                exit();
            }

            $token = 0;

            $untrimmedToken = $_COOKIE['Authorization'];
            $token = trimToken($untrimmedToken);
            
            $currentUserID = $sessionMan->getUserId($token);
            
            if (!$sessionMan->checkToken($token)) {
                http_response_code(401);
                echo json_encode([
                    "status" => "401", 
                    "message" => "Expired"
                ]);
                exit();
            }
    
            if ($sessionMan->isSessionTimeOut($token)) {
                http_response_code(408);
                echo json_encode([
                    "status" => "408", 
                    "message" => "Expired"
                ]);
                exit();
            }
            
            http_response_code(200);
            echo json_encode([
                "status" => "200", 
                "message" => "Not Expired"
            ]);
            exit();
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode([
                "status" => "400", 
                "message" => $e->getMessage()
            ]);
            exit();
        }
    } else if ($endpoint[1] == "remove") {
        try {
            if (!checkCookieToken()) {
                http_response_code(401);
                exit();
            }

            $token = 0;

            $untrimmedToken = $_COOKIE['Authorization'];
            $token = trimToken($untrimmedToken);
            
            $currentUserID = $sessionMan->getUserId($token);

            // Delete the Authorization cookie
            setcookie('Authorization', '', time() - 3600, '/');

            http_response_code(200);
            echo json_encode([
                "status" => "200", 
                "message" => "Cookie removed"
            ]);
            exit();
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode([
                "status" => "400", 
                "message" => $e->getMessage()
            ]);
            exit();
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => "Invalid request method"
        ]);
        exit();
    }
}