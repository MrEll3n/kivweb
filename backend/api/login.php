<?php
switch ($requestMethod) {
    case 'POST':
        handlePostRequest($pdo, $sessionMan);
        break;
    default:
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
                echo json_encode([
                    "status" => "401", 
                    "message" => "Login Failed - No known email"
                ]); 
                exit();
            }

            if (!password_verify($input['user_password'], $result['user_password'])) {
                echo json_encode([
                    "status" => "401", 
                    "message" => "Login Failed - Bad password", 
                ]);    
                exit();
            }

            $sessionMan->createSession($result['user_id']);

            echo json_encode([
                "status" => "200", 
                "message" => "Login Succesed",
                "data" => $sessionMan->getToken($result['user_id'])
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                "status" => "400", 
                "message" => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "status" => "400",
            "message" => "Invalid data"
        ]);
    }
}
?>
