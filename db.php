<?php
$message = [];

if (empty($_POST["username"])) {
    $message['username'] = 'Username is required.';
}

if (strlen($_POST["password"]) < 8) {
    $message['password'] = 'Password must be at least 8 characters.';
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    $message['password'] = 'Password must contain at least one letter.';
}

if (!preg_match("/[A-Z]/", $_POST["password"])) {
    $message['password'] = 'Password must contain at least one uppercase letter.';
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    $message['password'] = 'Password must contain at least one number.';
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    $message['password_confirmation'] = 'Passwords do not match.';
}

// Validate and process image upload
$uploadDir = 'upload/';

if (!empty($_FILES['image']['name'])) {
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);

    // Check if the file is an image
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

    if (!in_array($imageFileType, $allowedExtensions)) {
        $message['image'] = 'Invalid image format. Please upload a valid image.';
    }

    // Move the uploaded file to the specified directory
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        $message['image'] = 'Error uploading image.';
    }
} else {
    $message['image'] = 'Image is required.';
}

// Check if there are any validation errors
if (!empty($message)) {
    // Output the error messages or handle them as needed
    foreach ($message as $error) {
        echo $error . '<br>';
    }
} else {
    // Your database insertion logic goes here
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $mysqli = require __DIR__ . "/datas.php";

    $imagePath = $uploadDir . basename($_FILES['image']['name']);

    $sql = "INSERT INTO user (username, password_hash, image)
            VALUES (?, ?, ?)";

    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        die("SQL error: " . $mysqli->error);
    }

    // Use bind_param to bind parameters, including the image filename
    $stmt->bind_param("sss",
        $_POST["username"],
        $password_hash,
        $imagePath
    );

    if ($stmt->execute()) {
        header("Location: signupSuccessful.html");
        exit;
    } else {
        if ($mysqli->errno === 1062) {
            die("Username already taken");
        } else {
            die($mysqli->error . "" . $mysqli->errno);
        }
    }
}
?>
