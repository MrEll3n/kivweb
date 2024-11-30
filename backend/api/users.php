<?php

//header("Access-Control-Allow-Origin: CURRENT_HOST");
//header("Content-Type: application/json");
//header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//header("Access-Control-Expose-Headers: Access-Token, Uid");

require_once "../utils.php";

[$finalPath, $endpoint, $getQueries] = getApiPath();

switch ($requestMethod) {
    case 'GET':
        handleGETRequest($pdo, $sessionMan, $endpoint);
        break;
    case 'PUT':
        handlePUTRequest($pdo, $sessionMan, $endpoint);
        break; 
    case 'POST':
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

function handleGETRequest($pdo, $sessionMan, $endpoint) {
    // If theres no endpoint, return all users
    if (count($endpoint) == 1) {
        try {
            // If there is no query
            if (!isset($_GET['page']) && !isset($_GET['size'])) {
                $result = $pdo->query("SELECT user_id, user_name, user_email, perm_id FROM `USER`")->fetchAll();
                http_response_code(200);
                echo json_encode(["data" => $result]);
                exit();
            }

            // If there is a page and size query
            if (isset($_GET['page']) && isset($_GET['size']) && $_GET['page'] > 0 && $_GET['size'] > 0) {
                $PAGE = $_GET['page'];
                $SIZE = $_GET['size'];
                $stmt = $pdo->prepare("SELECT user_id, user_name, user_email, perm_id FROM `USER` LIMIT :PAGE OFFSET :PAGE_OFFSET"); 
                $stmt->execute(['PAGE' => $PAGE, 'PAGE_OFFSET' => ($PAGE - 1)*$SIZE]);
                $result = $stmt->fetchAll();
                http_response_code(200);
                echo json_encode(["data" => $result]);
                exit();
            }

            // If there is invalid query
            if ( (!isset($_GET['page']) && isset($_GET['size'])) || (isset($_GET['page']) && !isset($_GET['size'])) ||
                ($_GET['page'] <= 0 && $_GET['size'] <= 0) ) {
                http_response_code(400);
                echo json_encode([
                    "status" => "400", 
                    "message" => "Invalid query"
                ]);
                exit();
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => $e->getMessage()
            ]);
            echo 69;
            exit();
        }
    }

    // if there is an id
    if (count($endpoint) == 2) {
        $user_id = $endpoint[1];

        try {
            $stmt = $pdo->prepare("SELECT USER.user_id, USER.user_name, USER.user_email, perm_id FROM `USER` WHERE user_id = :user_id"); 
            $stmt->execute(['user_id' => $user_id]);
            $result = $stmt->fetch();
            http_response_code(200);
            echo json_encode($result);
            exit();

        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode([
                "status" => "400", 
                "message" => $e->getMessage()
            ]);
            echo "92";
            exit();
        }
    }

    if (count($endpoint) == 3) {
        $user_id = $endpoint[1];
        $item = '';

        switch ($endpoint[2]) {
            case 'user_id':
                $item = 'user_id';
                break;
            case 'user_name':
                $item = 'user_name';
                break;
            case 'user_email':
                $item = 'user_email';
                break;
            case 'perm':
                $item = 'user_email';
                break;
            default:
                http_response_code(400);
                echo json_encode([
                    "status" => "400", 
                    "message" => "Invalid selector"
                ]);
                exit();
        }

        try {
            $stmt = $pdo->prepare("SELECT $item FROM USER WHERE user_id = :user_id"); 
            $stmt->execute(['user_id' => $user_id]);
            $result = $stmt->fetch();
            http_response_code(200);
            echo json_encode($result);

        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode([
                "status" => "400", 
                "message" => $e->getMessage()
            ]);
            echo "132";
        }
    }   
}

function handlePUTRequest($pdo, $sessionMan, $endpoint) {
    $input = json_decode(file_get_contents("php://input"), true);

    $hashed_password = password_hash($input['user_password'], PASSWORD_BCRYPT);

    //echo json_encode([ "input" => $input, "hash" => $hashed_password]); 
    if (isset($input['user_name']) and isset($input['user_email']) and isset($input['user_password'])) {
        try {
            $is_correct = true;

            // SELECT Query \\
            $stmt_select = $pdo->prepare('SELECT * FROM USER WHERE user_name = :user_name OR user_email = :user_email');
            $stmt_select->execute(['user_name' => $input['user_name'], 'user_email' => $input['user_email']]);
            $result_select = $stmt_select->fetch();
            
            // USER_NAME \\
            if ($input['user_name'] == "") {
                echo json_encode([
                    "status" => "error", 
                    "message" => "No user_name inserted"
                ]); 
                $is_correct = false;
            } else if (strlen($input['user_name']) < 4) {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Insufficient user_name length"
                ]); 
                $is_correct = false;
            } else if ($input['user_name'] == $result_select['user_name']) {
                echo json_encode([
                    "status" => "error", 
                    "message" => "User_name already taken"
                ]); 
                $is_correct = false;
            }

            // USER_EMAIL \\
            if ($input['user_email'] == "") {
                echo json_encode([
                    "status" => "error", 
                    "message" => "No user_email inserted"
                ]); 
                $is_correct = false;
            } else if (strlen($input['user_email']) < 8) {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Insufficient user_email length"
                ]); 
                $is_correct = false;
            } else if ($input['user_email'] == $result_select['user_name']) {
                echo json_encode([
                    "status" => "error", 
                    "message" => "User_email already taken"
                ]); 
                $is_correct = false;
            }

            // USER_PASSWORD \\
            if ($input['user_password'] == "") {
                echo json_encode([
                    "status" => "error", 
                    "message" => "No user_password inserted"
                ]); 
                $is_correct = false;
            } else if (strlen($input['user_password']) < 8) {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Insufficient user_password length"
                ]); 
                $is_correct = false;
            }

            if (!$is_correct) {
                exit();
            }

            // INSERT Query
            $stmt = $pdo->prepare('INSERT INTO USER (user_name, user_email, user_password) VALUES (:user_name, :user_email, :user_password)');
            $stmt->bindParam(':user_name', $input['user_name']);
            $stmt->bindParam(':user_email', $input['user_email']);
            $stmt->bindParam(':user_password', $hashed_password);

            if ($stmt->execute()) {
                // Get the ID of the newly created user
                $newUserId = $pdo->lastInsertId();
    
                // Set the response code and headers
                http_response_code(201);
                header("Location: /users/$newUserId");
                header("Content-Type: application/json");
    
                // Respond with the new user data
                echo json_encode([
                    "user_id" => $newUserId,
                    "username" => $input['user_name'],
                    "email" => $input['user_email']
                ]);
                exit();
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to create user."]);
                exit();
            }

        } catch (PDOException $e) {
            echo json_encode([
                "status" => "error", 
                "message" => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid data"
        ]);
    }
}

function handlePOSTRequest($pdo, $sessionMan, $endpoint) {
    try {
        if (!checkCookieToken()) {
            http_response_code(401);
            exit();
        }
        
        $untrimmedToken = $_COOKIE['Authorization'];
        $token = trimToken($untrimmedToken);
        $user_id = $sessionMan->getUserId($token);
        
        // If there is no query
        $stmt = $pdo->prepare("SELECT USER.user_id, USER.user_name, USER.user_email, perm_id FROM `USER` WHERE user_id = :user_id"); 
        $stmt->execute(['user_id' => $user_id]);
        $result = $stmt->fetch();

        http_response_code(200);
        echo json_encode(["data" => $result]);
        exit();
        
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => $e->getMessage()
        ]);
        echo 69;
        exit();
    }
}