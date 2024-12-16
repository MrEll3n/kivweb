<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin

// Directory where images are stored
$imageDirectory = 'kivweb/backend/public/img/';
$imagePhysicalDir = dirname(__DIR__) . '/public/img/'; // Correct the path

// Get the image name from the query parameter
$imageName = isset($_GET['image_name']) ? $_GET['image_name'] : null;

// Check if an image name was provided
if ($imageName) {
    // Construct the full path to the image
    $filePath = $imageDirectory . basename($imageName);
    $filePathPhys = $imagePhysicalDir . basename($imageName); // Use basename to prevent directory traversal

    // Check if the file exists and is an image
    if (is_file($filePathPhys) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $filePath)) {
        // Construct the URL for the image
        $response['url'] = 'http://localhost:8080/' . $filePath;
    } else {
        // Image not found
        $response['error'] = 'Image not found or invalid image type.';
    }
} else {
    // No image name provided
    $response['error'] = 'No image name specified.';
}

// Return the response as a JSON object
echo json_encode($response);
?>
