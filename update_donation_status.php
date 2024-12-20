<?php
// Start the session
session_start();

// Check if the user is logged in as NGO
if (!isset($_SESSION['user_id']) || $_SESSION['org_type'] !== 'ngo') {
    echo 'error'; // Not an NGO
    exit;
}

require 'db_connection.php'; // Include the database connection file

// Check if the ID is passed via POST
if (isset($_POST['id'])) {
    $donationId = $_POST['id'];

    // Prepare and execute the update query to change the status to 'completed'
    $sql = "UPDATE food_donations SET status = 'completed' WHERE id = ? AND status = 'pending'"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $donationId);
    
    if ($stmt->execute()) {
        echo 'success'; // Successfully updated
    } else {
        echo 'error'; // Something went wrong
    }
}
?>
