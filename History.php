<?php
session_start();

if (!isset($_SESSION['user_id'])) {
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

    // Fetch booking history based on user ID with additional details from the vehicle table
    $bookingHistorySql = "SELECT b.booking_id, b.vehicle_name, b.payment_method, b.additional_info, v.type, v.price FROM booking b
                         INNER JOIN vehicle v ON b.vehicle_id = v.id
                         WHERE b.user_id=?";
    $bookingHistoryStmt = $mysqli->prepare($bookingHistorySql);
    $bookingHistoryStmt->bind_param("i", $_SESSION['user_id']);
    $bookingHistoryStmt->execute();
    $bookingHistoryResult = $bookingHistoryStmt->get_result();

    $bookingHistory = [];

    if ($bookingHistoryResult && $bookingHistoryResult->num_rows > 0) {
        while ($row = $bookingHistoryResult->fetch_assoc()) {
            $bookingHistory[] = [
                'booking_id' => $row['booking_id'],
                'Vehicle Name' => htmlspecialchars($row['vehicle_name']),
                'Payment Method' => htmlspecialchars($row['payment_method']),
                'Number' => htmlspecialchars($row['additional_info']),
                'Vehicle Type' => htmlspecialchars($row['type']),
                'Price' => htmlspecialchars($row['price']),
            ];
        }
    }

    $bookingHistoryStmt->close();
    $bookingHistoryResult->free_result();
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html>
<head> 
<title>History</title>
<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/phiwheel.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/phiwheel.png" type="image/x-icon">
<style>
body {
        font-family: 'Arial', sans-serif;
        background-color: #ede;
        margin: 0;
        padding: 0;
		cursor: default;
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
div.container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        div.card {
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        div.card-header {
            background-color: #3498db;
            color: #fff;
            padding: 15px;
        }

        div.card-body {
            padding: 20px;
        }

        div.card-body p {
            margin: 0;
        }

        div.card-footer {
            background-color: #f2f2f2;
            padding: 15px;
            text-align: right; 
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        button.cancel-btn {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }button.proceed-btn {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
		 .modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
        width: 300px; /* Adjust the width as needed */
        background-color: #111;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
		color: #fff;
    }

    .modal-content {
        padding: 20px;
        text-align: center;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }
	.print-btn{
		padding: 6px 10px 6px;
		transition: 0.2s ease;
		cursor: pointer;
	}
	.print-btn:hover{
		opacity: 0.6;
		border: 1px solid blue;
		color: #0ef; 
	}
span{
	color: red;
	font-size: 2em;
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
	<li><a href="History.php" class="a active">History</a></li>
	<li><a href="CarCategory.php">Car Category</a></li>
	<li><a href="About.php">About</a></li> 
	<li><a href="UserProfile.php"><img src="<?= $profileImageSrc ?>" class="logos"/></a></li> 
	<em style="color: #0ef; left: -545px; position: absolute; cursor: default; font-weight: 700">Hi, <?= htmlspecialchars($user["username"]) ?>.</em>
</ul>
</header>

  <div class="container">
    <br><br>
    
    <?php if (empty($bookingHistory)): ?>
        <p style="font-family: system-ui; font-size: 4em; text-align: center;font-weight: 700; cursor: default; text-shadow: 5px 10px 10px #444;margin-top: -11%">You don't have any bookings yet<span style="color: #0ef; font-size: 5em">.</span></p>
    <?php else: ?>
        <!-- Active bookings -->
        <div id="activeBookings">
		<h1 style="font-family: system-ui"><span>B</span>ooking History &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span style="font-size: 0.7em; color: navy">SUCCESSFUL BOOKING</span></h1>

            <?php foreach ($bookingHistory as $booking): ?>
                <div class="card" id="card_<?= $booking['booking_id'] ?>">
                    <div class="card-header">
                        <p><strong>Vehicle Name:</strong> <?= $booking['Vehicle Name'] ?></p>
                        <p><strong>Vehicle Type:</strong> <?= $booking['Vehicle Type'] ?></p>
                        <p><strong>Price: ₱</strong><?= $booking['Price'] ?></p>
                    </div>
                    <div class="card-body">
                        <p><strong>Payment Method:</strong> <?= $booking['Payment Method'] ?></p>
                        <p><strong>Number:</strong> <?= $booking['Number'] ?></p>
                        <!-- Add more details as needed -->
                    </div>
                    <div class="card-footer">
                        <button class="cancel-btn" onclick="showCancellationModal(<?= $booking['booking_id'] ?>)">Cancel Booking</button>
                        <button class="print-btn" onclick="printBookingAsDocx(<?= $booking['booking_id'] ?>)">Save in file</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Recently Canceled bookings --> 
<div id="recentlyCanceledBookings" style="display: none;">
    <?php if (empty($recentlyCanceledBookings)): ?>
        <p style="font-family: system-ui; font-size: 2em; text-align: center;font-weight: 700; cursor: default; color: tomato;text-shadow: 2px 4px 4px #444;">
            Recently canceled booking of you
        </p>
    <?php else: ?>
        <h2>Recently Canceled Bookings</h2>
        <?php foreach ($recentlyCanceledBookings as $canceledBooking): ?>
            <div class="card" style="opacity: 0.7;" id="card_<?= $canceledBooking['booking_id'] ?>">
                <div class="card-header">
                    <p><strong>Vehicle Name:</strong> <?= $canceledBooking['Vehicle Name'] ?></p>
                    <p><strong>Vehicle Type:</strong> <?= $canceledBooking['Vehicle Type'] ?></p>
                    <p><strong>Price: ₱</strong><?= $canceledBooking['Price'] ?></p>
                </div>
                <div class="card-body">
                    <p><strong>Payment Method:</strong> <?= $canceledBooking['Payment Method'] ?></p>
                    <p><strong>Number:</strong> <?= $canceledBooking['Number'] ?></p>
                    <!-- Add more details as needed -->
                </div>
                <div class="card-footer">
                    <button class="undo-btn" onclick="undoCancellation(<?= $canceledBooking['booking_id'] ?>)">Undo</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


    <?php endif; ?>
</div>


    <!-- Modal for cancellation confirmation -->
    <div id="cancellationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCancellationModal()">&times;</span>
            <p>Are you sure you want to cancel this booking?</p>
            <button class="cancel-btn" onclick="closeCancellationModal()">Cancel</button>
            <button class="proceed-btn" onclick="sendCancellationRequest()">Proceed</button>
            <!-- Hidden field to store the booking ID -->
            <input type="hidden" id="bookingIdField" value="">
        </div>
    </div>

 
 <script>
    // for navbar
    window.addEventListener("scroll", function () {
        var header = document.querySelector("header");
        header.classList.toggle("sticky", window.scrollY > 0);
    });

    function showCancellationModal(bookingId) {
        var modal = document.getElementById("cancellationModal");
        modal.style.display = "block";
        // Store the booking ID in a hidden field for later use
        document.getElementById("bookingIdField").value = bookingId;
    }

    function closeCancellationModal() {
        var modal = document.getElementById("cancellationModal");
        modal.style.display = "none";
    }

   
   function sendCancellationRequest() {
    // Retrieve the booking ID from the hidden field
    var bookingId = document.getElementById("bookingIdField").value;

    // Implement AJAX request to update the booking status on the server
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "cancel_booking.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log("Response from cancel_booking.php:", xhr.responseText);

            if (xhr.status === 200) {
                console.log("Cancellation request for booking ID: " + bookingId + " successful");

                // Move the card to the "Recently Canceled" section
                moveCardToRecentlyCanceled(bookingId);

                // Close the modal after processing
                closeCancellationModal();
            } else {
                console.error("Error canceling booking");
            }
        }
    };
    xhr.send("booking_id=" + bookingId);
}

  
 function moveCardToRecentlyCanceled(bookingId) {
    var card = document.getElementById("card_" + bookingId);
    var recentlyCanceledBookings = document.getElementById("recentlyCanceledBookings");

    // Clone the card node to move it to the "Recently Canceled" section
    var clonedCard = card.cloneNode(true);

    // Set opacity to 0.7 for the cloned card
    clonedCard.style.opacity = 0.7;

    // Remove the "Cancel Booking" and "Save in file" buttons
    var buttons = clonedCard.getElementsByClassName("cancel-btn");
    var printButton = clonedCard.getElementsByClassName("print-btn");

    for (var i = 0; i < buttons.length; i++) {
        buttons[i].style.display = "none";
        printButton[i].style.display = "none";
    }

    // Add Undo button
    var undoButton = document.createElement("button");
    undoButton.className = "undo-btn";
    undoButton.innerText = "Undo";
    undoButton.onclick = function () {
        undoCancellation(bookingId);
    };
	undoButton.style.backgroundColor = "lime"; // Example background color
undoButton.style.color = "#fff"; // Example text color
undoButton.style.border = "none";
undoButton.style.padding = "12px 36px 12px";
undoButton.style.borderRadius = "4px";
undoButton.style.cursor = "pointer";

	// Set a timer to hide the buttons after 3 seconds
    setTimeout(function () {
        for (var i = 0; i < buttons.length; i++) {
             undoButton.style.display = "none";
        }
    }, 2500);

    // Append the Undo button to the card footer
    clonedCard.getElementsByClassName("card-footer")[0].appendChild(undoButton);

    // Remove the card from the active bookings section
    card.parentNode.removeChild(card);

    // Append the cloned card to the "Recently Canceled" section
    recentlyCanceledBookings.appendChild(clonedCard);

    // Display the "Recently Canceled" section
    recentlyCanceledBookings.style.display = "block";

    // Add hover effect to change opacity to 1
    clonedCard.addEventListener("mouseover", function () {
        this.style.opacity = 1;
    });

    // Add hover effect to change opacity back to 0.7 when not hovering
    clonedCard.addEventListener("mouseout", function () {
        this.style.opacity = 0.7;
    });
}

function undoCancellation(bookingId) {
    var clonedCard = document.getElementById("card_" + bookingId);
    var activeBookings = document.getElementById("activeBookings");

    // Clone the card node to move it back to the active bookings section
    var originalCard = clonedCard.cloneNode(true);

    // Remove the Undo button from the card footer
    var undoButton = originalCard.getElementsByClassName("undo-btn")[0];
    undoButton.parentNode.removeChild(undoButton);

    // Bring back the "Cancel Booking" and "Save in file" buttons
    var buttons = originalCard.getElementsByClassName("card-footer")[0].getElementsByClassName("cancel-btn");
    var printButtons = originalCard.getElementsByClassName("card-footer")[0].getElementsByClassName("print-btn");

    for (var i = 0; i < buttons.length; i++) {
        buttons[i].style.display = "inline-block";
        printButtons[i].style.display = "inline-block";

        // Adjust the styling to move the buttons to the right side with margin
        buttons[i].style.marginLeft = "auto";
        buttons[i].style.marginRight = "0";

        printButtons[i].style.marginLeft = "auto";
        printButtons[i].style.marginRight = "0";  // Adjust the margin as needed
    }

    // Append the original card to the active bookings section
    activeBookings.appendChild(originalCard);

    // Remove the cloned card from the recently canceled bookings section
    clonedCard.parentNode.removeChild(clonedCard);

    // Display the "Active Bookings" section
    activeBookings.style.display = "block";

    
}



function printBookingAsDocx(bookingId) {
    // Redirect to PrintDetails.php with the booking ID
    window.location.href = 'PrintDetails.php?booking_id=' + bookingId;
}
 

</script>

</body>
</html>