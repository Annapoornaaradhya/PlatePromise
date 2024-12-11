<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "donations";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $donation_type = $_POST['donation'];
    $address = $_POST['address'];
    $message = $_POST['message'];

    // Insert data into MySQL database
    $sql = "INSERT INTO contact_form (name, mobile, donation_type, address, message)
            VALUES ('$name', '$mobile', '$donation_type', '$address', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Your details have been submitted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Save the form data into a CSV file
    $csvFile = 'donations.csv';

    // Open the CSV file in append mode (creates the file if it doesn't exist)
    $file = fopen($csvFile, 'a');

    // Write the form data to the CSV file
    // Ensure to write the column headers only once, if the file is empty
    if (filesize($csvFile) == 0) {
        // Add column headers if the file is empty
        fputcsv($file, ['Name', 'Mobile', 'Donation Type', 'Address', 'Message']);
    }

    // Add the form data as a new row
    fputcsv($file, [$name, $mobile, $donation_type, $address, $message]);

    // Close the CSV file
    fclose($file);

    // Close the MySQL connection
    $conn->close();
}
?>
