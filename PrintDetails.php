<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff;
            margin: 20px;
            padding: 20px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.7);
            max-width: 600px;
            margin: 50px auto;
			border-radius: 5px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .card-header, .card-body {
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .card-header p, .card-body p {
            margin: 0;
            color: #555;
        }

        .receipt-details {
            display: flex;
            justify-content: space-between;
        }

        .print-btn, .back-btn {
            background-color: #0ef;
            color: #111;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
            margin-right: 10px;
			text-decoration: none;
			transition: 0.2s ease;
        }

        .print-btn:hover, .back-btn:hover {
            border: 1px solid #7de;
			background-color: transparent;
        }
    </style>
    <!-- Include the FileSaver.js library for saving the generated document -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <!-- Include the Docxtemplater library directly from the CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/docxtemplater/3.9.2/docxtemplater.js"></script>
<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/phiwheel.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/phiwheel.png" type="image/x-icon">
</head>
<body>
    <?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: Login.php");
        exit;
    }

    function getBookingDetails($bookingId, $userId) {
        $mysqli = new mysqli("localhost", "root", "", "phiwheeldb");

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $bookingDetailsSql = "SELECT b.booking_id, b.vehicle_name, b.payment_method, b.additional_info, v.type, v.price 
                             FROM booking b
                             INNER JOIN vehicle v ON b.vehicle_id = v.id
                             WHERE b.booking_id=? AND b.user_id=?";
        $stmt = $mysqli->prepare($bookingDetailsSql);
        $stmt->bind_param("ii", $bookingId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $bookingDetails = [
                'Vehicle Name' => htmlspecialchars($row['vehicle_name']),
                'Vehicle Type' => htmlspecialchars($row['type']),
                'Price' => htmlspecialchars($row['price']),
                'Payment Method' => htmlspecialchars($row['payment_method']),
                'Number' => htmlspecialchars($row['additional_info']),
            ];
            $stmt->close();
            $mysqli->close();
            return $bookingDetails;
        } else {
            $stmt->close();
            $mysqli->close();
            return false;
        }
    }

    if (isset($_GET['booking_id'])) {
        $bookingId = $_GET['booking_id'];
        $userId = $_SESSION['user_id'];
        $bookingDetails = getBookingDetails($bookingId, $userId);

        if ($bookingDetails) {
            // Your HTML and PHP output here...
            echo "<h1>Booking Receipt</h1>";
            echo "<div class='card-header'>";
            echo "<p><strong>Vehicle Name:</strong> " . $bookingDetails['Vehicle Name'] . "</p>";
            echo "<p><strong>Vehicle Type:</strong> " . $bookingDetails['Vehicle Type'] . "</p>";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<p><strong>Price:</strong> ₱" . $bookingDetails['Price'] . "</p>";
            echo "<p><strong>Payment Method:</strong> " . $bookingDetails['Payment Method'] . "</p>";
            echo "<p><strong>Booking Number:</strong> " . $bookingDetails['Number'] . "</p>";
            // Output additional details
            echo "</div>";
            echo "<div class='receipt-details'>";
            echo "<p><strong>Date:</strong> " . date('Y-m-d H:i:s') . "</p>";
            echo "<p><strong>Receipt Number:</strong> " . uniqid() . "</p>";
            echo "</div>"; 
			echo "<a href='javascript:history.back()' class='back-btn'>Back</a>";
            echo "<button class='print-btn' onclick='printBookingAsText()'>Save in file</button>";
        } else {
            echo "Error fetching booking details.";
        }
    } else {
        echo "Booking ID not provided.";
    }
    ?>

    <script>
        function printBookingAsText() {
            var vehicleName = "<?php echo $bookingDetails['Vehicle Name']; ?>";
            var vehicleType = "<?php echo $bookingDetails['Vehicle Type']; ?>";
            var price = "<?php echo $bookingDetails['Price']; ?>";
            var paymentMethod = "<?php echo $bookingDetails['Payment Method']; ?>";
            var number = "<?php echo $bookingDetails['Number']; ?>";

            // Create a plain text representation of the booking details
            var textContent = "-------------------------------------\n" +
                              "         Booking Receipt          \n" +
                              "-------------------------------------\n" +
                              "Vehicle Name: " + vehicleName + "\n" +
                              "Vehicle Type: " + vehicleType + "\n" +
                              "Price: ₱" + price + "\n" +
                              "Payment Method: " + paymentMethod + "\n" +
                              "Booking Number: " + number + "\n" +
                              "-------------------------------------\n" +
                              "Date: " + new Date().toLocaleString() + "\n" +
                              "Receipt Number: " + Math.random().toString(36).substr(2, 9) + "\n" +
                              "-------------------------------------";

            // Create a Blob from the plain text content
            var textBlob = new Blob([textContent], { type: "text/plain;charset=utf-8" });

            // Trigger a download of the text file
            saveAs(textBlob, "Booking_Receipt.txt");
        }
    </script>
</body>
</html>
