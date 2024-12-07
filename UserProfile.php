<?php
session_start();

if(!isset($_SESSION['user_id'])){
	header("Location: Login.php");
	exit;
}

// Check if the user is logged in
if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/datas.php";
    $sql = "SELECT * FROM user WHERE id={$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    if ($result) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Check if the user has an image
        $imageSrc = (!empty($user["image"])) ? htmlspecialchars($user["image"]) : "path/to/default-image.jpg";

        // Handle image upload
        if (isset($_FILES['image'])) {
            $file = $_FILES['image'];

            // Check for errors during file upload
            if ($file['error'] === UPLOAD_ERR_OK) {
                // Move the uploaded file to a directory (adjust the directory as needed)
                $uploadDir = 'upload/';
                $uploadFile = $uploadDir . basename($file['name']);

                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    // Update the user's image in the database
                    $newImagePath = $uploadFile;
                    $updateSql = "UPDATE user SET image = '$newImagePath' WHERE id = {$_SESSION["user_id"]}";
                    $updateResult = $mysqli->query($updateSql);

                    if ($updateResult) {
                        // Image updated successfully
                        $imageSrc = htmlspecialchars($newImagePath);
                    } else {
                        echo "Error updating image in the database.";
                    }
                } else {
                    echo "Error moving uploaded file.";
                }
            } else {
                echo "Error during file upload.";
            }
        }
    } else {
        echo "Error fetching user data.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Profile</title>
    <!-- This is for the logo seen above site-->
    <link rel="icon" href="static/phiwheel.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/phiwheel.png" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        body {
            background: url(static/userPbg.gif);
            background-repeat: no-repeat;
            background-size: cover;
            cursor: default;
        }

        .loader {
            position: absolute;
            z-index: 499;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff;
        }

        .loader .wd {
            position: absolute;
            font-size: 3em;
            left: 27%;
            top: 59%;
            font-family: consolas;
        }

        .nonev {
            animation: vanish 1.5s forwards;
        }

        @keyframes vanish {
            100% {
                visibility: hidden;
            }
        }

        .container {
            width: 100%;
            display: flex;
            margin-top: 5%;
            align-items: center;
            justify-content: center; 
        }

        .profile-card {
            width: 90%;
            max-width: 450px;
            text-align: center;
            background: #fff;
            color: #333;
            height: 500px;
            box-shadow: 0 0 19px #0ef;
			overflow: hidden;
        }

        .cover-pic {
            width: 100%;
            height: 250px;
            display: block;
        }

        .profile-pic {
            width: 140px;
            height: 100px;
            border-radius: 50%;
            margin-top: -60px;
            background: #666;
            transition: 0.4s ease;
            cursor: pointer;
            border: 5px solid #111;
        }

        .profile-pic:hover {
            transform: scale(1.1);
        }

        .profile-card h1 {
            font-weight: 600;
        }

        .follow-btn {
            display: inline-block;
            text-decoration: none;
            border: 1px solid #9bd;
            color: #111;
            padding: 6px 35px;
            margin: 25px 0;
            transition: 0.4s ease;
            box-shadow: 0 0 15px #888;
        }

        .follow-btn:hover {
            background: #0ef;
            color: white;
            border: 1px solid #bbb;
        }

        .view-btn {
            display: inline-block;
            text-decoration: none;
            background: #8ba;
            color: #fff;
            width: 100%;
            padding: 15px 0;
            margin-top: 21px;
            opacity: 0.5;
            transition: 0.4s ease;
        }

        .view-btn:hover {
            opacity: 1;
            background: #0ef;
        }

        .upload-input {
            display: none;
        }

        .save-btn {
			padding: 2px 6px 5px;
			width: 30px;
			height: 30px;
			cursor: pointer;
			border-radius: 30px;
			font-family: system-ui;
			text-align: center;
			outline: none;
			border: none;
			justify-content: center;
			position: absolute;
			left: 54%;
			transition: 0.2s ease;
			display: none;
        }
		.save-btn:hover{
			transform: scale(0.9);
		}
		.ccl-btn {
			padding: 2px 6px 5px;
			width: 30px;
			height: 30px;
			cursor: pointer;
			border-radius: 30px;
			font-family: system-ui;
			text-align: center;
			outline: none;
			border: none;
			justify-content: center;
			position: absolute;
			left: 44.5%;
			transition: 0.2s ease;
			display: none;
		}
		.overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
		.overlay .btn{
			cursor: pointer;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 2px;
			transition: 0.2s ease;
		}
		.overlay .btn:hover{
			background: transparent;
			border: 1px solid red;
			color: red;
			transform: scale(0.9);
		}
        .modal {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .modal h2 {
            margin-bottom: 20px;
        }

        .btns {
            cursor: pointer;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 2px;
			width: 100%;
			margin-top: 34px; 
			transition: 0.2s ease;
			opacity: 0.8;
        }

        .btns:hover {
            background-color: #2980b9;
			transform: scale(1.3);
			opacity: 1;
        }
    </style>
</head>
<body>
<div class="loader">
    <img src="static/loader.gif">
</div>
<form id="profileForm" method="post" enctype="multipart/form-data">
    <div class="container">
        <div class="profile-card">
            <img src="static/phiwheel.png" class="cover-pic">
            <img src="<?= $imageSrc ?>" class="profile-pic" onclick="triggerUploadInput()">
            <input type="file" name="image" class="upload-input" accept="image/*" onchange="displayImage(this)">
            <button type="button" class="save-btn" onclick="updateProfilePicture()"><img src="static/bx-check-circle.svg"></button> 
			<button type="button" class="ccl-btn" onclick="cancelPP()"><img src="static/bx-check-circle.svg"></button>

            <h1><?= htmlspecialchars($user["username"]) ?></h1>
            <p>User Profile</p>
            <a href="Home.php" class="follow-btn">Homepage</a>
           <button class="btns" type="button" onclick="showConfirmationModal()">Logout</button>

        </div>
    </div>
</form>
<div class="overlay" id="confirmationModal">
    <div class="modal">
        <h2>Confirmation</h2>
        <p>Are you sure you want to logout?</p><br>
        <button class="btn" onclick="cancelLogout()">Cancel</button>
        <button class="btn" onclick="logout()">Logout</button>
    </div>
</div>
<script>
    // Function to trigger the hidden file input when clicking the profile picture
    function triggerUploadInput() {
        const input = document.querySelector('.upload-input');
        input.click();
    }

    // Function to preview the selected image and display the Save button
    function displayImage(input) {
        const previewImage = document.querySelector('.profile-pic');
        const saveButton = document.querySelector('.save-btn');
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                previewImage.src = e.target.result;
            };

            reader.readAsDataURL(file);

            // Display the Save button
            saveButton.style.display = 'inline-block';
        }
    }

    // Function to update the profile picture in the database
	function updateProfilePicture() {
		const form = document.getElementById('profileForm');
		const formData = new FormData(form);

		fetch(window.location.href, {
			method: 'POST',
			body: formData
		})
		.then(response => response.text()) // Change json() to text()
		.then(data => {
			console.log(data);

			// You can add additional logic here if needed

			// Optionally, hide the Save button after updating
			document.querySelector('.save-btn').style.display = 'none';
		})
		.catch(error => {
			console.error('Error:', error);
		});
	}

	// Function to cancel the update and reset the image preview
    function cancelPP() {
        const previewImage = document.querySelector('.profile-pic');
        const saveButton = document.querySelector('.save-btn');
        const uploadInput = document.querySelector('.upload-input');

        // Reset the image preview
        previewImage.src = "<?= $imageSrc ?>";

        // Hide the Save button
        saveButton.style.display = 'none';

        // Clear the file input
        uploadInput.value = null;
    }

    // Loader
    setTimeout(function(){
        document.body.classList.remove('loading');
    }, 3500);
    var loader = document.querySelector(".loader");

    window.addEventListener("load", vanish);

    function vanish() {
        loader.classList.add("nonev");
    }
	
	function showConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        modal.style.display = 'flex';
    }

    function logout() { 
        window.location.href = "logout.php";
    }

    function cancelLogout() {
        const modal = document.getElementById('confirmationModal');
        modal.style.display = 'none';
    }
</script>
</body>
</html>
