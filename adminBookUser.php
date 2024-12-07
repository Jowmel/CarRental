<?php
// adminBookUser.php

// Check if user_id parameter is set
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Connect to the database (replace with your credentials)
    $host = "localhost";
    $dbname = "phiwheeldb";
    $username = "root";
    $password = "";

    $mysqli = new mysqli($host, $username, $password, $dbname);

    if ($mysqli->connect_errno) {
        die("Connection error: " . $mysqli->connect_error);
    }

    // Fetch user details including the image
    $userSql = "SELECT * FROM user WHERE id = $userId";
    $userResult = $mysqli->query($userSql);

    // Fetch booked vehicles for the specified user
    $bookingSql = "SELECT * FROM booking WHERE user_id = $userId";
    $bookingResult = $mysqli->query($bookingSql);

    // Start HTML
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="static/adminL.jpeg" type="image/x-icon">
        <link rel="shortcut icon" href="static/adminL.jpeg" type="image/x-icon">
        <title>Booked Vehicles</title>
        <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ddd;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            opacity: 0;
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px;
            animation: fadeIn 0.5s forwards;
			margin-top: 1px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 10px 0;
            padding: 15px;
            transition: transform 0.3s;
            animation: fadeInUp 0.5s forwards;
        }

        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .card:hover {
            transform: scale(1.05);
        }

        h2 {
            color: #333;
        }

        strong {
            color: #555;
        }

        .back-link {
            display: block;
            text-align: center;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
			top: 1%;
			position: absolute;
			transition: 0.3s ease;
        }

        .back-link:hover {
            text-decoration: underline;
            color: #fff;
			background: #888;
			width: 100%;
        }
		span{
			color: red;
			font-size: 2.5em;
		}
    </style>
    </head>
    <body>
	<h2 style="margin-top: -35%; margin-left: -15%"><span>B</span>ooked Vehicles</h2>
         <div class="container"> 
            <?php
            if ($userResult->num_rows > 0) {
                $userRow = $userResult->fetch_assoc();
                echo '<img src="' . $userRow["image"] . '" alt="User Image" style="width: 100px; height: 100px; border-radius: 50%;">';
            }
 
            if ($bookingResult->num_rows > 0) {
                while ($row = $bookingResult->fetch_assoc()) {
                    echo '<div class="card">';
                    echo '<strong>Booking ID:</strong> ' . $row["booking_id"] . '<br>';
                    echo '<strong>User ID:</strong> ' . $row["user_id"] . '<br>';
                    echo '<strong>Vehicle ID:</strong> ' . $row["vehicle_id"] . '<br>';
                    echo '<strong>Vehicle Name:</strong> ' . $row["vehicle_name"] . '<br>';
                    echo '<strong>Payment Method:</strong> ' . $row["payment_method"] . '<br>';
                    echo '<strong>Additional Info:</strong> ' . $row["additional_info"] . '<br>';
                    // Add more details as needed
                    echo '</div>';
                }
            } else {
                echo "No booked vehicles found for this user.";
            }

            // Close the database connection
            $mysqli->close();
            ?>
        </div>
		
        <a class="back-link" href="javascript:history.back()">Back to Users</a>
    </body>
    </html>

    <?php
} else {
    // Handle the case when user_id parameter is not set
    echo "User ID not specified.";
}
?>