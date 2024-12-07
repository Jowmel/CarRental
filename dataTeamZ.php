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

 

// Check if there are any validation errors
if (!empty($message)) {
    // Output the error messages or handle them as needed
    foreach ($message as $error) {
        echo $error . '<br>';
    }
} else {
    // Your database insertion logic goes here
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $mysqli = require __DIR__ . "/TEAMZconnection.php";

 
    $sql = "INSERT INTO usercredentials (username, password_hash)
            VALUES (?, ?)";

    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        die("SQL error: " . $mysqli->error);
    }

    // Use bind_param to bind parameters, including the image filename
    $stmt->bind_param("ss",
        $_POST["username"],
        $password_hash
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
