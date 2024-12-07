<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create an account</title>
    <!-- This is for the logo seen above site-->
    <link rel="icon" href="static/phiwheel.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/phiwheel.png" type="image/x-icon">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-4o5+P6+eB1pPw5lU0OAFI0N6P1ZKJr45+5cHTp6p1cENlOzoOqV6QnvjMWKl2Al/O0JMx2aH76MiWEMN4+OPRg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="static/regValidate.js" defer></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #081b29;
            overflow: hidden;
        }

        .wrapper {
            position: relative;
            width: 750px;
            height: 450px;
            background: transparent;
            border: 2px solid #0ef;
            overflow: hidden;
            box-shadow: 0 0 15px #0ef;
            border-radius: 5px;
        }

        .wrapper .form-box {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-box h2 {
            font-size: 32px;
            color: #ffff;
            text-align: center;
        }

        .form-box .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin: 25px 0;
        }

        .input-box input {
            position: absolute;
            width: 90%; /* Adjusted width */
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            border-bottom: 2px solid #fff;
            transition: 0.5s;
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            padding-right: 23px;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            font-size: 16px;
            color: #fff;
            pointer-events: none;
            transition: 0.5s;
        }

        .input-box img {
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            color: #fff;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
        }

        .btn {
            position: relative;
            width: 100%;
            height: 45px;
            background: transparent;
            border: 2px solid #0ef;
            outline: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            z-index: 1;
            font-weight: 600;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: -110%;
            left: 0;
            width: 100%;
            height: 300%;
            background: linear-gradient(#081b29, #0ef, #081b29, #0ef);
            z-index: -1;
            transition: 0.5s;
        }

        .btn:hover::before {
            top: 0;
        }

        .form-box .logreg-link {
            font-size: 14.5px;
            color: #fff;
            text-align: center;
            margin: 20px 0 10px;
        }

        .logreg-link p a {
            color: #0ef;
            text-decoration: none;
            font-weight: 600;
        }

        .logreg-link p a:hover {
            text-decoration: underline;
        }

        .wrapper .form-box {
            right: 0;
            padding: 0 40px 0 60px;
        }

        .input-box input:focus~label,
        .input-box input:valid~label {
            top: -5px;
            color: #0ef;
        }

        .input-box input:focus,
        .input-box input:valid {
            border-bottom-color: #0ef;
        }

        .wrapper .info-text {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .wrapper .info-text.login {
            left: 0;
            text-align: left;
            padding: 0 150px 60px 40px;
        }

        .info-text h2 {
            font-size: 36px;
            color: #fff;
            line-height: 1.3;
            text-transform: uppercase;
        }

        .info-text p {
            font-size: 16px;
            color: #fff;
        }

        .wrapper .bg-animate {
            position: absolute;
            top: -4px;
            left: 0.2%;
            width: 360px;
            height: 600px;
            background: linear-gradient(45deg, #081b29, #0ef);
            transform-origin: bottom right;
            border-bottom: 3px solid #0ef;
        }

        .wrapper .bg-animate2 {
            position: absolute;
            top: 100%;
            left: 250px;
            width: 850px;
            height: 700px;
            background: #081b29;
            transform-origin: bottom left;
            border-top: 3px solid #0ef;
        }

        .loader {
            position: absolute;
            z-index: 4000;
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
            animation: vanish .2s forwards;
        }

        @keyframes vanish {
            100% {
                visibility: hidden;
            }
        }

        #image-container {
            position: relative;
            padding: 5px 5px 25px;
            border-radius: 50px;
            color: #fff;
            overflow: hidden;
            display: inline-block;
            border-radius: 10px;
        }

        #image-container input[type="file"] {
            display: none;
        }

        #image-container label {
            cursor: pointer;
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            display: block;
        }

        #image-container label:hover {
            background-color: #2980b9;
        }

        .preview-img {
            top: 25%;
            left: 23%;
            height: 50%;
            width: 50%;
            border-radius: 50%;
            outline: none;
            border: none;
            position: absolute;
            border: 1px solid #fff;
            box-shadow: 0 12px 9px #222;
        }

        /* Add the following style for the eye icon */
        .input-box .show-password {
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="loader">
        <img src="static/loader.gif">
    </div>

    <div class="wrapper">
        <span class="bg-animate"></span>
        <span class="bg-animate2"></span>
        <div class="form-box register"><br>
            <h2>Sign Up</h2>
            <form method="post" action="db.php" id="signup" enctype="multipart/form-data">
                <div class="input-box">
                    <input type="text" id="username" name="username" autocomplete="off">
					<?php if (isset($message['username'])) echo $message['username']; ?>
                    <label>Username</label>
                    <img src="static/bxs-user.svg" style="background: #ddd">
                </div>
                <div class="input-box">
        <input type="password" id="password" name="password" autocomplete="off">
		<?php if (isset($message['password'])) echo $message['password']; ?>
        <label>Password</label>
		<img src="static/bxs-lock-alt.svg" style="background: #ddd;">
    </div>
    <div class="input-box">
        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="off">
		<?php if (isset($message['password_confirmation'])) echo $message['password_confirmation']; ?>
        <label>Confirm Password</label>
		<img src="static/bxs-lock-alt.svg" style="background: #ddd;"> 
    </div>
                <div id="image-container">
                    <label for="image" class="file-input-label">Upload Image</label>
                    <input type="file" id="image" name="image" onchange="updateFileLabel()">
					 <?php if (isset($message['image'])) echo $message['image']; ?>
                </div>
                <div class="validation-message"></div>
                <button class="btn">Register</button>
                <div class="logreg-link">
                    <p>Already have an account? <a href="Login.php" class="register-link">Login</a></p>
                </div>
            </form>
        </div>
        <div class="info-text login">
            <h1 style="width: 100%; position: absolute; top: 5%;left: 2.5%; font-family: system-ui;color:#ddd">Preview Profile Image</h1>
            <img src="upload/default.png" class="preview-img">
        </div>
    </div>

    <script>
        var loader = document.querySelector(".loader")

        window.addEventListener("load", vanish);

        function vanish() {
            loader.classList.add("nonev");
        }

        function updateFileLabel() {
            const input = document.getElementById('image');
            const label = document.querySelector('.file-input-label');
            const previewImage = document.querySelector('.info-text.login img');

            if (input.files.length > 0) {
                label.textContent = input.files[0].name;
                // Set the src attribute of the preview image
                previewImage.src = URL.createObjectURL(input.files[0]);
            } else {
                label.textContent = 'Upload Image';
                // Reset the src attribute when no file is selected
                previewImage.src = '';
            }
        }

        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.querySelector(`#${inputId} + .show-password`);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = '👁️';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = '🔒';
            }
        }
    </script>
</body>

</html>

