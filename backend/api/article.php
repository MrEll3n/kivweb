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

function handleGETRequest($pdo, $sessionMan, $endpoint, $getQueries) {
    try {
        if (count($endpoint) == 1) {
            if (isset($_GET['accepted'])) {
                handleAcceptedArticles($pdo, $_GET['accepted']);
            } else if (isset($_GET['moderation'])) {
                handleModerationArticles($pdo); 
            } else if (isset($_GET['page'])) {
                paginateArticles($pdo, false, $_GET['page']);
            } else {
                fetchAllArticles($pdo);
            }
        }
        if (count($endpoint) == 2) {
            fetchArticleById($pdo, $endpoint[1]);
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
        fetchAcceptedArticleById($pdo, $acceptedValue, $_GET['id']);
    } else {
        fetchArticlesByAcceptance($pdo, $acceptedValue);
    }
}

function handleModerationArticles($pdo) {
    $result = $pdo->query(
        "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
         FROM ARTICLE 
         JOIN USER ON ARTICLE.user_id = USER.user_id
         WHERE ARTICLE.accepted = 0 AND ARTICLE.reviewed = 0"
    )->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($result); 
}

function paginateArticles($pdo, $acceptedValue, $page) {
    $PAGE_LENGTH = 5;
    $PAGE_OFFSET = 5;
    $offset = ($page - 1) * $PAGE_OFFSET;

    $stmt = $pdo->prepare(
        "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
         FROM ARTICLE 
         JOIN USER ON ARTICLE.user_id = USER.user_id 
         WHERE ARTICLE.accepted = :accepted AND ARTICLE.reviewed = 0
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

function fetchAcceptedArticleById($pdo, $acceptedValue, $id) {
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
function fetchArticleById($pdo, $id) {
    $stmt = $pdo->prepare(
        "SELECT ARTICLE.article_id, ARTICLE.article_header, ARTICLE.article_content, ARTICLE.article_created, ARTICLE.article_image, ARTICLE.accepted, USER.user_name as article_author
         FROM ARTICLE 
         JOIN USER ON ARTICLE.user_id = USER.user_id 
         WHERE ARTICLE.article_id = :id"
    );

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
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
    } elseif (count($endpoint) == 3 && $endpoint[2] == 'reviewed') {
        updateArticleReviewed($pdo, $endpoint[1], $input['reviewed']);
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

    $image_path = uploadImage('article_image');
    echo $image_path;

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO ARTICLE (article_id, article_header, article_content, article_image, article_created, user_id, accepted) 
             VALUES (NULL, :article_header, :article_content, :article_image, current_timestamp(), :user_id, '0')"
        );

        $stmt->execute([
            'article_header' => $_POST['article_header'],
            'article_content' => $_POST['article_content'],
            'article_image' => $image_path,
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

function uploadImage($file_name) {
    echo $_FILES[$file_name];
    if (isset($_FILES[$file_name])) {
        $targetDir = dirname(__DIR__) . '/public/img/'; // Use absolute path
        $imageHash = uniqid();
        $imageExtension = strtolower(pathinfo($_FILES[$file_name]['name'], PATHINFO_EXTENSION));
        $targetFile = $targetDir . $imageHash . '.' . $imageExtension;
        $uploadOk = true;

        // Check if the file is an actual image
        $check = getimagesize($_FILES[$file_name]['tmp_name']);
        if ($check === false) {
            echo 'File is not an image.';
            $uploadOk = false;
        }

        // Check if the file already exists (highly unlikely with uniqid)
        if (file_exists($targetFile)) {
            echo 'File already exists.';
            $uploadOk = false;
        }

        // Check file size (500KB limit)
        if ($_FILES[$file_name]['size'] > 500000) {
            echo 'File is too large.';
            $uploadOk = false;
        }

        // Allow only certain file formats
        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (!in_array($imageExtension, $allowedTypes)) {
            echo 'Only JPG, JPEG, PNG, and GIF files are allowed.';
            $uploadOk = false;
        }

        // If everything is ok, try to upload the file
        if ($uploadOk) {
            // Ensure the target directory exists, create it if it doesn't
            if (!is_dir($targetDir)) {
                if (!mkdir($targetDir, 0777, true)) {
                    throw new RuntimeException(sprintf('Directory "%s" was not created', $targetDir));
                }
            } else {
                echo 'Directory already exists: ' . $targetDir;
            }


            if (move_uploaded_file($_FILES[$file_name]['tmp_name'], $targetFile)) {
                echo 'Image uploaded successfully.';
                return basename($targetFile);
            } else {
                echo 'Failed to upload image.';
            }
        } else {
            echo 'File was not uploaded due to errors.';
        }
    } else {
        echo 'No image file uploaded.';
    }
    return null;
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

function updateArticleReviewed($pdo, $articleId, $reviewed) {
    try {
        $stmt = $pdo->prepare("UPDATE ARTICLE SET reviewed = :reviewed WHERE article_id = :id");
        $stmt->execute([
            'reviewed' => $reviewed,
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

function handleDELETERequest($pdo, $sessionMan, $endpoint) {
    try {
        if (count($endpoint) == 2) {
            // Get the article ID from the endpoint
            $articleId = $endpoint[1];
            
            // Check if the article exists
            $stmt = $pdo->prepare("SELECT article_id FROM ARTICLE WHERE article_id = :id");
            $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Article exists, proceed with deletion
                deleteArticle($pdo, $articleId);
            } else {
                http_response_code(404);
                echo json_encode([
                    "status" => "404",
                    "message" => "Article not found"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => "Invalid endpoint"
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => $e->getMessage()
        ]);
    }
}

function deleteArticle($pdo, $articleId) {
    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Delete the article's associated image (optional but recommended)
        $stmt = $pdo->prepare("SELECT article_image FROM ARTICLE WHERE article_id = :id");
        $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($article && $article['article_image']) {
            $imagePath = dirname(__DIR__) . '/public/img/' . $article['article_image'];
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            }
        }

        // Delete the article from the database
        $stmt = $pdo->prepare("DELETE FROM ARTICLE WHERE article_id = :id");
        $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Respond with success
        http_response_code(200);
        echo json_encode([
            "message" => "Article deleted successfully"
        ]);
    } catch (PDOException $e) {
        // Rollback in case of error
        $pdo->rollBack();
        
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => $e->getMessage()
        ]);
    }
}

?>
