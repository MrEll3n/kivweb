<?php

    //header("Access-Control-Allow-Origin: CURRENT_HOST");
    //header("Access-Control-Allow-Credentials: true");
    //header("Content-Type: application/json");
    //header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    //header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    //header("Access-Control-Expose-Headers: Access-Token, Uid, Authorization");

    switch ($requestMethod) {
        case 'POST':
            handlePOSTRequest($pdo, $sessionMan);
            break;
        default:
            //http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => "Invalid request method"
            ]);
            break;
    }

    function handlePOSTRequest($pdo, $sessionMan) {
        $input = json_decode(file_get_contents("php://input"), true);
        $hashed_password = password_hash($input['user_password'], PASSWORD_BCRYPT);

        //echo json_encode([ "input" => $input, "hash" => $hashed_password]); 
        if (isset($input['user_email']) and isset($input['user_password'])) {
            try {
                $stmt = $pdo->prepare('SELECT * FROM USER WHERE user_email = :user_email');
                $stmt->execute(['user_email' => $input['user_email']]);
                $result = $stmt->fetch();

                //echo implode(" | ", $result) .'';

                if ($result['user_email'] == "") {
                    http_response_code(401);
                    echo json_encode([
                        "status" => "401", 
                        "message" => "Login Failed - 1"
                    ]); 
                    exit();
                }

                if (!password_verify($input['user_password'], $result['user_password'])) {
                    http_response_code(401);
                    echo json_encode([
                        "status" => "401", 
                        "message" => "Login Failed - 2", 
                    ]);    
                    exit();
                }

                $stmt = $pdo->prepare('SELECT user_id, user_name, user_email, perm_id FROM USER WHERE user_email = :user_email');
                $stmt->execute(['user_email' => $input['user_email']]);
                $result = $stmt->fetch(); 

                $sessionMan->createSession($result['user_id']);
                http_response_code(200);
                echo json_encode([
                    "status" => "200", 
                    "message" => "Login Succesesful",
                    "data" => $result
                ]);

                // Set the cookie
                $cookieName = 'Authorization';
                $bearerToken = "Bearer " . $sessionMan->getToken($result['user_id']);
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

            } catch (PDOException $e) {
                //http_response_code(400);
                echo json_encode([
                    "status" => "400", 
                    "message" => $e->getMessage()
                ]);
            }
        } else {
            //http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => "Invalid data"
            ]);
        }
    }
?>
