<?php
switch ($requestMethod) {
    case 'POST':
        handlePostRequest($pdo);
        break;
    default:
        echo json_encode(["status" => "error", "message" => "Invalid request method"]);
        break;
}

function handlePostRequest($pdo) {
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
                echo json_encode(["status" => "error", "message" => "No user_name inserted"]); 
                $is_correct = false;
            } else if (strlen($input['user_name']) < 4) {
                echo json_encode(["status" => "error", "message" => "Insufficient user_name length"]); 
                $is_correct = false;
            } else if ($input['user_name'] == $result_select['user_name']) {
                echo json_encode(["status" => "error", "message" => "User_name already taken"]); 
                $is_correct = false;
            }

            // USER_EMAIL \\
            if ($input['user_email'] == "") {
                echo json_encode(["status" => "error", "message" => "No user_email inserted"]); 
                $is_correct = false;
            } else if (strlen($input['user_email']) < 8) {
                echo json_encode(["status" => "error", "message" => "Insufficient user_email length"]); 
                $is_correct = false;
            } else if ($input['user_email'] == $result_select['user_name']) {
                echo json_encode(["status" => "error", "message" => "User_email already taken"]); 
                $is_correct = false;
            }

            // USER_PASSWORD \\
            if ($input['user_password'] == "") {
                echo json_encode(["status" => "error", "message" => "No user_password inserted"]); 
                $is_correct = false;
            } else if (strlen($input['user_password']) < 8) {
                echo json_encode(["status" => "error", "message" => "Insufficient user_password length"]); 
                $is_correct = false;
            }

            if (!$is_correct) {
                exit();
            }

            // INSERT Query
            $stmt = $pdo->prepare('INSERT INTO USER (user_name, user_email, user_password) VALUES (:user_name, :user_email, :user_password)');
            $stmt->execute(['user_name' => $input['user_name'], 'user_email' => $input['user_email'], 'user_password' => $hashed_password]);
            echo json_encode(["status" => "success", "message" => "User Registered successfully"]); 

        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid data"]);
    }
}
?>
