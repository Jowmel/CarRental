







<?php
//code para makuha ang username ug image through user_id
session_start();

if(!isset($_SESSION['user_id'])){
	header("Location: LOGINteamZ.php");
	exit;
}

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/TEAMZconnection.php";
    $sql = "SELECT * FROM usercredentials WHERE id={$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    if ($result) {
        $user = $result->fetch_assoc();
 
        $imageSrc = (!empty($user["image"])) ? htmlspecialchars($user["image"]) : "path/to/default-image.jpg";

        
    } else {
        echo "Error fetching user data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TEAM Z - Temperature & Humidity</title>
<style>
body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #000000;
            margin: 0;
        }
        .container {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
        }
        .number {
            font-size: 48px;
            color: #333;
        }
        .status {
            font-size: 24px;
            color: #666;
            margin-top: 10px;
        }
		.btns{
		    position: absolute;
    top: 25px;
    right: 24px;
    background: cyan;
    color: #111;
	font-weight: bold;
    border: none;
    padding: 10px 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btns:hover {
    background-color: #ff6666;
}

</style> 
</head>
<body>

 
<button class="btns" type="button" onclick="logout()">Logout</button>  
<div class="container">

<h1>Welcome, <?= htmlspecialchars($user["username"]) ?>!</h1>
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQzGUzdAqHoNFFrIgyCuErovT9JjnBUXCEd5yxFItag3w&s" alt="Logo" class="logo">
        <h1>Distance Status</h1>
        <div class="number" id="distance">0</div>
        <div class="status" id="status">Status</div>
    </div>
    
    <script>
        function getRandomNumber(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function updateDistance() {
            const distanceElement = document.getElementById('distance');
            const statusElement = document.getElementById('status');
            const randomDistance = getRandomNumber(10, 220);
            distanceElement.textContent = randomDistance;

            let status;
            if (randomDistance >= 10 && randomDistance <= 80) {
                status = "Near";
            } else if (randomDistance >= 81 && randomDistance <= 150) {
                status = "Far";
            } else {
                status = "Very Far";
            }
            statusElement.textContent = status;

            // Set a new random interval between 1 and 5 seconds
            const randomInterval = getRandomNumber(3000, 8000);
            setTimeout(updateDistance, randomInterval);
        }

        // Initial call to set a random number and status immediately on load
        updateDistance();
		function logout() { 
    window.location.href = "logoutTEAMZ.php";
}
    </script>
</body>
</body>
</html>
