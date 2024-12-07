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

<?php 
// Fetch latest vehicles from the vehicle table
$fetchLatestVehiclesSql = "SELECT * FROM vehicle ORDER BY id DESC LIMIT 6";
$fetchLatestVehiclesStmt = $mysqli->prepare($fetchLatestVehiclesSql);
$fetchLatestVehiclesStmt->execute();
$latestVehiclesResult = $fetchLatestVehiclesStmt->get_result();

$latestVehicles = [];
while ($row = $latestVehiclesResult->fetch_assoc()) {
    $latestVehicles[] = $row;
}

$fetchLatestVehiclesStmt->close();
$latestVehiclesResult->free_result();

// Close the database connection
$mysqli->close();
?>


<!DOCTYPE html>
<html>
<head>
<title>Home</title>
<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/phiwheel.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/phiwheel.png" type="image/x-icon">
	<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<style>
*{
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}
body{
	background: #eaeaea;
	overflow-x: hidden;
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
	animation: vanish 3.5s forwards;
}
@keyframes vanish{
	100%{
		visibility: hidden;
	}
	
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
	font-family: 'Poppins', sans-serif;
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
.container{
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%,-50%);
	width: 1350px;
	height: 640px;
	background: #f5f5f5;
	box-shadow: 0 30px 50px #dbdbdb;
	cursor: default;
}
.container .slide .item{
	width: 200px;
	height: 300px;
	position: absolute;
	top: 50%;
	transform: translate(0,-50%);
	border-radius: 20px;
	box-shadow: 0 30px 50px #505050;
	background-position: 50% 50%;
	background-size: cover;
	display: inline-block;
	transition: 0.5s;
}

.slide .item:nth-child(1),
.slide .item:nth-child(2){
	top: 0;
	left: 0;
	transform: translate(0,0);
	border-radius: 0;
	width: 100%;
	height: 100%;
}
.slide .item:nth-child(3){
	left: 73%;	
}
.slide .item:nth-child(4){
	left: calc(73.5% + 220px);
}
.slide .item:nth-child(5){
	left: calc(50% + 440px);
}
.slide .item:nth-child(n + 6){
	left: calc(50% + 660px);
	opacity: 0;
}
.item .content{
	position: absolute;
	top: 50%;
	left: 100px;
	width: 300px;
	text-align: left;
	color: #eee;
	transform: translate(0, -50%);
	font-family: system-ui;
	display: none;
}
.slide .item:nth-child(2) .content{
	display: block;
}
.content .name{
	font-size: 40px;
	text-transform: uppercase;
	font-weight: bold;
	opacity: 0;
	animation: animate 1s ease-in-out 1 forwards;
}
.content .des{
	margin-top: 10px;
	margin-bottom: 20px;
	opacity: 0;
	animation: animate 1s ease-in-out 0.3s 1 forwards;
}
.content button{
	padding: 10px 20px;
	border: none;
	cursor: pointer;
	opacity: 0;
	animation: animate 1s ease-in-out 0.6s 1 forwards;
	transition: 0.3s ease;
	box-shadow: 0 12px 24px #887878;
}
.content button:hover{
	color: #fff;
	background: #0ef;
}
@keyframes animate{
	from{
		opacity: 0;
		transform: translate(0, 100px);
		filter: blur(33px);
	}
	to{
		opacity: 1;
		transform: translate(0);
		filter: blur(0);
	}
}
.button2{
	width: 100%;
	text-align: center;
	position: absolute;
	bottom: 20px;
	padding: 2px 12px 14px 15px;
} 
.button2 .prev:hover, .next:hover{
	transform: scale(1.2); 
}
.button2 .prev:hover{
	margin: 0 10px 0 0;
}
.button2 .next:hover{
	margin: 0 0 0 10px;
}
.main{
	position: absolute;
	left: 3%;
	margin-top: 900px;
	overflow: hidden;
}
.cards{
	display: inline-block;
	margin-top: 5%;
	margin-left: 2.5%;
	border-radius: 15px;
	background-color: #edd;
	font-family: arial;
	box-shadow: 2px 2px 10px #111;
	width: 30%;
	height: 10%;
	transition: transform 0.3s ease;
	overflow: hidden;
	color: #fff;
}
.cards:hover {
    transform: scale(1.05); 
}
body.loading{
	overflow: hidden;
}
.cards img{
	width: 300px;
	height: 200px;
	border-radius: 12px;
}
.cards .title{
	text-align: center;
	padding: 5px;
	cursor: default; 
    border-radius: 8px;
	font-weight: bold;
	font-size: 2em;
}
.cards .des{
	text-align: center;
	padding: 4px;
	cursor: default;
}  
.suggest{
	position: absolute;
	top: 125%;
	left: 34%;
	font-family: system-ui;
	text-shadow: 4px 4px 9px #123;
	cursor: default;
}
.suggest h1{
	font-size: 5em;
}
.suggest span{
	color: #0ef;
}
.footer{
	width: 100%;
	height: 10%;
	position: absolute;
	top: 250%;
	background: #222;
	cursor: default;
}
.footer h1{
	font-size: 1em;
	color: #eee;
	font-family: system-ui;
	text-align: center;
	justify-content: center;
	margin-top: 2%;
}
.footer span{
	color: #0ef;
}
.details {
    position: absolute;
    bottom: -100%;  
    left: 0;
    width: 100%;
    background-color: rgba(1, 1, 1, 0.8);
    transition: bottom 0.3s ease;   
}

.cards:hover .details {
    bottom: 0;     
}
</style>
<body class="loading">
<?php
    //if empty ang image, matik default image sa database magamit
    $profileImageSrc = (!empty($user["image"])) ? htmlspecialchars($user["image"]) : "path/to/default-image.jpg";
 ?>
 <div class="main"> 
    <?php foreach ($latestVehicles as $vehicle) : ?>
    <div class="cards">
        <div class="image">
            <!-- Adjust the src attribute to include the path to the uploaded_img folder -->
            <center><img src="uploaded_img/<?= htmlspecialchars($vehicle['image']) ?>" alt="<?= htmlspecialchars($vehicle['name']) ?>"></center>
        </div>
		<div class="details">
			<div class="title"><?= htmlspecialchars($vehicle['name']) ?></div>
			<div class="des" style="color: #dde;font-family: system-ui;font-size: 0.8em"><?= htmlspecialchars($vehicle['type']) ?></div>
			<div class="des" style="color: red">₱<?= htmlspecialchars($vehicle['price']) ?></div>
		</div>
    </div>
<?php endforeach; ?>
</div>
<div class="loader">
	<img src="static/loader.gif">
 </div>
<header>
	<div class="logo">
		<a href="#"><img src="static/phiwheelLogo-removebg-preview.png" /></a>
	</div>
<ul>
	<li><a href="Home.html" class="a active">Home</a></li>
	<li><a href="History.php">History</a></li>
	<li><a href="CarCategory.php">Car Category</a></li>
	<li><a href="About.php">About</a></li> 
	<li><a href="UserProfile.php"><img src="<?= $profileImageSrc ?>" class="logos"/></a></li> 
	<em style="color: #0ef; left: -595px; position: absolute; cursor: default; font-weight: 700">Hi, <?= htmlspecialchars($user["username"]) ?>.</em>
</ul>
</header>
 
 <br><br><br><br> 
 <div class="container">
	<div class="slide">
		<div class="item" style="background-image: url(static/hm1.jpeg)">
			<div class="content">
				<div class="name">Anytime</div>
				<div class="des">Travel with us. The time is in your hands, you can ask the driver when to stop.</div>
				<a href="CarCategory.php"><button class="button">Book</button></a>
			</div>
		</div>
		<div class="item" style="background-image: url(static/hm2.jpeg)">
			<div class="content">
				<div class="name">Anywhere</div>
				<div class="des">Plan your destination because we support your land travel. Safety Travels!</div>
				<a href="CarCategory.php"><button class="button">Book</button></a>
			</div>
		</div>
		<div class="item" style="background-image: url(static/hm3.jpg)">
			<div class="content">
				<div class="name">Awesome</div>
				<div class="des">Book us for your travel businesses. Fast, safe and efficient travel.</div>
				<a href="CarCategory.php"><button class="button">Book</button></a>
			</div>
		</div>
		<div class="item" style="background-image: url(static/hm4.jpeg)">
			<div class="content">
				<div class="name">Amazing</div>
				<div class="des">Cars and Vans are maintained and check-up everyday for your safety. Book Now!</div>
				<a href="CarCategory.php"><button class="button">Book</button></a>
			</div>
		</div>
	</div>
	<div class="button2">
		<button class="prev" style="height: 25px; width: 25px;border-radius: 50px; transition: 0.3s ease; background: #fff; outline: none; border: none; cursor: pointer"><img src="static/bxs-left-arrow-circle.svg"></button>
		<button class="next" style="height: 25px; width: 25px;border-radius: 50px; transition: 0.3s ease; background: white; outline: none; border: none; cursor: pointer"><img src="static/bxs-right-arrow-circle.svg"></button>
	</div>
</div>

 <br><br><br><br><br><br>
 <br><br><br><br><br><br>
 <div class="suggest">
	<h1><span>New</span>ly <span>Add</span>ed</h1>
 </div>
 
 
 <div class="footer">
	<h1><span>Phi</span>Wheel</h1>
 </div>
 
<script>
	//for loader
	setTimeout(function(){
		document.body.classList.remove('loading');
	}, 3500);
	var loader = document.querySelector(".loader")
		
		window.addEventListener("load",vanish);
		
		function vanish(){
			loader.classList.add("nonev");
		}
		
	//for navbar
	window.addEventListener("scroll",function(){
		var header=document.querySelector("header");
		header.classList.toggle("sticky", window.scrollY > 0);
	});
	 
	 //for button sliding images
	let next=document.querySelector('.next');
	let prev=document.querySelector('.prev');
		next.addEventListener('click', function(){
			let items=document.querySelectorAll('.item');
			document.querySelector('.slide').appendChild(items[0]);
		})
		prev.addEventListener('click', function(){
			let items=document.querySelectorAll('.item');
			document.querySelector('.slide').prepend(items[items.length - 1]);
		})

</script>
</body>
</html>