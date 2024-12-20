<?php
// Start a session or perform any required backend processing here
// Example: session_start();

// Include this code after your form submission logic
// if (form submission successful) { render this page }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Success</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: black;
            background-size: cover;
            font-family: 'Arial', sans-serif;
            color: #ffffff;
        }

        .success-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            backdrop-filter: blur(5px);
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
        }

        .success-icon {
            font-size: 100px;
            size:30px;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .success-message {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .cta-button {
            padding: 15px 30px;
            font-size: 18px;
            color: #ffffff;
            background-color: #4CAF50;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="success-container">
        
            <img src="img/icons/don/gif.gif"> <img/>
        <a href="index.html" class="cta-button">Go to Homepage</a>
    </div>
</body>
</html>