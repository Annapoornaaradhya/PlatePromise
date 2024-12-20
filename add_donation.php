<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $restaurant_id = $_POST['restaurant_id'];
    $restaurant_name = $_POST['restaurant_name'];
    $food_name = $_POST['food_name'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $contact_person = $_POST['contact_person'];
    $contact_number = $_POST['contact_number'];
    $donation_date = $_POST['donation_date'];

    // Database connection
    $mysqli = new mysqli("localhost", "root", "", "platepromise");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // SQL query to insert data into the table
    $sql = "INSERT INTO food_donations (restaurant_name, restaurant_id, food_name, quantity, description, location, contact_person, contact_number, donation_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sisssssss", $restaurant_name, $restaurant_id, $food_name, $quantity, $description, $location, $contact_person, $contact_number, $donation_date);

    if ($stmt->execute()) {
        // Redirect to donation_success.php with a success message
        header("Location: donation_success.php?status=success");
        exit();
    } else {
        // In case of an error, display the error message
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>
