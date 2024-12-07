<?php
	session_start();
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		$mysqli=require __DIR__ . "/datas.php";
		$sql=sprintf("SELECT * FROM user WHERE username = '%s'",
		$mysqli->real_escape_string($_POST["username"]));
	
	$result = $mysqli->query($sql);
	$user=$result->fetch_assoc();
	
	if(!isset($_SESSION["username"])){
		header("Location: login.php");
		exit();
	}
	$username=$_SESSION["username"];
	include "header.php";
	$username=$_SESSION["username"];
	echo "$username";
	}
?>