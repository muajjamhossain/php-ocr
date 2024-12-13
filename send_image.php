<?php
$imagePath = 'nid_2.jpg';
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
