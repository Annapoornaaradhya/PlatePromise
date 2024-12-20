<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['org_type'] !== 'ngo') {
    header("Location: index.html");
    exit;
}

require 'db_connection.php';

$sql = "SELECT * FROM food_donations WHERE status = 'pending'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="Screenshot 2024-11-23 154406.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Add background image to the body */
        body {
            background-image: url('file.jpg');
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

        /* Button Styling */
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

        /* Table Styling */
        .table th, .table td {
            text-align: center;
            color: white;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: rgba(0, 0, 0, 0.5);
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
    <header class="header">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <a class="navbar-brand d-flex align-items-center" href="#home">
                        <img src="img/pplogo.png" alt="PlatePromise Logo" style="width: 40px; height: 40px; margin-right: 10px;">
                        PlatePromise
                    </a>
                    <a class="navbar-brand" href="#home"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="ngo_dashboard.php">Dashboard</a>
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
                </nav>
                
            </div>
        </header>
  

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Available Donations</h1>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Restaurant ID</th>
                        <th>Restaurant Name</th>
                        <th>Food Name</th>
                        <th>Quantity</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['restaurant_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['restaurant_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['food_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td>
                                <button class="claim-btn" data-id="<?php echo $row['id']; ?>">Claim</button>
                            </td>
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
                    <h4>PlatePromise NGO</h4>
                    <p>Address: 3, Near IT Park, Mahatma Gandhi Road, Bengaluru 560001</p>
                    <p>Contact No: <a href="tel:+91 986593359">+91 986593359</a></p>
                    <p>Email: <a href="mailto:platepromise@gmail.com">platepromise@gmail.com</a></p>
                </div>
                <div class="col-lg-4">
                    <h4>Important Links</h4>
                    <ul>
                        <li><a href="ngo_dashboard.php">Dashboard</a></li>
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

    <!-- JavaScript to handle Claim button -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // When a claim button is clicked
            $('.claim-btn').click(function() {
                var donationId = $(this).data('id'); // Get the donation ID from the button's data-id attribute

                // Send an AJAX request to update the donation status
                $.ajax({
                    url: 'update_donation_status.php',  // The PHP file to handle the request
                    method: 'POST',
                    data: { id: donationId }, // Send the donation ID to the PHP file
                    success: function(response) {
                        // If the request is successful, update the table or show a success message
                        if (response == 'success') {
                            alert('Donation claimed successfully!');
                            location.reload(); // Reload the page to reflect the updated status
                        } else {
                            alert('Error claiming donation');
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>
