<?php

if (!isset($_FILES['nid_image']) || $_FILES['nid_image']['error'] !== UPLOAD_ERR_OK) {
    echo "Error: No file uploaded or upload failed.";
    exit;
}

$filePath = $_FILES['nid_image']['tmp_name'];

if (!file_exists($filePath)) {
    echo "Error: File does not exist.";
    exit;
}

$imagePath = $filePath;
// $imagePath = 'nid_4.jpg';
$url = 'http://localhost/python/php-ocr/process_image.php';


$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'image' => new CURLFile($imagePath)
]);

$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    echo 'Response from server: ' . $response;
}

curl_close($ch);

$responseData = json_decode($response, true);
