<?php

header("Access-Control-Allow-Origin: $currentHost");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Expose-Headers: Access-Token, Uid, Authorization");

switch ($requestMethod) {
    case 'POST':
        handlePostRequest($pdo, $sessionMan);
        break;
    default:
    //http_response_code(400);
        echo json_encode([
            "status" => "400",
             "message" => "Invalid request method"
        ]);
        break;
}

function handlePostRequest($pdo, $sessionMan) {
    $input = json_decode(file_get_contents("php://input"), true);

    $hashed_password = password_hash($input['user_password'], PASSWORD_BCRYPT);


    //echo json_encode([ "input" => $input, "hash" => $hashed_password]); 
    if (isset($input['user_email']) and isset($input['user_password'])) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM USER WHERE user_email = :user_email');
            $stmt->execute(['user_email' => $input['user_email']]);
            $result = $stmt->fetch();

            if ($result['user_email'] == "") {
                //http_response_code(401);
                echo json_encode([
                    "status" => "401", 
                    "message" => "Login Failed"
                ]); 
                exit();
            }

            if (!password_verify($input['user_password'], $result['user_password'])) {
                //http_response_code(401);
                echo json_encode([
                    "status" => "401", 
                    "message" => "Login Failed", 
                ]);    
                exit();
            }

            $sessionMan->createSession($result['user_id']);
            //http_response_code(200);
            echo json_encode([
                "status" => "200", 
                "message" => "Login Succesesful"
                //"data" => $sessionMan->getToken($result['user_id'])
            ]);

            $bearToken = "Authorization: Bearer ".$sessionMan->getToken($result['user_id']);

            header($bearToken);

            //echo $bearToken;
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
