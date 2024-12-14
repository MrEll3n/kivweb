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
    try {
        if (isset($_GET['accepted'])) {
            handleAcceptedArticles($pdo, $_GET['accepted']);
        } else {
            fetchAllArticles($pdo);
        }
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => $e->getMessage()
        ]);
    }
}

function handleAcceptedArticles($pdo, $accepted) {
    $isAccepted = filter_var($accepted, FILTER_VALIDATE_BOOLEAN);
    $acceptedValue = $isAccepted ? 1 : 0;

    if (isset($_GET['page'])) {
        paginateArticles($pdo, $acceptedValue, $_GET['page']);
    } elseif (isset($_GET['id'])) {
        fetchArticleById($pdo, $acceptedValue, $_GET['id']);
    } else {
        fetchArticlesByAcceptance($pdo, $acceptedValue);
    }
}

function paginateArticles($pdo, $acceptedValue, $page) {
    $PAGE_LENGTH = 5;
    $PAGE_OFFSET = 5;
    $offset = ($page - 1) * $PAGE_OFFSET;

    $stmt = $pdo->prepare(
        "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
         FROM ARTICLE 
         JOIN USER ON ARTICLE.user_id = USER.user_id 
         WHERE ARTICLE.accepted = :accepted 
         LIMIT :page_length OFFSET :offset"
    );

    $stmt->bindParam(':accepted', $acceptedValue, PDO::PARAM_INT);
    $stmt->bindParam(':page_length', $PAGE_LENGTH, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($result);
}

function fetchArticleById($pdo, $acceptedValue, $id) {
    $stmt = $pdo->prepare(
        "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
         FROM ARTICLE 
         JOIN USER ON ARTICLE.user_id = USER.user_id 
         WHERE ARTICLE.article_id = :id AND ARTICLE.accepted = :accepted"
    );

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':accepted', $acceptedValue, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($result);
}

function fetchArticlesByAcceptance($pdo, $acceptedValue) {
    $stmt = $pdo->prepare(
        "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
         FROM ARTICLE 
         JOIN USER ON ARTICLE.user_id = USER.user_id 
         WHERE ARTICLE.accepted = :accepted"
    );

    $stmt->bindParam(':accepted', $acceptedValue, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($result);
}

function fetchAllArticles($pdo) {
    $result = $pdo->query(
        "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
         FROM ARTICLE 
         JOIN USER ON ARTICLE.user_id = USER.user_id"
    )->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($result);
}

function handlePOSTRequest($pdo, $sessionMan, $endpoint) {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!checkCookieToken()) {
        http_response_code(401);
        exit();
    }

    $token = trimToken($_COOKIE['Authorization']);
    $currentUserID = $sessionMan->getUserId($token);

    if (count($endpoint) == 1) {
        createArticle($pdo, $currentUserID);
    } elseif (count($endpoint) == 3 && $endpoint[2] == 'update') {
        updateArticle($pdo, $endpoint[1], $input['accepted']);
    }
}

function createArticle($pdo, $currentUserID) {
    if (!isset($_POST['article_header']) || !isset($_POST['article_content'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => "Invalid arguments"
        ]);
        exit();
    }

    $article_image = handleFileUpload();

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO ARTICLE (article_id, article_header, article_content, article_image, article_created, user_id, accepted) 
             VALUES (NULL, :article_header, :article_content, :article_image, current_timestamp(), :user_id, '0')"
        );

        $stmt->execute([
            'article_header' => $_POST['article_header'],
            'article_content' => $_POST['article_content'],
            'article_image' => $article_image,
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

function handleFileUpload() {
    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['article_image']['tmp_name'];
        return file_get_contents($fileTmpPath);
    } else {
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => "File upload error."
        ]);
        exit();
    }
}

function updateArticle($pdo, $articleId, $accepted) {
    try {
        $stmt = $pdo->prepare("UPDATE ARTICLE SET accepted = :accepted WHERE article_id = :id");
        $stmt->execute([
            'accepted' => $accepted,
            'id' => $articleId
        ]);

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
        exit();
    }
}
?>
