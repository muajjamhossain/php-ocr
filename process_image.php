<?php
if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES['image']['tmp_name'];
    $destination = 'uploads/' . basename($_FILES['image']['name']); // Ensure the 'uploads' directory exists and is writable

    if (move_uploaded_file($uploadedFile, $destination)) {
        // Execute the Python script with the uploaded image
        $command = escapeshellcmd("python process_image.py $destination");
        $output = shell_exec($command);

        // Return the OCR output to the client
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'output' => $output]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'File upload error: ' . $_FILES['image']['error']]);
}
