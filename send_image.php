<?php

if (!isset($_FILES['nid_image']) || $_FILES['nid_image']['error'] !== UPLOAD_ERR_OK) {
    echo "Error: No file uploaded or upload failed.";
    exit;
}

$filePath = $_FILES['nid_image']['tmp_name'];

$uploadsDir = 'uploads/';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}
$imageName = basename($_FILES['nid_image']['name']);
$uploadedImagePath = $uploadsDir . $imageName;

if (!move_uploaded_file($filePath, $uploadedImagePath)) {
    echo "Error: Failed to save the uploaded file.";
    exit;
}

$url = 'http://localhost/python/php-ocr/process_image.php';
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'image' => new CURLFile($uploadedImagePath)
]);

$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    exit;
}

curl_close($ch);

$responseData = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCR Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }
        img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        .response {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: inline-block;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>OCR Result</h1>
    <h3>Uploaded Image:</h3>
    <img src="<?php echo htmlspecialchars($uploadedImagePath); ?>" alt="Uploaded Image">
    <h3>Server Response:</h3>
    <div class="response">
        <pre><?php echo htmlspecialchars($response); ?></pre>
    </div>
</body>
</html>
