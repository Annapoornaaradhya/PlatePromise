<?php
session_start();
// Redirect to index.html if the user is not logged in or not a restaurant
if (!isset($_SESSION['user_id']) || $_SESSION['org_type'] !== 'restaurant') {
    header("Location: index.html");
    exit;
}

// Debugging Tip: Check if restaurant_id is set
if (!isset($_SESSION['restaurant_id'])) {
    echo "Error: Restaurant ID is not set in the session. Please check your login process.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Dashboard</title>
    <link rel="icon" href="Screenshot 2024-11-23 154406.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Add background image to the body */
        body {
            background-image: url('img/IMG1.webp'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white; /* Text color to stand out against background */
        }

        /* Optional: Add a background overlay with blur */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
            filter: blur(5px); /* Add blur to the background */
            z-index: -1; /* Ensure overlay is behind the content */
        }

        /* Button Styling */
        .btn-custom {
            background-color: #ff5722; /* Bright orange color */
            color: white; /* White text */
            border: none; /* No border */
            padding: 10px 20px; /* Add padding */
            font-size: 16px; /* Increase font size */
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth effects */
        }

        .btn-custom:hover {
            background-color: #e64a19; /* Darker orange on hover */
            transform: scale(1.05); /* Slight enlargement on hover */
            color: white; /* Ensure text stays white */
        }

        /* Form Section Styling */
        .dashboard-box {
            background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background for form */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Subtle shadow for better visibility */
        }
    </style>
</head>
<body>
    <!-- Background overlay (semi-transparent dark overlay with blur) -->
    <div class="overlay"></div>

    <!-- Navbar -->
    <header class="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="#home">PlatePromise</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="restaurant_dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="manage_donations.php">Manage Donations</a></li>
                        <li class="nav-item"><a class="nav-link" href="track_food_waste.php">Track Food Waste</a></li>
                        <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Dashboard Section -->
    <section class="dashboard-section">
        <div class="container">
            <h1 class="display-4 text-center">Welcome to the Restaurant Dashboard</h1>
            <p class="text-center">Manage your donations, track food waste, and view reports here.</p>

            <!-- Food Donation Form -->
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="dashboard-box text-center">
                        <h3>Add Leftover Food Details</h3>
                        <script>
                            // Function to show a success alert
                            function showSuccessMessage() {
                                alert("Donation submitted successfully!");
                            }
                        </script>

                        <form action="add_donation.php" method="POST" onsubmit="showSuccessMessage();">
                            <!-- Hidden restaurant_id field passed from session -->
                            <input type="hidden" name="restaurant_id" value="<?php echo isset($_SESSION['restaurant_id']) ? $_SESSION['restaurant_id'] : ''; ?>" />
                            
                            <!-- Restaurant Name Field -->
                            <div class="form-group">
                                <label for="restaurant_name">Restaurant Name</label>
                                <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" value="<?php echo isset($_SESSION['restaurant_name']) ? $_SESSION['restaurant_name'] : ''; ?>" required>
                            </div>

                            <!-- Food Name Field -->
                            <div class="form-group">
                                <label for="food_name">Food Name</label>
                                <input type="text" class="form-control" id="food_name" name="food_name" required>
                            </div>

                            <!-- Quantity Field -->
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>

                            <!-- Description Field (Optional) -->
                            <div class="form-group">
                                <label for="description">Description (Optional)</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>

                            <!-- Location Field -->
                            <div class="form-group">
                                <label for="location">Restaurant Location</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>

                            <!-- Contact Person Field -->
                            <div class="form-group">
                                <label for="contact_person">Contact Person</label>
                                <input type="text" class="form-control" id="contact_person" name="contact_person" required>
                            </div>

                            <!-- Contact Number Field -->
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                            </div>

                            <!-- Donation Date Field -->
                            <div class="form-group">
                                <label for="donation_date">Donation Date</label>
                                <input type="date" class="form-control" id="donation_date" name="donation_date" required>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-custom">Add Donation</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h4>PlatePromise</h4>
                    <p>Address: 3, Near IT Park, Mahatma Gandhi Road, Bengaluru 560001</p>
                    <p>Contact No: <a href="tel:+91 986593359">+91 986593359</a></p>
                    <p>Email: <a href="mailto:platepromise@gmail.com">platepromise@gmail.com</a></p>
                </div>
                <div class="col-lg-4">
                    <h4>Important Links</h4>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#donations">Donations</a></li>
                        <li><a href="#missions">Missions</a></li>
                        <li><a href="#about">About us</a></li>
                        <li><a href="#contact">Contact us</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h4>Social Media</h4>
                        <div class="social">
                            <a href="#"><img src="img/icons/facebook.png" alt="facebook"></a>
                            <a href="#"><img src="img/icons/instagram.png" alt="inatagram"></a>
                            <a href="#"><img src="img/icons/youtube.png" alt="youtube"></a>
                            <a href="#"><img src="img/icons/linkedin.png" alt="linkedin"></a>
                            <a href="#"><img src="img/icons/gmail.png" alt="gnail"></a>
                        </div>
                        <p>Copyright &copy; 2022 | All Right Reserved</p>
                    </div>
            </div>
        </div>
    </footer>
</body>
</html>
