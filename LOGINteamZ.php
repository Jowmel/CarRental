<?php
session_start();

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $mysqli = require __DIR__ . "/TEAMZconnection.php";
    $sql = sprintf("SELECT * FROM usercredentials WHERE username = '%s'", $mysqli->real_escape_string($_POST["username"]));

    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($_POST["password"], $user["password_hash"])) {
            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["is_admin"] = false; // Set to false for regular users
            header("Location: fetchdata_teamZ.php");
            exit;
        }
    }

    $is_invalid = true;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/phiwheel.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/phiwheel.png" type="image/x-icon">
<style> 
*{
	margin: 0;
	padding: 0;
	box-sizing: border-box;
	font-family: 'Poppins', sans-serif;
}
body{
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 100vh;
	background: #081b29;
}
.wrapper{
	position: relative;
	width: 750px;
	height: 450px;
	background: transparent;
	border: 2px solid #0ef;
	overflow: hidden;
	box-shadow: 0 0 15px #0ef;
	border-radius: 5px;
}
.wrapper .form-box{
	position: absolute;
	top: 0;
	width: 50%;
	height: 100%; 
	display: flex;
	flex-direction: column;
	justify-content: center;
}
.form-box h2{
	font-size: 32px;
	color: #ffff;
	text-align: center;
}
.form-box .input-box{
	position: relative;
	width: 100%;
	height: 50px; 
	margin: 25px 0;
}
.input-box input{
	position: absolute;
	width: 100%;
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
.input-box label{
	position: absolute;
	top: 50%;
	left: 0;
	transform: translateY(-50%);
	font-size: 16px;
	color: #fff;
	pointer-events: none;
	transition: 0.5s;
}
.input-box img{
	position: absolute;
	top: 50%;
	right: 0;
	transform: translateY(-50%);
	color: #fff;
	font-size: 18px;
	border-radius: 50px;
}
.btn{
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
.btn::before{
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
.btn:hover::before{
	top: 0;
}
.form-box .logreg-link{
	font-size: 14.5px;
	color: #fff;
	text-align: center;
	margin: 20px 0 10px;
}
.logreg-link p a{
	color: #0ef;
	text-decoration: none;
	font-weight: 600;
}
.logreg-link p a:hover{
	text-decoration: underline;
}
.wrapper .form-box{
	left: 0;
	padding: 0 60px 0 40px; 
}
.input-box input:focus~label,
.input-box input:valid~label{
	top: -5px;
	color: #0ef;
}
.input-box input:focus,
.input-box input:valid{
	border-bottom-color: #0ef;
} 
.wrapper .info-text{
	position: absolute;
	top: 0;
	width: 50%;
	height: 100%; 
	display: flex;
	flex-direction: column;
	justify-content: center;
}
.wrapper .info-text.login{
	right: 0;
	text-align: right;
	padding: 0 40px 60px 150px; 
}
.info-text h2{
	font-size: 36px;
	color: #fff;
	line-height: 1.3;
	text-transform: uppercase;
	margin-left: -40px;
	width: 100%;
	text-align: center;
}
.info-text p{
	font-size: 16px;
	color: #fff;
	margin-left: -40px;
	width: 100%;
	text-align: center;
}
.wrapper .bg-animate{
	position: absolute;
	top: -4px;
	right: 0;
	width: 360px;
	height: 600px;
	background: linear-gradient(45deg, #081b29, #0ef); 
	transform-origin: bottom right;
	border-bottom: 3px solid #0ef; 
} 
.loader{
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
.loader .wd{
	position: absolute;
	font-size: 3em;
	left: 27%;
	top: 59%;
	font-family: consolas;
}
.nonev{
	animation: vanish .2s forwards;
}
@keyframes vanish{
	100%{
		visibility: hidden;
	}
	
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
	<div class="form-box login">
		<h2>Login</h2>
		<form method="post">
			<div class="input-box">
				<input type="text" name="username" autofocus id="username" autocomplete="off" value="<?= htmlspecialchars($_POST["username"] ?? "") ?>">
				<label>Username</label>
				<img src="static/bxs-user.svg" style="background: #ddd;"> 
			</div> 
			<div class="input-box">
				<input type="password" name="password" id="password" autocomplete="off">
				<label>Password</label>
				<img src="static/bxs-lock-alt.svg" style="background: #ddd;">
			</div> 
			<button class="btn">Login</button>
			<div class="logreg-link">
				<p>Don't have an account? <a href="IOTteamZREG.php" class="register-link">Sign Up</a></p>
			</div><br>
			<?php 
				if($is_invalid):
			?><em style="color: red; margin-left: 22%;">Invalid credentials</em>
			<?php
				endif;
			?>
		</form> 
	</div>
	<div class="info-text login">
				<h2>Welcome <span style="font-size: 0.5em; opacity: 0.6; color: #041000">We are TeamZ!</span></h2><br><br>
				<p>Login and experience our website made just for you. Buckle up cause hero we go!</p>
				<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0udh9L8T1RYAFNHWTUI4379_vI6LDfYhbccHV9Bl0sA&s" style="position: absolute; height: 25%;width:30%;left: 40%;top: 70%; border-radius: 50px; opacity: 0.8">
	</div>
</div>
<script>
//loader
var loader = document.querySelector(".loader")
		
		window.addEventListener("load",vanish);
		
		function vanish(){
			loader.classList.add("nonev");
		} 
</script>
</body>
</html>



