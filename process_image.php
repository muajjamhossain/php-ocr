<?php
if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES['image']['tmp_name'];
    $destination = 'uploads/' . $_FILES['image']['name']; // Ensure the 'uploads' directory exists and is writable

    if (move_uploaded_file($uploadedFile, $destination)) {
        echo "Image successfully uploaded to $destination";
        // Execute the Python script
        $command = escapeshellcmd("python3 process_image.py $destination");
        $output = shell_exec($command);
        echo  " Output is$output";
    } else {
        echo 'Failed to move uploaded file.';
    }
} else {
    echo 'File upload error: ' . $_FILES['image']['error'];
}
