<?php
$imagePath = 'nid_2.jpg'; // Replace with the path to your image
$url = 'http://example.com/process_image.php'; // Replace with the actual URL of the second PHP file

// Initialize cURL
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'image' => new CURLFile($imagePath)
]);

// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    echo 'Response from server: ' . $response;
}

// Close cURL
curl_close($ch);
