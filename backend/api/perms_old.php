<?php

header("Access-Control-Allow-Origin: http://localhost:3000");
//header("Access-Control-Allow-Origin: CURRENT_HOST");
//header("Content-Type: application/json");
//header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//header("Access-Control-Expose-Headers: Access-Token, Uid");



switch ($requestMethod) {
    case 'GET':
        handleGETRequest($pdo, $sessionMan);
        break;
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

function handleGETRequest($pdo, $sessionMan) {
    //echo $_COOKIE['Authorization'];

    if (!checkCookieToken()) {
        http_response_code(401);
        exit();
    }

    try {
        if (isset($_GET['page'])) {
            $PAGE_LENGTH = 10;
            $PAGE_OFFSET = 10;

            $stmt = $pdo->prepare("SELECT * FROM `PERMISSIONS` LIMIT :PAGE_LENGTH OFFSET :PAGE_OFFSET"); 
            $stmt->execute(['PAGE_LENGTH' => $PAGE_LENGTH,'PAGE_OFFSET' => $_GET['page']*$PAGE_OFFSET]);
            $result = $stmt->fetchAll();
        } else if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM `PERMISSIONS` WHERE perm_id = :id"); 
            $stmt->execute(['id' => $_GET['id']]);
            $result = $stmt->fetch();
        } else {
            $result = $pdo->query("SELECT * FROM `PERMISSIONS`")->fetchAll();
        }
        
        http_response_code(200);
        echo json_encode(
            $result
        );
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "400", 
            "message" => $e->getMessage()

        ]);
    }
}

function handlePOSTRequest($pdo, $sessionMan) {
    
    if (!checkCookieToken()) {
        http_response_code(401);
        exit();
    }
    $token = 0;

    $untrimmedToken = $_COOKIE['Authorization'];
    $token = trimToken($untrimmedToken);
    //echo $token;
    
    $currentUserID = $sessionMan->getUserId($token);
    //echo $currentUserID;

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
        
        $stmt = $pdo->prepare('SELECT PERMISSIONS.perm_id, PERMISSIONS.perm_name, PERMISSIONS.perm_weight, PERMISSIONS.disallowed_routes FROM `USER` JOIN `PERMISSIONS` ON USER.perm_id = PERMISSIONS.perm_id WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $currentUserID]);
        $result = $stmt->fetch();

        http_response_code(200);
        echo json_encode(
            $result
        );



    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "400", 
            "message" => $e->getMessage()
        ]);
    }
}