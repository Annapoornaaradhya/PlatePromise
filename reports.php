<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['org_type'] !== 'restaurant') {
    header("Location: index.html");
    exit;
}

require 'db_connection.php';

// Query to fetch total donations and other metrics
$sql_total_donations = "SELECT COUNT(*) AS total_donations, SUM(quantity) AS total_quantity FROM food_donations WHERE restaurant_id = ?";
$stmt = $conn->prepare($sql_total_donations);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Query for weekly donation statistics
$sql_weekly_donations = "SELECT WEEK(donation_date) AS week, SUM(quantity) AS weekly_quantity FROM food_donations WHERE restaurant_id = ? GROUP BY WEEK(donation_date) ORDER BY WEEK(donation_date) DESC LIMIT 4"; // Last 4 weeks
$stmt_weekly = $conn->prepare($sql_weekly_donations);
$stmt_weekly->bind_param("i", $_SESSION['user_id']);
$stmt_weekly->execute();
$weekly_result = $stmt_weekly->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Donation Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="Screenshot 2024-11-23 154406.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Add background image to the body */
        body {
            background-image: url('img/IMG1.webp');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
        }

        /* Optional: Add a background overlay with blur */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            filter: blur(5px);
            z-index: -1;
        }

        /* Table Styling */
        .table-responsive {
            margin-top: 30px;
        }

        /* Make table text white */
        .table th, .table td {
            text-align: center;
            color: white;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Styling for Claim button */
        .claim-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .claim-btn:hover {
            background-color: #0056b3;
        }

        /* Navbar Styling */
        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .navbar-brand:hover, .nav-link:hover {
            color: #ff5722 !important;
        }

        /* Footer Styling */
        footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }

        footer .social a {
            margin: 0 10px;
            color: white;
            font-size: 24px;
            text-decoration: none;
        }

        footer .social a:hover {
            color: #ff5722;
        }
    </style>
</head>

<body>
    <!-- Background overlay -->
    <div class="overlay"></div>

    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="restaurant_dashboard.php">PlatePromise</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="restaurant_dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="donations.php">Manage Donations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reports.php">Reports</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Donation Report</h1>

        <!-- Total Donations -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Total Donations:</h4>
                <p><?php echo htmlspecialchars($data['total_donations']); ?> donations made</p>
            </div>
            <div class="col-md-6">
                <h4>Total Quantity Donated:</h4>
                <p><?php echo htmlspecialchars($data['total_quantity']); ?> units of food donated</p>
            </div>
        </div>

        <!-- Weekly Donations -->
        <h4>Weekly Donations</h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Quantity Donated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($week_data = $weekly_result->fetch_assoc()) { ?>
                        <tr>
                            <td>Week <?php echo htmlspecialchars($week_data['week']); ?></td>
                            <td><?php echo htmlspecialchars($week_data['weekly_quantity']); ?> units</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h4>PlatePromise Restaurant</h4>
                    <p>Address: 3, Near IT Park, Mahatma Gandhi Road, Bengaluru 560001</p>
                    <p>Contact No: <a href="tel:+91 986593359">+91 986593359</a></p>
                    <p>Email: <a href="mailto:platepromise@gmail.com">platepromise@gmail.com</a></p>
                </div>
                <div class="col-lg-4">
                    <h4>Important Links</h4>
                    <ul>
                        <li><a href="restaurant_dashboard.php">Dashboard</a></li>
                        <li><a href="donations.php">Manage Donations</a></li>
                        <li><a href="reports.php">Reports</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h4>Follow Us</h4>
                    <div class="social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
