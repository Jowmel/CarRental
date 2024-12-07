<?php
    
    // Validate and process image upload
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'upload/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

        if (!in_array($imageFileType, $allowedExtensions)) {
            die("Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            echo "Image has been uploaded successfully.";
        } else {
            die("Error uploading image.");
        }
    }

	

    $mysqli = require __DIR__ . "/datas.php";

    $sql = "INSERT INTO user (image)
            VALUES (?)";

    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        die("SQL error: " . $mysqli->error);
    }

    // Use bind_param to bind parameters, including the image filename
    $stmt->bind_param("s",
        basename($_FILES['image']['name'])
    );


?>
