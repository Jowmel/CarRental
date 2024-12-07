<?php
//code para makuha ang username ug image through user_id
session_start();

if(!isset($_SESSION['user_id'])){
	header("Location: Login.php");
	exit;
}

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/datas.php";
    $sql = "SELECT * FROM user WHERE id={$_SESSION["user_id"]}";

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
<html>
<head>
<title>About Us</title>
<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/phiwheel.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/phiwheel.png" type="image/x-icon">
<style>
*{
	margin: 0;
	padding: 0;
	font-family: 'Poppins', sans-serif;
}
a{
	text-decoration: none;
}
header{
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 10%;
	display: flex;
	justify-content: space-between;
	align-items: center;
	transition: 0.6s;
	padding: 1px 1px;
	background: black;
	overflow: hidden;
	z-index: 100000000000;
	font-family: consolas;
}
header.sticky{
	padding: 4px 1px;
	background: #eedefe;
}
header.sticky a{
	color: black;
}
header .logo img{
	position: relative;
	transition: 0.6s;
	margin-left: -1%;
	overflow: hidden;
	width: 45%; 
}
header ul{
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;
}
header ul li{
	position: relative;
	list-style: none;
	left: -7%;
}
header ul li a{
	position: relative;
	margin: 0 15px;
	color: #fff;
	letter-spacing: 2px;
	font-weight: 500px;
	transition: 0.5s ease-out;
}
header ul li a:hover{
	color: cyan;
}
header ul li .logos{
	height: 50px;
	width: 50px;
	border-radius: 50px;
	right: -12%;
	position: relative;
	border: 1px solid #ddd;
	transition: 0.2s ease;
} 
header ul li .logos:hover{
	transform: scale(0.9);
}
.active{
	color: cyan;
	font-weight: bold;
	text-transform: uppercase;
	opacity: 0.6;
}


body{
	display: flex;
	justify-content: center;
	align-items: center;
	flex-wrap: wrap;
	background: #161623;
	min-height: 100vh; 
	font-family: 'Poppins', sans-serif;
	cursor: default;
}
section::before{
	content: '';
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}
section::after
	content: '';
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}
.container{
	position: relative;
	z-index: 1;
	display: flex;
	justify-content: center;
	align-items: center;
	flex-wrap: wrap;
	margin: 40px 0;
}
.container .card{
	width: 300px;
	height: 400px;
	background: rgba(255, 255, 255, 0.05);
	margin: 20px;
	box-shadow: 0 15px 35px rgba(0,0,0,0.2);
	border-radius: 15px;
	display: flex;
	justify-content: center;
	align-items: center;
	backdrop-filter: blur(10px);
	transition: 0.7s ease-out;
	margin-top: 45%;
}
.container .card:hover{
	border: 1px solid cyan;
}
.container .card .content{
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;
	flex-direction: column;
	opacity: 0.2;
	transition: 0.5s;
}
.container .card:hover .content{
	opacity: 1;
	transform: translateY(-20px);
}
.container .card .content .imgBx{
	position: relative;
	width: 150px;
	height: 150px;
	border-radius: 50%;
	overflow: hidden;
	border: 10px solid rgba(0,0,0,0.25);
	transition: 0.7s ease-out;
}
.container .card .content .imgBx:hover{
	border: 10px solid cyan;
}
.container .card .content .imgBx img{
	position: absolute;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	object-fit: cover;
}
.container .card .content .contentBx h3{
	color: #fff;
	text-transform: uppercase;
	letter-spacing: 2px;
	font-weight: 500;
	font-size: 18px;
	text-align: center;
	margin: 20px 0 10px;
	line-height: 1.1em;
}
.container .card .content .contentBx h3 span{
	font-size: 12px;
	font-weight: 300;
	text-transform: initial;
}
.container .card .sci{
	position: absolute;
	bottom: 50px;
	display: flex;
}
.container .card .sci li{
	list-style: none;
	margin: 0 10px;
	transform: translateY(40px);
	transition: 0.5s;
	opacity: 0;
	transition-delay: calc(0.1s * var(--i));
}
.container .card:hover .sci li{
	transform: translateY(0px);
	opacity: 1;
}
.container .card .sci li a{
	color: #fff;
	font-size: 24px;
}
.foot{
	height: 10%;
	top: 185%;
	width: 100%;
	position: absolute;
	background: black;
}

h2,
h3,
a {
	color: #34495e;
}

a {
	text-decoration: none;
}
.dev h3{
	position: absolute;
	color: white;
	top: 102%;
	left: 40.5%;
	font-size: 3em;
	letter-spacing: 3px;
	text-shadow: 2px 2px 7px rgba(0,191,255,0.7);
} 
.hero{
	background: #f8f8f8;
	overflow: hidden;
	margin-top: 5%;
	width: 100%;
	height: 5%;
}
.heading h1{
	color: #3d535f;
	font-size: 55px;
	text-align: center;
	margin-top: 35px;
}
.container{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 100%;
	margin: 65px auto;
}
.hero-content{
	flex: 1;
	width: 600px;
	margin: 0px 25px;
	animation: fadeInUp 2s ease;
}
.hero-content h2{
	font-size: 38px;
	margin-bottom: 20px;
	color: #333;
}
.hero-content p{
	font-size: 24px;
	line-height: 1.5;
	margin-bottom: 40px;
	color: #666; 
	width: 55%;
}

.hero-image img{
	width: 40%;
	margin-left: -41%;
	margin-top: -20%;
	position: absolute;
	border-radius: 50%;
	animation: fadeInRight 2s ease;
}
@keyframes fadeInUp{
	0%{
		opacity: 0;
		transform: translateY(50px);
	}
	100%{
		opacity: 1;
		transform: translateY(0px);
	}
}
@keyframes fadeInRight{
	0%{
		opacity: 0;
		transform: translateY(-50px);
	}
	100%{
		opacity: 1;
		transform: translateY(0px);
	}
}
.contact{
	position: absolute;
	z-index: 999999999999999999999;
	display: inline-block;
	color: #111;
	padding: 12px 24px;
	border-radius: 5px;
	font-size: 20px;
	border: 2px solid black;
	transition: 0.3s ease;
	background: transparent;
}
.contact:hover{
	background: cyan;
	color: #fff;
	transform: scale(1.1);
}
.foot h1{
	font-size: 1em;
	color: #eee;
	font-family: system-ui;
	text-align: center;
	justify-content: center;
	margin-top: 2%;
}
.foot span{
	color: #0ef;
}
</style>
<body>
<?php
    //if empty ang image, matik default image sa database magamit
    $profileImageSrc = (!empty($user["image"])) ? htmlspecialchars($user["image"]) : "path/to/default-image.jpg";
 ?>
<header>
	<div class="logo">
		<a href="#"><img src="static/phiwheelLogo-removebg-preview.png" /></a>
	</div>
<ul>
	<li><a href="Home.php">Home</a></li>
	<li><a href="History.php">History</a></li>
	<li><a href="CarCategory.php">Car Category</a></li>
	<li><a href="About.php"  class="a active">About</a></li> 
	<li><a href="UserProfile.php"><img src="<?= $profileImageSrc ?>" class="logos"/></a></li> 
	<em style="color: #0ef; left: -595px; position: absolute; cursor: default; font-weight: 700">Hi, <?= htmlspecialchars($user["username"]) ?>.</em>
</ul>
</header>
 
 <div class="hero">
	<div class="heading">
		<h1>About Us</h1>
	</div>
	<div class="container">
		<div class="hero-content">
			<h2>Welcome to our Website</h2>
			<p>Come and rent with us, you can get yourself a driver when you rent a car or a van. We can assure you to have your safety with these drivers.
			Our drivers are trained professionally before being inserted here. 
			Our team of experts brings you the best content and insights to help you stay ahead of the curve.</p>
			<a href="ContactUs.php" class="contact">Contact Us</a>
		</div>
		<div class="hero-image">
			<img src="static/AUbg.gif">
		</div>
	</div>
 </div>
  
	<div class="dev">
		<h3>Developer</h3>
	</div>
<section>
<div class="container">
<div class="card" style="position: absolute; left: 22%; top: 23%;">
	<div class="content">
		<div class="imgBx">
			<a href="static/Lowell.html"><img src="static/Dev1.jpg"></a>
		</div>
		<div class="contentBx">
			<a href="static/Lowell.html" style="color: white;"><h3>Lowell Stave Estoconing</a><br><br><br><span>Front-end Developer</span></h3>
		</div>
	</div>
</div>
<div class="card" style="position: absolute; left: 53%; top: 23%">
	<div class="content">
		<div class="imgBx">
			<a href="static/PJ.html"><img src="static/Dev2.jpg"></a>
		</div>
		<div class="contentBx">
			<a href="static/PJ.html" style="color: white;"><h3>Philip John Bonbon</a><br><br><br><span>Back-end Developer</span></h3>
		</div>
	</div>
</div>	
</div>
</section>
 
 <div class="foot">
 	<h1><span>Phi</span>Wheel</h1>
 </div>
 
<script>
	//for navbar
	window.addEventListener("scroll",function(){
		var header=document.querySelector("header");
		header.classList.toggle("sticky", window.scrollY > 0);
	});
	 
</script>
</body>
</html>