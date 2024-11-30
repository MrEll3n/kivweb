<?php

//header("Access-Control-Allow-Origin: CURRENT_HOST");
//header("Content-Type: application/json");
//header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//header("Access-Control-Expose-Headers: Access-Token, Uid");



switch ($requestMethod) {
    case 'GET':
        handleGETRequest($pdo, $sessionMan);
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

    try {
        if (isset($_GET['page'])) {
            $PAGE_LENGTH = 5;
            $PAGE_OFFSET = 5;

            $stmt = $pdo->prepare("SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, USER.user_name as article_author
                FROM ARTICLE JOIN USER ON ARTICLE.user_id = USER.user_id LIMIT :PAGE_LENGTH OFFSET :PAGE_OFFSET"
            ); 

            $stmt->execute(['PAGE_LENGTH' => $PAGE_LENGTH,'PAGE_OFFSET' => ($_GET['page'] - 1)*$PAGE_OFFSET]);
            $result = $stmt->fetchAll();

        } else if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, USER.user_name as article_author
                FROM ARTICLE JOIN USER ON ARTICLE.user_id = USER.user_id WHERE article_id = :id"
            ); 

            $stmt->execute(['id' => $_GET['id']]);
            $result = $stmt->fetch();

        } else {
            $result = $pdo->query("SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, USER.user_name as article_auth
                FROM ARTICLE JOIN USER ON ARTICLE.user_id = USER.user_id"
            )->fetchAll();
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