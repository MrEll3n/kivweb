<?php
require 'sessionMan.php';

define("currentHost", "http://localhost:8080");

header("Access-Control-Allow-Origin: $currentHost");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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



$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = $_SERVER["REQUEST_URI"];

// Parse the URL to determine the endpoint
$endpoint = basename(parse_url($requestUri, PHP_URL_PATH));

// Include the appropriate file based on the endpoint
switch ($endpoint) {
    case 'login':
        include 'login.php';
        break;
    case 'register':
        include 'register.php';
        break;
    // Add more cases for different endpoints
    default:
        echo json_encode(["status" => "error", "message" => "Invalid endpoint"]);
        break;
}

$pdo = null;
$sessionMan = null;
?>
