<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../sessionMan.php';
require '../utils.php';

define("CURRENT_HOST", 'http://localhost:3000');
define("BASE_PATH", "/kivweb/backend/api/");

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-HTTP-Method-Override, Accept");
header("Access-Control-Expose-Headers: Access-Token, Uid");

// Database connection settings
$host = "localhost";
$port = "3306";
$dbname = "kiv_web_db";
$username = "root";
$password = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];



try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $e->getMessage()]);
    exit();
}

// Session Manager
$sessionMan = new SessionMan($pdo);

[$finalPath, $endpoint, $getQueries] = getApiPath(); 
//echo $endpoint[1];

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = $_SERVER["REQUEST_URI"];

//echo $finalPath[0];
// Parse the URL to determine the endpoint
//$endpoint = basename(parse_url($requestUri, PHP_URL_PATH));

// Include the appropriate file based on the endpoint
switch ($endpoint[0]) {
    case 'login':
        require 'login.php';
        break;
    case 'register':
        require 'register.php';
        break;
    case 'auth':
        require 'auth.php';
        break;
    case 'users':
        require 'users.php';
        break;
    case 'article':
        require 'article.php';
        break;
    case 'token':
        require 'token.php';
        break;
    case 'perms':
        require 'perms.php';
        break;
    default:
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid endpoint"]);
        break;
}

$pdo = null;
$sessionMan = null;
?>
