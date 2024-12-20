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

// Fetch the donation data
$query = "SELECT * FROM food_donations WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $donation_id);
$stmt->execute();
$result = $stmt->get_result();
$donation = $result->fetch_assoc();

if (!$donation) {
    die("Donation not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update donation in the database
    $food_name = $_POST['food_name'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $contact_person = $_POST['contact_person'];
    $contact_number = $_POST['contact_number'];
    $donation_date = $_POST['donation_date'];

    $update_query = "UPDATE food_donations SET food_name = ?, quantity = ?, description = ?, location = ?, contact_person = ?, contact_number = ?, donation_date = ? WHERE id = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param("sisssssi", $food_name, $quantity, $description, $location, $contact_person, $contact_number, $donation_date, $donation_id);

    if ($update_stmt->execute()) {
        echo "Donation updated successfully!";
    } else {
        echo "Error: " . $update_stmt->error;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Donation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #000;
            color: white;
            padding: 50px;
        }

        .form-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
        }

        .btn-custom {
            background-color: #ff5722;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-custom:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="display-4 text-center">Edit Donation</h1>
    <div class="form-container">
        <form method="POST">
            <div class="form-group">
                <label for="food_name">Food Name</label>
                <input type="text" class="form-control" id="food_name" name="food_name" value="<?php echo $donation['food_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity (kg)</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $donation['quantity']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description"><?php echo $donation['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $donation['location']; ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_person">Contact Person</label>
                <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?php echo $donation['contact_person']; ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo $donation['contact_number']; ?>" required>
            </div>
            <div class="form-group">
                <label for="donation_date">Donation Date</label>
                <input type="date" class="form-control" id="donation_date" name="donation_date" value="<?php echo $donation['donation_date']; ?>" required>
            </div>
            <button type="submit" class="btn btn-custom">Update Donation</button>
        </form>
    </div>
</div>

</body>
</html>
