<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Shanghai');
require 'config.php'; // Ensure you include the database connection details

if ($_POST) {
    // Check if all required fields are filled
    if ($_POST['city'] && $_POST['date'] && $_POST['room_type'] && $_POST['price'] && $_POST['payment_method']) {

        // Retrieve the form data
        $city = $_POST['city'];
        $bookingDate = $_POST['date'];
        $roomType = $_POST['room_type'];
        $price = $_POST['price'];
        $paymentMethod = $_POST['payment_method'];

        // Prepare the SQL query with placeholders to avoid SQL injection
        $sql = "INSERT INTO book (city, date, room_type, price, payment_method, create_time) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the parameters to the SQL query
            $stmt->bind_param("sssss", $city, $bookingDate, $roomType, $price, $paymentMethod);

            // Execute the query
            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $stmt->error; // Display the error if any
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            echo "Error: " . $conn->error; // Display the error if statement preparation fails
        }

    } else {
        echo "Please fill in all required fields.";
        exit();
    }
}
?>
