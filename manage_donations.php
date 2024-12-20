<?php
session_start();

// Database connection
$mysqli = new mysqli("localhost", "root", "", "platepromise");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch all donations from the current restaurant
$restaurant_id = $_SESSION['restaurant_id']; // Assume the restaurant ID is stored in session
$query = "SELECT * FROM food_donations WHERE restaurant_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $restaurant_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all data for displaying
$donations = [];
while ($row = $result->fetch_assoc()) {
    $donations[] = $row;
}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Donations</title>
    <link rel="icon" href="Screenshot 2024-11-23 154406.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #000;
            color: white;
            font-family: Arial, sans-serif;
            padding: 50px;
        }

        .table {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
        }

        .table th,
        .table td {
            text-align: center;
            color: white;
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

        .btn-delete {
            background-color: #f44336;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="display-4 text-center">Manage Donations</h1>
        <p class="lead text-center">Here you can view, edit or delete your food donations.</p>

        <!-- Donations Table -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Food Name</th>
                    <th>Quantity (kg)</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Contact Person</th>
                    <th>Contact Number</th>
                    <th>Donation Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($donations) > 0) {
                    foreach ($donations as $donation) {
                        echo "<tr>
                            <td>{$donation['food_name']}</td>
                            <td>{$donation['quantity']}</td>
                            <td>{$donation['description']}</td>
                            <td>{$donation['location']}</td>
                            <td>{$donation['contact_person']}</td>
                            <td>{$donation['contact_number']}</td>
                            <td>{$donation['donation_date']}</td>
                            <td>
                                <a href='edit_donation.php?id={$donation['id']}' class='btn btn-custom'>Edit</a>
                                <a href='delete_donation.php?id={$donation['id']}' class='btn btn-delete' onclick='return confirm(\"Are you sure you want to delete this donation?\");'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No donations found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>
