<?php
session_start();

if (!isset($_GET['id'])) {
    die("Donation ID is missing.");
}

$donation_id = $_GET['id'];
$mysqli = new mysqli("localhost", "root", "", "platepromise");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Delete the donation from the database
$query = "DELETE FROM food_donations WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $donation_id);

if ($stmt->execute()) {
    echo "Donation deleted successfully!";
    header("Location: manage_donations.php"); // Redirect back to the manage donations page
    exit();
} else {
    echo "Error deleting donation: " . $stmt->error;
}

$mysqli->close();
?>
