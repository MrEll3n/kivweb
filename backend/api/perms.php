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
                $result = $pdo->query("SELECT perm_id, perm_name, perm_weight, disallowed_routes FROM `PERMISSIONS`")->fetchAll();
                http_response_code(200);
                echo json_encode($result);
                exit();
            }

            // If there is a page and size query
            if (isset($_GET['page']) && isset($_GET['size']) && $_GET['page'] > 0 && $_GET['size'] > 0) {
                $PAGE = $_GET['page'];
                $SIZE = $_GET['size'];
                $stmt = $pdo->prepare("SELECT perm_id, perm_name, perm_weight, disallowed_routes FROM `PERMISSIONS` LIMIT :PAGE OFFSET :PAGE_OFFSET"); 
                $stmt->execute(['PAGE' => $PAGE, 'PAGE_OFFSET' => ($PAGE - 1)*$SIZE]);
                $result = $stmt->fetchAll();
                http_response_code(200);
                echo json_encode($result);
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
        $perm_id = $endpoint[1];

        try {
            $stmt = $pdo->prepare("SELECT perm_id, perm_name, perm_weight, disallowed_routes FROM `PERMISSIONS` WHERE perm_id = :perm_id"); 
            $stmt->execute(['perm_id' => $perm_id]);
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
        $perm_id = $endpoint[1];
        $item = '';

        switch ($endpoint[2]) {
            case 'perm_id':
                $item = 'perm_id';
                break;
            case 'perm_name':
                $item = 'perm_name';
                break;
            case 'perm_weight':
                $item = 'perm_weight';
                break;
            case 'disallowed_routes':
                $item = 'disallowed_routes';
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
            $stmt = $pdo->prepare("SELECT $item FROM PERMISSIONS WHERE perm_id = :perm_id"); 
            $stmt->execute(['perm_id' => $perm_id]);
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

function handlePOSTRequest($pdo, $sessionMan, $endpoint) {
        try {
        if (!checkCookieToken()) {
            http_response_code(401);
            exit();
        }
        
        $untrimmedToken = $_COOKIE['Authorization'];
        $token = trimToken($untrimmedToken);
        $user_id = $sessionMan->getUserId($token);

        $stmt1 = $pdo->prepare("SELECT perm_id FROM `USER` WHERE user_id = :user_id"); 
        $stmt1->execute(['user_id' => $user_id]);
        $result1 = $stmt1->fetch();

        // If there is no query
        $stmt2 = $pdo->prepare("SELECT perm_id, perm_name, perm_weight, disallowed_routes FROM `PERMISSIONS` WHERE perm_id = :perm_id"); 
        $stmt2->execute(['perm_id' => $result1['perm_id']]);
        $result2 = $stmt2->fetch();

        http_response_code(200);
        echo json_encode($result2);
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