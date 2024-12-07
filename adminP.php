<?php
session_start();
if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']){
	header("Location: Login.php");
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin</title>
<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/adminL.jpeg" type="image/x-icon">
    <link rel="shortcut icon" href="static/adminL.jpeg" type="image/x-icon">

<style>
*{
	padding: 0;
	margin: 0;
	font-family: system-ui;
}
body{
	cursor: default;
}
a{
	text-decoration: none;
	font-size: 3em;
	color: #111;
}
.b1{
	position: absolute;
	border: 2px solid #332;
	padding: 10px 14px 10px;
	width: 28%;
	text-align: center;
	left: 35%;
	top: 20%;
	transition: 0.2s ease;
	box-shadow: 1px 10px 15px #111;
}
.b2{
	position: absolute;
	border: 2px solid #332;
	padding: 10px 14px 10px;
	width: 28%;
	text-align: center;
	left: 35%;
	top: 50%;
	transition: 0.2s ease;
	box-shadow: 1px 10px 15px #111;
}
.b1:hover,.b2:hover{
	border: 2px solid #0ef;
	color: #ddd;
	background: #222;
	box-shadow: 1px 4px 15px #0ef;
	transform: scale(1.1);
}
header{
	width: 100%;
	background: #111;
}
header h1{
	padding: 6px 16px;
	color: #fff;
}
header a{
	right: 0;
	padding: 0 10px;
	position: absolute;
	top: 1%;
	color: #fff;
	font-size: 1.5em;
	transition: 0.2s ease;
}
header a:hover{
	color: red;
}
</style>
</head>
<body>
<audio autoplay loop>
	<source src="static/adminMusic.mp3" type="audio/mp3">
</audio>
<header><h1>ADMIN PANEL</h1><a href="logout.php">Logout</a></header>
<a href="AdminPage.php" class="b1">Users Registered</a>
<a href="AdminPage2.php" class="b2">CRUD</a>

</body>
</html>