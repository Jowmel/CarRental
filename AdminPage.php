<!DOCTYPE html>
<html>
<head>
<title>Admin</title>
<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/adminL.jpeg" type="image/x-icon">
    <link rel="shortcut icon" href="static/adminL.jpeg" type="image/x-icon">

<style>
*{
	margin: 0;
	padding: 0;
	font-family: system-ui;
}
body{cursor: default}
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
	color: #0e f;
}
a{
	text-decoration: none;
}
header .srch #search{
	border-radius: 15px;
	text-indent: 5px;
	padding: 5px 7px 5px;
}
header .srch{
	position: absolute;
	top: 1.5%;
	left: 65%;
}
.sub{
	background-image:url('static/bx-search-alt-2.svg');
	background-size: cover;
	height: 20px;
	width: 20px;
	border-radius: 50px;
	cursor: pointer;
	position: absolute;
	top: 5px;
	left: 142px;
	border: none;
	outline: none;
	transition: 0.2s ease;
}
.sub:hover{
	transform: scale(0.9);
}
</style>
</head>
<body>

<header>
	<h1>ADMIN PANEL</h1><a href="adminP.php">Back</a>
	<div class="srch">
		<form method="post" action="">
			<input type="text" id="search" placeholder="Enter username" name="search">
			<input type="submit" class="sub" value="">
		</form>
	</div>
</header>


<?php
session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: Login.php");
    exit;
}

// Connect to your database (replace with your credentials)
$host = "localhost";
$dbname = "phiwheeldb";
$username = "root";
$password = "";

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $userIdToDelete = $_POST['delete_user'];

    // Display a confirmation dialog
    echo "<script>
            var confirmDelete = confirm('Are you sure you want to delete this account? It will be permanently lost.');
            if (confirmDelete) {
                window.location.href = 'AdminPage.php?confirmedDelete=' + $userIdToDelete;
            }else {
                window.location.href = 'AdminPage.php';
            }
          </script>";
    exit;
}

// Check if confirmedDelete is set in the URL
if (isset($_GET['confirmedDelete'])) {
    $userIdToDelete = $_GET['confirmedDelete'];
    $deleteSql = "DELETE FROM user WHERE id = $userIdToDelete";
    $result = $mysqli->query($deleteSql);

    if ($result) {
        echo "<script>alert('Account deleted successfully.')</script>";
    } else {
        echo "Error deleting the account. Please try again.";
    }
}

// Retrieve user details
$sql = "SELECT id, username, image, password_hash FROM user";

// Search functionality
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search = $mysqli->real_escape_string($_POST['search']);
    $sql .= " WHERE username LIKE '%$search%'";
}

$result = $mysqli->query($sql);

// Display user details in card boxes with delete button
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 10px 0 19px; margin: 10px; height: 55%;'>";
        echo "<center><img style='border: 2px solid #111;height: 100px; width: 100px;border-radius: 50px  'src='" . $row["image"] . "' alt='user image'></center><br><br>";
        echo "<strong>Username:</strong> " . $row["username"] . "<br>";
        echo "<strong>Password Hash:</strong> " . $row["password_hash"] . "<br><br>";
        // Add delete button
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='delete_user' value='" . $row["id"] . "'>";
        echo "<center><button type='submit' style='cursor: pointer; padding: 7px 46px 5px; border: 1px solid red; border-radius: 5px; color: red; background: #222; box-shadow: 0 5px 6px #111; margin-bottom: 15px'>Delete</button></center>";
        echo "<center><a href='adminBookUser.php?user_id=" . $row["id"] . "' style='cursor: pointer; padding: 5px 10px 5px; border: 1px solid blue; border-radius: 5px; color: #0ef; background: #222; box-shadow: 0 5px 6px #111;'>Booked vehicles</a></center>";
		echo "</form>";
        echo "</div>";
    }
} else {
    echo "No users found. <a href='AdminPage.php' style='margin-left: 5px; color: blue; text-decoration: underline'>Refresh</a>";
}

$mysqli->close();
?>


<script>
function confirmDelete() {
    var confirmDelete = confirm('Are you sure you want to delete this account? It will be permanently lost.');
    return confirmDelete;
}
</script>


</body>
</html>