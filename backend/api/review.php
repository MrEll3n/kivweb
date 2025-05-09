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
    //echo $_COOKIE['Authorization'];

    if (count($endpoint) == 1) {
        try {
            // If there is no query
            if (!isset($_GET['page'])) {
                    $result = $pdo->query("SELECT 
                        REVIEW.review_id,
                        ARTICLE.article_id, 
                        ARTICLE.article_header, 
                        ARTICLE.article_content, 
                        ARTICLE.article_created, 
                        ARTICLE.article_image, 
                        USER.user_name AS article_author,
                        REVIEW.finished
                    FROM 
                        REVIEW 
                    JOIN 
                        ARTICLE ON REVIEW.article_id = ARTICLE.article_id 
                    JOIN 
                        USER ON ARTICLE.user_id = USER.user_id
                    WHERE 
                        REVIEW.finished = 0"
                    )->fetchAll();
                http_response_code(200);
                echo json_encode($result);
                exit();
            }

            // If there is a page and size query
            if (isset($_GET['page']) && $_GET['page'] > 0) {
                $PAGE = (int)$_GET['page']; // Cast to integer for safety
                $SIZE = 5;
                
                // Calculate the offset
                $OFFSET = ($PAGE - 1) * $SIZE;
            
                // Prepare the SQL statement
                $stmt = $pdo->prepare("SELECT 
                    REVIEW.review_id,
                    ARTICLE.article_id, 
                    ARTICLE.article_header, 
                    ARTICLE.article_content, 
                    ARTICLE.article_created, 
                    ARTICLE.article_image, 
                    USER.user_name AS article_author,
                    REVIEW.finished
                FROM 
                    REVIEW 
                JOIN 
                    ARTICLE ON REVIEW.article_id = ARTICLE.article_id 
                JOIN 
                    USER ON ARTICLE.user_id = USER.user_id
                WHERE 
                    REVIEW.finished = 0
                LIMIT :PAGE_LENGTH OFFSET :PAGE_OFFSET"); 
            
                // Bind the parameters
                $stmt->bindParam(':PAGE_LENGTH', $SIZE, PDO::PARAM_INT);
                $stmt->bindParam(':PAGE_OFFSET', $OFFSET, PDO::PARAM_INT);
            
                // Execute the statement
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
            
                // Set the response code and return the result
                http_response_code(200);
                echo json_encode($result);
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
        $review_id = $endpoint[1];

        try {
            $stmt = $pdo->prepare("SELECT review_id, article_id, user_id FROM `REVIEW` WHERE review_id = :review_id"); 
            $stmt->execute(['review_id' => $review_id]);
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
        $review_id = $endpoint[1];
        $item = '';
        $option = '';

        switch ($endpoint[2]) {
            case 'review_id':
                $item = 'review_id';
                break;
            case 'user_id':
                $item = 'user_id';
                break;
            case 'article_id':
                $item = 'article_id';
                break;
            case 'article':
                $item = null;
                $option = 'article';
                break;
            case 'user':
                $item = null;
                $option = 'user';
                break;
            default:
                http_response_code(400);
                echo json_encode([
                    "status" => "400", 
                    "message" => "Invalid selector"
                ]);
                exit();
        }

        if ($item != null) {
            try {
                $stmt = $pdo->prepare("SELECT $item FROM `REVIEW` WHERE review_id = :review_id"); 
                $stmt->execute(['review_id' => $review_id]);
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
        } else if ($option == 'article') {
            try {
                $stmt = $pdo->prepare("SELECT 
                    REVIEW.review_id,
                    ARTICLE.article_id, 
                    ARTICLE.article_header, 
                    ARTICLE.article_content, 
                    ARTICLE.article_created, 
                    ARTICLE.article_image, 
                    USER.user_name AS article_author,
                    REVIEW.finished
                FROM 
                    REVIEW 
                JOIN 
                    ARTICLE ON REVIEW.article_id = ARTICLE.article_id 
                JOIN 
                    USER ON ARTICLE.user_id = USER.user_id 
                WHERE 
                    REVIEW.review_id = :review_id"); 
                $stmt->execute(['review_id' => $review_id]);
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
        } else if ($option == 'user') {
            try {
                $stmt = $pdo->prepare("SELECT 
                    USER.user_id,
                    USER.user_email,
                    USER.user_name AS article_author,
                    USER.perm_id
                FROM 
                    REVIEW 
                JOIN 
                    USER ON REVIEW.user_id = USER.user_id 
                WHERE 
                    REVIEW.review_id = :review_id"); 
                $stmt->execute(['review_id' => $review_id]);
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
}

function handlePOSTRequest($pdo, $sessionMan, $endpoint) {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!checkCookieToken()) {
        http_response_code(401);
        exit();
    }

    $token = 0;
    $untrimmedToken = $_COOKIE['Authorization'];
    $token = trimToken($untrimmedToken);
    
    $currentUserID = $sessionMan->getUserId($token);

    // Check if we are creating a new review
    if (count($endpoint) == 2 && $endpoint[1] == 'create') {
        try {
            // Prepare the SQL statement to insert a new review
            $stmt = $pdo->prepare("INSERT INTO `REVIEW` (article_id, user_id, finished) VALUES (:article_id, :user_id, :finished)");
            $stmt->execute([
                'article_id' => $input['article_id'], // Assuming the input contains the article_id
                'user_id' => $currentUserID, // Use the current user's ID
                'finished' => 0 // Assuming the input contains the finished status
            ]);

            // Get the ID of the newly created review
            $review_id = $pdo->lastInsertId();

            http_response_code(201); // Created
            echo json_encode([
                "message" => "Review created successfully",
                "review_id" => $review_id
            ]);
            exit();
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode([
                "status" => "400",
                "message" => $e->getMessage()
            ]);
            echo "285";
            exit();
        }
    }

    // Existing update logic
    if (count($endpoint) == 3) {
        if ($endpoint[2] == 'update') {
            try {
                $stmt = $pdo->prepare("UPDATE `REVIEW` SET `finished` = :bool WHERE `REVIEW`.`review_id` = :id");
                $stmt->execute(['bool' => $input['finished'], 'id' => $endpoint[1]]);
                http_response_code(200);
                echo json_encode([
                    "message" => "Review updated"
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
    }
}

function getUser($pdo, $review_id) {
    $stmt = $pdo->prepare("SELECT  FROM `REVIEW` WHERE review_id = :review_id"); 
    $stmt->execute(['review_id' => $review_id]);
    $result = $stmt->fetch();
}

function handleDELETERequest($pdo, $sessionMan, $endpoint) {
    // DELETE method to remove a review from the database
    if (count($endpoint) == 2) {
        $review_id = $endpoint[1];

        // Ensure the user is authenticated
        if (!checkCookieToken()) {
            http_response_code(401);
            exit();
        }

        $token = trimToken($_COOKIE['Authorization']);
        $currentUserID = $sessionMan->getUserId($token);

        try {
            // Get the user's permission level
            $stmt = $pdo->prepare("SELECT PERMISSIONS.perm_weight FROM `USER` JOIN PERMISSIONS ON PERMISSIONS.perm_id = USER.perm_id WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $currentUserID]);
            $user = $stmt->fetch();

            // Check if the user is an admin (perm_id == 2) or the review creator
            if ($user['perm_weight'] < 4) { // Not an admin
                // Check if the review exists and is associated with the current user
                $stmt = $pdo->prepare("SELECT user_id FROM `REVIEW` WHERE review_id = :review_id");
                $stmt->execute(['review_id' => $review_id]);
                $review = $stmt->fetch();

                // If the review does not exist or the user is not the creator, return error
                if (!$review || $review['user_id'] !== $currentUserID) {
                    http_response_code(403);
                    echo json_encode([
                        "status" => "403",
                        "message" => "Forbidden: You can only delete your own reviews."
                    ]);
                    exit();
                }
            }

            // Proceed with deleting the review
            $stmt = $pdo->prepare("DELETE FROM `REVIEW` WHERE review_id = :review_id");
            $stmt->execute(['review_id' => $review_id]);

            http_response_code(200);
            echo json_encode([
                "message" => "Review deleted successfully"
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
    } else {
        // Return error if the ID is missing in the URL
        http_response_code(400);
        echo json_encode([
            "status" => "400",
            "message" => "Review ID is required for deletion"
        ]);
        exit();
    }
}
