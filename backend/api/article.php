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
        handleGETRequest($pdo, $sessionMan, $endpoint, $getQueries);
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

function handleGETRequest($pdo, $sessionMan, $endpoint, $getQueries) {
    //echo $getQueries;

    try {
        if (isset($_GET['accepted']) && $_GET['accepted'] === 'false') {
            if (isset($_GET['page'])) { 
                $PAGE_LENGTH = 5;
                $PAGE_OFFSET = 5;
                $offset = ($_GET['page'] - 1) * $PAGE_OFFSET;

                $stmt = $pdo->prepare(
                    "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
                     FROM ARTICLE 
                     JOIN USER ON ARTICLE.user_id = USER.user_id 
                     WHERE ARTICLE.accepted = 0 
                     LIMIT ? OFFSET ?"
                );

                $stmt->bindParam(1, $PAGE_LENGTH, PDO::PARAM_INT);
                $stmt->bindParam(2, $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode($result);
                exit();

            } else if (isset($_GET['id'])) {
                $stmt = $pdo->prepare(
                    "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
                     FROM ARTICLE 
                     JOIN USER ON ARTICLE.user_id = USER.user_id 
                     WHERE ARTICLE.article_id = :id AND ARTICLE.accepted = 0"
                );

                $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode($result);
                exit();

            } else {
                $result = $pdo->query(
                    "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
                     FROM ARTICLE 
                     JOIN USER ON ARTICLE.user_id = USER.user_id 
                     WHERE ARTICLE.accepted = 0"
                )->fetchAll(PDO::FETCH_ASSOC);
            }
        } else if (isset($_GET['accepted']) && $_GET['accepted'] === 'true') {
            if (isset($_GET['page'])) { 
                $PAGE_LENGTH = 5;
                $PAGE_OFFSET = 5;
                $offset = ($_GET['page'] - 1) * $PAGE_OFFSET;

                $stmt = $pdo->prepare(
                    "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
                     FROM ARTICLE 
                     JOIN USER ON ARTICLE.user_id = USER.user_id 
                     WHERE ARTICLE.accepted = 1 
                     LIMIT ? OFFSET ?"
                );

                $stmt->bindParam(1, $PAGE_LENGTH, PDO::PARAM_INT);
                $stmt->bindParam(2, $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode($result);
                exit();

            } else if (isset($_GET['id'])) {
                $stmt = $pdo->prepare(
                    "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
                     FROM ARTICLE 
                     JOIN USER ON ARTICLE.user_id = USER.user_id 
                     WHERE ARTICLE.article_id = :id AND ARTICLE.accepted = 1"
                );

                $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode($result);
                exit();

            } else {
                echo json_decode('test');
                $result = $pdo->query(
                    "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, 
                    ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
                    FROM ARTICLE JOIN USER ON ARTICLE.user_id = USER.user_id WHERE ARTICLE.accepted = 1"
                )->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode($result);
                exit();
            }
        } else {
            // Handle cases where 'accepted' parameter is not provided or is invalid
            http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => "Invalid or missing 'accepted' parameter"
            ]);
            return;
        }

        http_response_code(200);
        echo json_encode($result);

    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => $e->getMessage()
        ]);
    }
}



function handlePOSTRequest($pdo, $sessionMan, $endpoint) {
    $input = json_decode(file_get_contents("php://input"), true);
    //echo $input['article_image'];
    //echo count($endpoint);

    // Check for valid cookie token
    if (!checkCookieToken()) {
        http_response_code(401);
        exit();
    }

    $token = 0;
    $untrimmedToken = $_COOKIE['Authorization'];
    $token = trimToken($untrimmedToken);
    $currentUserID = $sessionMan->getUserId($token);

    if (count($endpoint) == 1) {
        // Check if required fields are set
        if (!isset($_POST['article_header']) || !isset($_POST['article_content'])) {
            http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => "Invalid arguments"
            ]);
            exit();
        }

        // Handle the file upload
        $article_image = null;
        if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['article_image']['tmp_name'];
            $article_image = file_get_contents($fileTmpPath); // Read the file content as binary
        } else {
            http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => "File upload error."
            ]);
            exit();
        }

        // Prepare and execute the database insert
        try {
            $stmt = $pdo->prepare("INSERT INTO `ARTICLE` (`article_id`, `article_header`, `article_content`, `article_image`, `article_created`, `user_id`, `accepted`) 
                                    VALUES (NULL, :article_header, :article_content, :article_image, current_timestamp(), :user_id, '0')");
            $stmt->execute([
                'article_header' => $_POST['article_header'],
                'article_content' => $_POST['article_content'],
                'article_image' => $article_image, // Store the binary data
                'user_id' => $currentUserID
            ]);
            http_response_code(200);
            echo json_encode([
                "message" => "Article successfully added for review"
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
    }

    if (count($endpoint) == 3) {
        if ($endpoint[2] == 'update') {
            try {
                $stmt = $pdo->prepare("UPDATE `ARTICLE` SET `accepted` = :bool WHERE `ARTICLE`.`article_id` = :id");
                $stmt->execute(['bool' => $input['accepted'], 'id' => $endpoint[1]]);
                $result = $stmt->fetch();
                http_response_code(200);
                echo json_encode([
                    "message" => "Article updated"
                ]);
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
    }
}
