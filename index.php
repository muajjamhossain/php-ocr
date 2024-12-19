<?php

if (!isset($_FILES['nid_image']) || $_FILES['nid_image']['error'] !== UPLOAD_ERR_OK) {
    echo "Error: No file uploaded or upload failed.";
    exit;
}

// Step 1: Handle File Upload
$filePath = $_FILES['nid_image']['tmp_name'];
$uploadsDir = 'uploads/';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true); // Create the uploads directory if it doesn't exist
}
$imageName = basename($_FILES['nid_image']['name']);
$timestamp = time(); // Add timestamp to avoid overwriting files
$uploadedImagePath = $uploadsDir . pathinfo($imageName, PATHINFO_FILENAME) . "-{$timestamp}." . pathinfo($imageName, PATHINFO_EXTENSION);

if (!move_uploaded_file($filePath, $uploadedImagePath)) {
    echo "Error: Failed to save the uploaded file.";
    exit;
}

// Step 2: Process Image Using Python Script
$command = escapeshellcmd("python process_image.py $uploadedImagePath");
$output = shell_exec($command);

// Check if Python script executed successfully
if ($output === null) {
    echo "Error: Failed to execute the Python script.";
    exit;
}

// Decode Python script output
$responseData = json_decode($output, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error: Failed to decode the Python script output.";
    exit;
}

// Step 3: Display Results
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
        <pre><?php echo htmlspecialchars(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)); ?></pre>
    </div>
</body>
</html>
