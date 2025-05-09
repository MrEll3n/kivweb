<?php

// Include necessary headers for CORS and JSON content type
// header("Access-Control-Allow-Origin: CURRENT_HOST");
// header("Content-Type: application/json");
// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// header("Access-Control-Expose-Headers: Access-Token, Uid");

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
    case 'DELETE':
        handleDELETERequest($pdo, $sessionMan, $endpoint);
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
    // If there's no endpoint, return all users
    if (count($endpoint) == 1) {
        try {
            // If there are no query parameters
            if (!isset($_GET['page']) && !isset($_GET['size']) && !isset($_GET['attr'])) {
                $result = $pdo->query("SELECT user_id, user_name, user_email, perm_id FROM `USER`")->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode($result);
                exit();
            }

            if (isset($_GET['attr'])) {
                handleAttrQuery($pdo, $_GET['attr']);
                exit();
            }

            if (isset($_GET['page']) && isset($_GET['size']) && $_GET['page'] > 0 && $_GET['size'] > 0) {
                handlePagination($pdo, $_GET['page'], $_GET['size']);
                exit();
            }

            // If there is an invalid query
            if ((!isset($_GET['page']) && isset($_GET['size'])) || (isset($_GET['page']) && !isset($_GET['size'])) ||
                ($_GET['page'] <= 0 && $_GET['size'] <= 0)) {
                http_response_code(400);
                echo json_encode([
                    "status" => "400", 
                    "message" => "Invalid query"
                ]);
                exit();
            }
        } catch (PDOException $e) {
            handlePDOException($e);
        }
    }

    if (count($endpoint) == 2) {
        handleUserById($pdo, $endpoint[1]);
    }

    if (count($endpoint) == 3) {
        handleUserAttributeById($pdo, $endpoint[1], $endpoint[2]);
    }
}

function handleAttrQuery($pdo, $ATTR) {
    $allowedAttributes = ['user_id', 'user_name', 'user_email']; // Add other allowed attributes as needed //TODO: Add other attributes
    
    if (in_array($ATTR, $allowedAttributes)) {
        $stmt = $pdo->prepare("SELECT $ATTR FROM `USER`"); 
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid attribute']);
    }
}

function handlePagination($pdo, $PAGE, $SIZE) {
    $offset = ($PAGE - 1) * $SIZE;
    $stmt = $pdo->prepare("SELECT user_id, user_name, user_email, perm_id FROM `USER` LIMIT :SIZE OFFSET :OFFSET");
    $stmt->bindParam(':SIZE', $SIZE, PDO::PARAM_INT);
    $stmt->bindParam(':OFFSET', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($result);
}

function handleUserById($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT USER.user_id, USER.user_name, USER.user_email, perm_id FROM `USER` WHERE user_id = :user_id"); 
        $stmt->execute(['user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($result);
    } catch (PDOException $e) {
        handlePDOException($e);
    }
}

function handleUserAttributeById($pdo, $user_id, $item) {
    $allowedItems = ['user_id', 'user_name', 'user_email', 'perm_id'];
    
    if (!in_array($item, $allowedItems)) {
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
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($result);
    } catch (PDOException $e) {
        handlePDOException($e);
    }
}

function handlePUTRequest($pdo, $sessionMan, $endpoint) {
    $input = json_decode(file_get_contents("php://input"), true);

    // Ensure necessary fields are present
    if (!isset($input['user_name']) || !isset($input['user_email']) || !isset($input['user_password'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => "Invalid data"
        ]);
        return;
    }

    // Hash the password
    $hashed_password = password_hash($input['user_password'], PASSWORD_BCRYPT);

    try {
        // Check if user already exists
        $stmt_select = $pdo->prepare('SELECT * FROM USER WHERE user_name = :user_name OR user_email = :user_email');
        $stmt_select->execute(['user_name' => $input['user_name'], 'user_email' => $input['user_email']]);
        $result_select = $stmt_select->fetch();

        // Validate input
        if (empty($input['user_name']) || strlen($input['user_name']) < 4) {
            http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => "Invalid user_name"
            ]);
            return;
        }

        if (empty($input['user_email']) || strlen($input['user_email']) < 8) {
            http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => "Invalid user_email"
            ]);
            return;
        }

        if (empty($input['user_password']) || strlen($input['user_password']) < 8) {
            http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => "Invalid user_password"
            ]);
            return;
        }

        // If user exists, update them
        if ($result_select) {
            $current_password = $pdo->prepare('UPDATE USER SET user_name = :user_name, user_email = :user_email WHERE user_id = :user_id');
            $current_password->bindParam(':user_name', $input['user_name']);
            $current_password->bindParam(':user_email', $input['user_email']);
            $current_password->bindParam(':user_id', $result_select['user_id']);
            


            $stmt_update = $pdo->prepare('UPDATE USER SET user_name = :user_name, user_email = :user_email WHERE user_id = :user_id');
            $stmt_update->bindParam(':user_name', $input['user_name']);
            $stmt_update->bindParam(':user_email', $input['user_email']);
            $stmt_update->bindParam(':user_id', $result_select['user_id']);

            if ($stmt_update->execute()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "200",
                    "message" => "User updated successfully"
                ]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to update user."]);
            }
        } else {
            // If user does not exist, insert a new user
            $stmt_insert = $pdo->prepare('INSERT INTO USER (user_name, user_email, user_password) VALUES (:user_name, :user_email, :user_password)');
            $stmt_insert->bindParam(':user_name', $input['user_name']);
            $stmt_insert->bindParam(':user_email', $input['user_email']);
            $stmt_insert->bindParam(':user_password', $hashed_password);

            if ($stmt_insert->execute()) {
                $newUserId = $pdo->lastInsertId();
                http_response_code(201);
                header("Location: /users/$newUserId");
                echo json_encode([
                    "user_id" => $newUserId,
                    "username" => $input['user_name'],
                    "email" => $input['user_email']
                ]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to create user."]);
            }
        }
    } catch (PDOException $e) {
        handlePDOException($e);
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
        
        $stmt = $pdo->prepare("SELECT USER.user_id, USER.user_name, USER.user_email, perm_id FROM `USER` WHERE user_id = :user_id"); 
        $stmt->execute(['user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode($result);
        exit();
        
    } catch (PDOException $e) {
        handlePDOException($e);
    }
}

function handleDELETERequest($pdo, $sessionMan, $endpoint) {
    if (count($endpoint) == 2) {
        $user_id = $endpoint[1];
        try {
            $stmt = $pdo->prepare("DELETE FROM `USER` WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            http_response_code(200);
            echo json_encode([
                "status" => "200",
                "message" => "User deleted successfully"
            ]);
        } catch (PDOException $e) {
            handlePDOException($e);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => "Invalid endpoint"
        ]);
    }
}

function handlePDOException($e) {
    http_response_code(400);
    echo json_encode([
        "status" => "400",
        "message" => $e->getMessage()
    ]);
}
