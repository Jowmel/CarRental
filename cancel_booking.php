<?php
session_start();

if (isset($_POST['booking_id'])) {
    $bookingId = $_POST['booking_id'];
    
    // Include your database connection
    $mysqli = require __DIR__ . "/datas.php";
    
    // Prepare and execute SQL query to update the booking status
    $updateSql = "UPDATE booking SET status = 'deleted' WHERE booking_id = ?";
    $updateStmt = $mysqli->prepare($updateSql);
    $updateStmt->bind_param("i", $bookingId);

    if ($updateStmt->execute()) {
        // Booking cancellation successful
        echo "Booking marked as recently deleted";
    } else {
        // Error in marking the booking as recently deleted
        echo "Error: " . $updateStmt->error;
    }

    $updateStmt->close();
    $mysqli->close();
    
} else {
    // Respond with an error message
    echo "Invalid request";
}
?>
