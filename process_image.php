<?php
if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES['image']['tmp_name'];
    $originalName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $timestamp = time(); // Current timestamp
    $destination = "uploads/{$originalName}-{$timestamp}.{$extension}";
    
    if (move_uploaded_file($uploadedFile, $destination)) {
        $command = escapeshellcmd("python process_image.py $destination");
        $output = shell_exec($command);

        header('Content-Type: application/json');
        // echo json_encode(['success' => true, 'output' => $output]);
        echo json_encode(['success' => true, 'output' => json_decode($output, true)], JSON_UNESCAPED_UNICODE);

    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'File upload error: ' . $_FILES['image']['error']]);
}
