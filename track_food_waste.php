<?php
session_start();

// Database connection
$mysqli = new mysqli("localhost", "root", "", "platepromise");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query to get total donations
$total_donations_sql = "SELECT SUM(quantity) AS total_donated FROM food_donations";
$total_donations_result = $mysqli->query($total_donations_sql);
$total_donated = $total_donations_result->fetch_assoc()['total_donated'];

// Query to get weekly donations (last 7 days)
$weekly_donations_sql = "SELECT SUM(quantity) AS weekly_donated FROM food_donations WHERE donation_date >= CURDATE() - INTERVAL 7 DAY";
$weekly_donations_result = $mysqli->query($weekly_donations_sql);
$weekly_donated = $weekly_donations_result->fetch_assoc()['weekly_donated'];

// Query to get the daily donations for the past 7 days
$weekly_data_sql = "SELECT DAYNAME(donation_date) AS day, SUM(quantity) AS daily_donated
                    FROM food_donations
                    WHERE donation_date >= CURDATE() - INTERVAL 7 DAY
                    GROUP BY DAYNAME(donation_date)
                    ORDER BY donation_date ASC";
$weekly_data_result = $mysqli->query($weekly_data_sql);

// Arrays to store the chart data
$days_of_week = [];
$daily_donations = [];

// Populate the arrays with data
while ($row = $weekly_data_result->fetch_assoc()) {
    $days_of_week[] = $row['day'];
    $daily_donations[] = $row['daily_donated'];
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Food Waste</title>
    <link rel="icon" href="Screenshot 2024-11-23 154406.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #000; /* Black background */
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }

        .dashboard-box {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        .card {
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
        }

        .card-header {
            background-color: #333;
            color: white;
        }

        .card-body {
            background-color: #444;
            color: white;
        }

        .btn-custom {
            background-color: #ff5722;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-custom:hover {
            background-color: #e64a19;
        }

        .chart-container {
            width: 80%;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="display-4">Track Food Waste</h1>
        <p class="lead">Here you can track the total food donated and weekly updates of donations.</p>

        <!-- Dashboard Box -->
        <div class="row">
            <!-- Total Donations Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Total Donations Till Date</h3>
                    </div>
                    <div class="card-body">
                        <h4><?php echo number_format($total_donated); ?> kg</h4>
                        <p>Total food donated by all restaurants until now.</p>
                    </div>
                </div>
            </div>

            <!-- Weekly Donations Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Weekly Donations</h3>
                    </div>
                    <div class="card-body">
                        <h4><?php echo number_format($weekly_donated); ?> kg</h4>
                        <p>Total food donated in the last 7 days.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <h3 class="mt-5">Weekly Donation Trend</h3>
            <canvas id="weeklyDonationsChart"></canvas>
        </div>

        <script>
            var ctx = document.getElementById('weeklyDonationsChart').getContext('2d');
            var weeklyDonationsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($days_of_week); ?>, // PHP array to JavaScript
                    datasets: [{
                        label: 'Weekly Donations (kg)',
                        data: <?php echo json_encode($daily_donations); ?>, // PHP array to JavaScript
                        backgroundColor: 'rgba(255, 87, 34, 0.2)',
                        borderColor: 'rgba(255, 87, 34, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>

</body>

</html>
