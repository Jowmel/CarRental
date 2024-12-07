<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit;
}

$success = false;
$error = null;
$bookingExists = isset($_SESSION['bookingExists']) ? $_SESSION['bookingExists'] : false;
$mysqli = null; // Define $mysqli with a default value
$selectedPaymentMethod = isset($paymentMethod) ? $paymentMethod : 'cash';
$gcashNumberValue = isset($gcashNumber) ? htmlspecialchars($gcashNumber) : '';
$atmNumberValue = isset($atmNumber) ? htmlspecialchars($atmNumber) : '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $userId = $_SESSION['user_id'];
    $vehicleId = $_POST['vehicleId'];
    $paymentMethod = $_POST['paymentMethod'];
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Additional fields based on the selected payment method
    $gcashNumber = ($_POST['paymentMethod'] === 'gcash') ? $_POST['gcashNumber'] : null;
    $atmNumber = ($_POST['paymentMethod'] === 'atm') ? $_POST['atmNumber'] : null;

    // Combine GCash and ATM numbers into additional_info
    $additionalInfo = ($paymentMethod === 'gcash') ? $gcashNumber : ($paymentMethod === 'atm' ? $atmNumber : '');

    // If payment method is "Cash on Hand," set additional_info to an appropriate default value
    if ($paymentMethod === 'cash') {
        $additionalInfo = ''; // You can set this to an empty string or another default value
    }

    // Validate and sanitize data if needed

    // Check for empty additional_info if payment method is 'gcash' or 'atm'
    if (($paymentMethod === 'gcash' || $paymentMethod === 'atm') && empty($additionalInfo)) {
        $error = "Please enter the number required.";
    }

    // Check if the user has already booked this vehicle
    $mysqli = require __DIR__ . "/datas.php"; // Define $mysqli here
    $checkBookingSql = "SELECT * FROM booking WHERE user_id=? AND vehicle_id=?";
    $checkBookingStmt = $mysqli->prepare($checkBookingSql);
    $checkBookingStmt->bind_param("ii", $userId, $vehicleId);
    $checkBookingStmt->execute();
    $existingBookingResult = $checkBookingStmt->get_result();

    if ($existingBookingResult && $existingBookingResult->num_rows > 0) {
        // User has already booked this vehicle
        $bookingExists = true;
        $error = "You have already booked this vehicle.";
    }

    $checkBookingStmt->close();
    $existingBookingResult->free_result();

    // If there are no errors, proceed with saving the booking
    if (!isset($error) && $action === 'submit' && !$bookingExists) {
        // Fetch vehicle details including the vehicle_name
        $fetchVehicleSql = "SELECT * FROM vehicle WHERE id=?";
        $fetchVehicleStmt = $mysqli->prepare($fetchVehicleSql);
        $fetchVehicleStmt->bind_param("i", $vehicleId);
        $fetchVehicleStmt->execute();
        $result = $fetchVehicleStmt->get_result();
		

        if ($result && $result->num_rows > 0) {
            $vehicle = $result->fetch_assoc();
            $vehicleName = htmlspecialchars($vehicle["name"]);
        } else {
            // Handle error
            $error = "Error fetching vehicle details";
        }

        $fetchVehicleStmt->close();
        $result->free_result();

        // If there are no errors, proceed with saving the booking
        if (!isset($error)) {
            // Prepare the SQL statement for inserting into the booking table
            $insertBookingSql = "INSERT INTO booking (user_id, vehicle_id, payment_method, additional_info, vehicle_name) VALUES (?, ?, ?, ?, ?)";
            $insertBookingStmt = $mysqli->prepare($insertBookingSql);
            $insertBookingStmt->bind_param("iisss", $userId, $vehicleId, $paymentMethod, $additionalInfo, $vehicleName);

            if ($insertBookingStmt->execute()) {
                // Placeholder for successful submission
                $success = true;
                $bookingExists = true;
                $vehicleDetails = [
                    'Vehicle Name' => $vehicleName,
                    'Payment Method' => $paymentMethod,
                    'Number' => $additionalInfo,
                ];
 
            } else {
                // Placeholder for error during submission
                $error = "Error saving booking: " . $insertBookingStmt->error;
            }

            $insertBookingStmt->close();
        }
    } elseif ($action === 'cancel') {
        // User clicked "Cancel Booking" button
        if (!isset($mysqli)) {
            $mysqli = require __DIR__ . "/datas.php"; // Define $mysqli if not already defined
        }

        $cancelBookingSql = "DELETE FROM booking WHERE user_id=? AND vehicle_id=?";
        $cancelBookingStmt = $mysqli->prepare($cancelBookingSql);
        $cancelBookingStmt->bind_param("ii", $userId, $vehicleId);

        if ($cancelBookingStmt->execute()) {
            // Placeholder for successful cancellation
            $success = true;
            $bookingExists = false;
	
            // Remove booking status from session
            unset($_SESSION['bookingExists']);
			$error = "Canceled booking ";
        } else {
            // Placeholder for error during cancellation
            $error = "Error canceling booking: " . $cancelBookingStmt->error;
        }

        $cancelBookingStmt->close();
    } elseif ($action === 'update' && $bookingExists) {
    // Update the booking information in the database
    $updateBookingSql = "UPDATE booking SET payment_method=?, additional_info=? WHERE user_id=? AND vehicle_id=?";
    $updateBookingStmt = $mysqli->prepare($updateBookingSql);
    $updateBookingStmt->bind_param("ssii", $paymentMethod, $additionalInfo, $userId, $vehicleId);

    if ($updateBookingStmt->execute()) {
        // Placeholder for successful update
        $success = true;
        $error = "Update successfully ";

        // Fetch vehicle details after update
        $fetchVehicleSql = "SELECT * FROM vehicle WHERE id=?";
        $fetchVehicleStmt = $mysqli->prepare($fetchVehicleSql);
        $fetchVehicleStmt->bind_param("i", $vehicleId);
        $fetchVehicleStmt->execute();
        $result = $fetchVehicleStmt->get_result();

        if ($result && $result->num_rows > 0) {
            $vehicle = $result->fetch_assoc();
            $vehicleName = htmlspecialchars($vehicle["name"]);
            $vehicleDetails = [
                'Vehicle Name' => $vehicleName,
                'Payment Method' => $paymentMethod,
                'Number' => $additionalInfo,
            ];
        } else {
            // Handle error
            $error = "Error fetching vehicle details after update";
        }

        $fetchVehicleStmt->close();
        $result->free_result();
    } else {
        // Placeholder for error during update
        $error = "Error updating booking: " . $updateBookingStmt->error;
    }

    $updateBookingStmt->close();
}
}

// Fetch vehicle details based on the vehicleId (outside of the POST handling)
$vehicleId = isset($_GET['vehicleId']) ? $_GET['vehicleId'] : null;
$bookingExists = false; // Initialize bookingExists variable


if ($vehicleId) {
    $mysqli = require __DIR__ . "/datas.php";
    $sql = "SELECT * FROM vehicle WHERE id=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $vehicleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();
        $vehicleName = htmlspecialchars($vehicle["name"]);
        $vehiclePrice = htmlspecialchars($vehicle["price"]);
        $vehicleType = htmlspecialchars($vehicle["type"]);

        // Check if the user has already booked this vehicle
        $userId = $_SESSION['user_id'];
        $checkBookingSql = "SELECT * FROM booking WHERE user_id=? AND vehicle_id=?";
        $checkBookingStmt = $mysqli->prepare($checkBookingSql);
        $checkBookingStmt->bind_param("ii", $userId, $vehicleId);
        $checkBookingStmt->execute();
        $existingBookingResult = $checkBookingStmt->get_result();

        if ($existingBookingResult && $existingBookingResult->num_rows > 0) {
        // User has already booked this vehicle
        $bookingExists = true;
    }

    $checkBookingStmt->close();
    $existingBookingResult->free_result();
} else {
    // Handle error or redirect to an error page
    echo "Invalid vehicleId";
    exit;
}

    $stmt->close();
    $mysqli->close();
} else {
    // Handle error or redirect to an error page
    echo "Invalid vehicleId";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        label {
            display: block;
            margin: 10px 0;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        #gcashNumberField,
        #atmNumberField {
            display: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
            display: <?php echo isset($error) ? 'block' : 'none'; ?>;
        }

        .success {
            background-color: #4CA3E2;  /* Dry Cyan Color */
            color: white;
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
            display: <?php echo $success ? 'block' : 'none'; ?>;
        }
    </style>
<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/phiwheel.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/phiwheel.png" type="image/x-icon">
</head>

<body>
<br><br>
   <a href="CarCategory.php">Go back to Car Category</a>

    <h1>Payment Details</h1>
    <h1><?= $vehicleName ?> - Price: <?= $vehiclePrice ?> - Type: <?= $vehicleType ?></h1>

    <div class="error"><?= isset($error) ? $error : '' ?></div>

    <form method="post">
        <!-- Add hidden input for vehicleId -->
        <input type="hidden" name="vehicleId" value="<?= htmlspecialchars($vehicleId) ?>">

        <!-- Other payment details --> 
<label for="paymentMethod">Payment Method:</label>
<select name="paymentMethod" id="paymentMethod" required>
    <option value="cash" <?= $selectedPaymentMethod === 'cash' ? 'selected' : '' ?>>Cash on Hand</option>
    <option value="gcash" <?= $selectedPaymentMethod === 'gcash' ? 'selected' : '' ?>>GCash</option>
    <option value="atm" <?= $selectedPaymentMethod === 'atm' ? 'selected' : '' ?>>ATM</option>
</select>

<!-- Additional fields based on payment method -->
<div id="gcashNumberField" style="display: <?= $selectedPaymentMethod === 'gcash' ? 'block' : 'none' ?>;">
    <label for="gcashNumber">GCash Number:</label>
    <input type="text" name="gcashNumber" autocomplete="off" id="gcashNumber" value="<?= $gcashNumberValue ?>">
</div>

<div id="atmNumberField" style="display: <?= $selectedPaymentMethod === 'atm' ? 'block' : 'none' ?>;">
    <label for="atmNumber">ATM Number:</label>
    <input type="text" name="atmNumber" autocomplete="off" id="atmNumber" value="<?= $atmNumberValue ?>">
</div>


        <button type="submit" name="action" value="submit" <?= $bookingExists ? 'hidden' : '' ?>>Submit</button>
        <button type="submit" name="action" value="update" <?= !$bookingExists ? 'hidden' : '' ?>>Update Booking</button> <br><br>
        <button type="submit" name="action" value="cancel" <?= !$bookingExists ? 'hidden' : '' ?>>Cancel Booking</button>
    </form>

    <div class="success">Booking operation successful!</div>

    <!-- Display vehicle details if available -->
    <?php if (isset($vehicleDetails)): ?>
        <div style="margin-top: 20px;">
            <h2>Details:</h2>
            <ul>
                <?php foreach ($vehicleDetails as $key => $value): ?>
                    <li><strong><?= $key ?>:</strong> <?= $value ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>


  

<script>
    // Add event listener to show/hide additional fields based on payment method
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const gcashNumberField = document.getElementById('gcashNumberField');
    const atmNumberField = document.getElementById('atmNumberField');

    // Function to show additional fields and display entered values
    function updateFieldsVisibility() {
        const selectedMethod = paymentMethodSelect.value;

        // Show/hide additional fields based on payment method
        gcashNumberField.style.display = selectedMethod === 'gcash' ? 'block' : 'none';
        atmNumberField.style.display = selectedMethod === 'atm' ? 'block' : 'none';
    }

    // Function to update displayed values
    function updateDisplayedValues() {
        const gcashNumberInput = document.getElementById('gcashNumber');
        const atmNumberInput = document.getElementById('atmNumber');

        // Display entered values in the text fields
        gcashNumberInput.value = gcashNumberInput.value.trim(); // Trim to remove leading/trailing whitespaces
        atmNumberInput.value = atmNumberInput.value.trim();
    }

    // Call the functions on page load
    window.addEventListener('load', function () {
        updateFieldsVisibility();
        updateDisplayedValues();
    });

    // Add event listeners for changes in payment method and input values
    paymentMethodSelect.addEventListener('change', function () {
        updateFieldsVisibility();
        updateDisplayedValues();
    });

    // Add event listeners for input changes
    document.getElementById('gcashNumber').addEventListener('input', updateDisplayedValues);
    document.getElementById('atmNumber').addEventListener('input', updateDisplayedValues);
</script>




</body>

</html>


