<?php
	session_start();
	
	if(!isset($_SESSION['user_id'])){
	header("Location: Login.php");
	exit;
}

	if(isset($_SESSION["user_id"])){
		$mysqli=require __DIR__ . "/datas.php";
		$sql="SELECT * FROM user WHERE id={$_SESSION["user_id"]}";
		
		$result=$mysqli->query($sql);
		
		$user=$result->fetch_assoc();
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Contact Us</title>
	<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/phiwheel.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/phiwheel.png" type="image/x-icon">
<style>
*{
	padding: 0;
	margin: 0;
	box-sizing: border-box;
	font-family: 'Poppins', sans-serif;
}
section{
	padding: 40px 15%;
}
body{
	background: #101010;
}
.contact{
	height: 100%:
	width: 100%:
	min-height: 150vh;
	display: grid;
	grid-template-columns: repeat(2,2fr);
	align-items: center;
	grid-gap: 6rem;
	cursor: default;
}
.contact-img img{
	max-width: 100%;
	width: 720px;
	height: auto;
	border-radius: 10px;
}
.contact-form h1{
	font-size: 80px;
	color: #fff;
	margin-bottom: 20px;
}
span{
	color: #0ef;
}
.contact-form p{
	color: #c6c9d8bf;
	letter-spacing: 1px;
	line-height: 26px;
	font-size: 1.1rem;
	margin-bottom: 3.8rem;
}
.contact-form form{
	position: relative;
}
.contact-form form input,
form textarea{
	width: 100%:
	padding: 17px;
	border: none;
	background: #191919;
	color: #fff;
	font-size: 1.1rem;
	margin-bottom: 0.7rem;
	border-radius: 5px;
}
.contact-form textarea{
	resize: none;
	height: 200px;
}
.contact-form .btn{
	display: inline-block;
	background: #1bf;
	width: 25%;
	border-radius: 4px;
	font-size: 20px;
	left: 0;
	top: 100%;
	position: absolute;
	cursor: pointer;
	box-shadow: 0 0 5px #0ef;
	transition: 0.3s ease;
}
.contact-form .btn:hover{
	box-shadow: 0 0 0;
	background: transparent;
	border: 2px solid #0ef;
	transform: scale(1.1);
	color: #fff;
}
.back{
	text-decoration: none;
	display: inline-block;
	padding: 2px 10px;
	border: 2px solid #333;
	font-size: 1.3em;
	margin-left: 3%;
	margin-top: 1%;
	justify: center;
	justify-content: center;
	text-align: center;
	border-radius: 5px;
	opacity: 0.4;
	transition: 0.2s ease;
	color: #fff;
}
.back:hover{
	opacity: 1;
	background: #ddd;
	color: #333;
	box-shadow: 0 0 4px #ddd;
	border: none;
	outline: none;
	transform: scale(1.1);
}
.loader{
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
.loader .wd{
	position: absolute;
	font-size: 3em;
	left: 27%;
	top: 59%;
	font-family: consolas;
}
.nonev{
	animation: vanish 1.5s forwards;
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
	<section class="contact">
		<div class="contact-form">
			<h1>Contact <span>Us</span></h1>
			<p>Having a trouble? Send us feedback? Connect with us here.</p>
			
			<form action="https://api.web3forms.com/submit" method="post">
				<input type="hidden" name="access_key" value="c1c729c6-f86e-4553-a91a-a531e8034a0d">
				<input type="text" id="txt1" name="username" value="<?= htmlspecialchars($user["username"]) ?>" autocomplete="off" style="cursor: default;opacity: 0.6;text-indent: 10px"placeholder="Username" required readonly>
				<input type="text" id="txt2" name="purpose" autocomplete="off" placeholder="State your purpose" required>
				<textarea id="txt3" name="message" autocomplete="off" cols="30" rows="10" placeholder="Your Message Here." required></textarea>
				<button class="btn">Send</button>
			</form>
		</div>
		
		<div class="contact-img">
			<img src="static/contactUS.gif">
		</div>
	</section>
	<a href="About.php" class="back">Back</a>
	<script> 
	//for loader
	var loader = document.querySelector(".loader")
		
		window.addEventListener("load",vanish);
		
		function vanish(){
			loader.classList.add("nonev");
		}
	</script>
</body>
</html>