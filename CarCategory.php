<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit;
}

// Include the database connection
$mysqli = require __DIR__ . "/datas.php";

// Fetch user data
if (isset($_SESSION["user_id"])) {
    $sql = "SELECT * FROM user WHERE id=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $user = $result->fetch_assoc();
        $imageSrc = (!empty($user["image"])) ? htmlspecialchars($user["image"]) : "path/to/default-image.jpg";
    } else {
        echo "Error fetching user data: " . $stmt->error;
        exit;
    }
    $stmt->close();
}

// Fetch vehicle data
$sql = "SELECT * FROM vehicle";
$result = $mysqli->query($sql);

if ($result && $result->num_rows > 0) {
    $vehicles = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $vehicles = [];
}

// Fetch booked vehicle IDs for the current user
$bookedVehicleIds = [];
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $bookedSql = "SELECT vehicle_id FROM booking WHERE user_id=?";
    $bookedStmt = $mysqli->prepare($bookedSql);
    $bookedStmt->bind_param("i", $userId);
    $bookedStmt->execute();
    $bookedResult = $bookedStmt->get_result();

    if ($bookedResult) {
        while ($row = $bookedResult->fetch_assoc()) {
            $bookedVehicleIds[] = $row['vehicle_id'];
        }
    }

    $bookedStmt->close();
}

$mysqli->close();
?>


<!DOCTYPE html> 
<html>
<head>
<title>Vehicle Booking</title>
<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/phiwheel.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/phiwheel.png" type="image/x-icon">
<style>
*{
	margin: 0;
	padding: 0;
	box-sizing: border-box;
	font-family: 'poppins', sans-serif;
}
.showA{
	color: #111;
	background: #eee;
	padding: 15px 90px 16px;
	margin: 7px 0;
	box-shadow: 0 9px 6px #222;
	transition: 0.2s ease;	
}
.showA:hover{
	background-color: #aaa;
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
	display: flex;
	cursor: default;
	margin-top: 65px;
}
.sidebar{
	width: 25%;
	height: 100vh;
	padding: 50px 0;
	background-color: #ddd;
	position: fixed;
}
.sidebar>h3{
	background: #9cd;
	color: #666;
	letter-spacing: 4px;
	padding-left: 100px;
}
.filter{
	margin-top: 10px;
	padding: 30px 40px;
}
.filter h3{
	margin-bottom: 13px;
}
.contant{
	width: 75%;
	position: absolute;
	right: 0;
}
.header{
	display: flex;
	align-items: center;
	justify-content: center;
	height: 10vh;
	border-bottom: 1px solid #eee;
	color: #383838;
	letter-spacing: 2px;
	background-color: #eee;
}
.header p{
	font-size: 30px;
	font-weight: 555;
}
.header span{
	color: #0ef;
	font-size: 45px;
	font-weight: bold;
}
.fil-p{
	padding: 15px 15px;
	background-color: #fff;
	margin: 7px 0;
	cursor: pointer;
	color: #333;
	text-align: justify;
	box-shadow: 0 9px 6px #222;
	transition: 0.3s ease;
}
.fil-p:hover{
	background: #0ef;
	color: #111;
}
button{
	width: 100%;
	position: relative;
	border: none;
	border-radius: 5px;
	background-color: #aaa;
	padding: 7px 25px;
	cursor: pointer;
	color: white;
}
.box a{
	width: 100%;
	position: relative;
	border: none;
	border-radius: 5px;
	background-color: #aaa;
	padding: 7px 25px;
	cursor: pointer;
	color: white;
	text-decoration: none;
	box-shadow: 0 10px 15px #665;
	transition: 0.2s ease;
}
.box a:hover{
	border: 1px solid #0ad;
	background: transparent;
	color: #0ef;
	font-weight: 400;
	transform: scale(0.9);
}
#root{
	width: 100%;
	display: grid;
	grid-template-columns: repeat(3, 1fr) ;
	grid-gap: 20px;
	padding: 30px;
}
.box{
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: space-between;
	border: 1px solid #ddd;
	padding: 15px;
}
.box>h3{
	margin-bottom: 20px;
}
.img-box{
	width: 100%;
	height: 180px;
	display: flex;
	align-items: center;
	justify-content: center;
}
.images{
	max-height: 90%;
	max-width: 90%;
	object-fit: cover;
	object-position: center;
	transition: 0.3s ease;
}
.images:hover{
	transform: scale(1.1);
}
.bottom{
	margin-top: 20px;
	width: 100%;
	text-align: center;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: space-between;
	height: 70px;
}
button:hover{
	background-color: #333;
}
.bg{
	height: 50%;
	width: 120%;
	margin-top: 185px;
	margin-left: -30px;
	outline: none;
	border: 1px solid #223;
	border-radius: 2px;
	opacity: 0.8;
	box-shadow: 0 18px 14px #000;
	transition: 0.2s ease;
}
.bg:hover{
	opacity: 1;
}
.filter-btn:hover{
	border: 1px solid #0ef;
	color: #0ef;
}
.filter-btn.active{
	color: #fff;
	opacity: 0.5;
	background: #111;
	box-shadow: 0 5px 5px #111;
	border: 1px solid #9dc;
	transform: scale(1.1);
}
.card-box{
	background: #ddd; 
	border-radius: 8px;
	box-shadow: 5px 4px 10px #777;
}
.card-box img{
	height: 60%;
	width: 60%;
	border-radius: 50%;
	margin-left: 22.5%;
	padding: 10px 0 15px;
}
.card-box h2{
	text-align: center;
	color: #020;
	background: #ccc;
	letter-spacing: 1px;
}
.card-box p{
	text-align: center;
	color: #555;
	font-size: 0.8em;
	padding: 5px 0 15px;
}
.card-box a{
	width: 50%;
	padding: 7px 30px 7px;
	background: #9dd;
	cursor: pointer;
	margin-left: 34%;
	color: #fff;
	border-radius: 10px;
	transition: 0.2s ease;
	border: 1px solid #0ef;
	box-shadow: 2px 5px 2px #aaa;
}
.card-box a:hover{
	transform: scale(1.1);
	background: #444;
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
	<li><a href="CarCategory.php" class="a active">Car Category</a></li>
	<li><a href="About.php">About</a></li> 
	<li><a href="UserProfile.php"><img src="<?= $profileImageSrc ?>" class="logos"/></a></li> 
	<em style="color: #0ef; left: -535px; position: absolute; cursor: default; font-weight: 700">Hi, <?= htmlspecialchars($user["username"]) ?>.</em>
</ul>
</header>

	<div class="container">
		<div class="sidebar">
			<h3>PHIWHEEL</h3>
			<div class="filter">
				<h3>Vehicle Sitter Type</h3>
				<div id="btns"></div> 
				<video autoplay muted loop class="bg"><source src="static/CCbg.mp4" type="video/mp4"></video>
			</div>
			<div id="filter-buttons">
				<button class="filter-btn active" style="transition: 0.2s ease;position: absolute; height: 5%; width: 55%; top: 25%; left: 24%" data-filter="all">Show All</button>
				<button class="filter-btn" style="transition: 0.2s ease;position: absolute; height: 5%; width: 55%; top: 32%; left: 24%" data-filter="4 Sitter">4 Sitter</button>
				<button class="filter-btn" style="transition: 0.2s ease;position: absolute; height: 5%; width: 55%; top: 39%; left: 24%" data-filter="8 Sitter">8 Sitter</button>
			</div>
		</div>
		<div class="contant">
			<div class="header">
				<p><span>C</span>hoose and <span>B</span>ook</p>
			</div>
			<div id="root"></div>
		</div>
		
	</div>

<script>
	window.addEventListener("scroll", function () {
		var header = document.querySelector("header");
		header.classList.toggle("sticky", window.scrollY > 0);
	});

	document.addEventListener('DOMContentLoaded', function () {
		const filterButtons = document.getElementById('filter-buttons');
		const vehicles = <?php echo json_encode($vehicles); ?>;
		displayVehicles(vehicles);

		filterButtons.addEventListener('click', function (event) {
			if (event.target.classList.contains('filter-btn')) {
				const filterType = event.target.dataset.filter;
				const filteredVehicles = vehicles.filter(vehicle => vehicle.type === filterType || filterType === 'all');
				displayVehicles(filteredVehicles);

				// Remove 'active' class from all buttons
				filterButtons.querySelectorAll('.filter-btn').forEach(button => button.classList.remove('active'));

				// Add 'active' class to the clicked button
				event.target.classList.add('active');
			}
		});

		document.addEventListener('click', function (event) {
			if (event.target.textContent === 'View Details') {
				const vehicleIndex = Array.from(event.target.parentNode.parentNode.children).indexOf(event.target.parentNode);
				displayPopup(vehicles[vehicleIndex]);
			}
		});

		 

    

    function displayVehicles(vehicles) {
    const root = document.getElementById('root');
    root.innerHTML = '';

    vehicles.forEach(vehicle => {
        const cardBox = document.createElement('div');
        cardBox.className = 'card-box';

        const image = document.createElement('img');
        image.src = 'uploaded_img/' + vehicle.image;
        image.alt = vehicle.name;

        const name = document.createElement('h2');
        name.textContent = vehicle.name;

        const type = document.createElement('p');
        type.textContent = vehicle.type;

        // Replace the button with an anchor tag
        const viewDetailsLink = document.createElement('a');
        viewDetailsLink.href = 'Payment.php?vehicleId=' + vehicle.id; // You may need to adjust the URL parameter as needed
        viewDetailsLink.textContent = 'Book';

        cardBox.appendChild(image);
        cardBox.appendChild(name);
        cardBox.appendChild(type);
        cardBox.appendChild(viewDetailsLink);

        root.appendChild(cardBox);
    });
}
});
</script>


</body>
</html>