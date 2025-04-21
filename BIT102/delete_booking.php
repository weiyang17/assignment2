<?php
include 'config.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete = "DELETE FROM book WHERE id = $id";
    if (mysqli_query($conn, $delete)) {
        $_SESSION['delete_success'] = "Booking ID #$id deleted successfully.";
    } else {
        $_SESSION['delete_success'] = "Failed to delete Booking ID #$id.";
    }
}

header('Location: booking_list.php');
exit();