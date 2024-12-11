<?php
// Database credentials
$host = "localhost";
$dbname = "platepromise";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form data is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $org_type = $_POST['org_type'];
        $org_name = $_POST['org_name'];
        $contact_person = $_POST['contact_person'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing
        $license_number = $_POST['license_number'] ?? null;
        $ngo_id = $_POST['ngo_id'] ?? null;

        // Insert data into the database
        $stmt = $pdo->prepare("INSERT INTO users (org_type, org_name, contact_person, email, phone, address, password, license_number, ngo_id)
                               VALUES (:org_type, :org_name, :contact_person, :email, :phone, :address, :password, :license_number, :ngo_id)");
        $stmt->execute([
            ':org_type' => $org_type,
            ':org_name' => $org_name,
            ':contact_person' => $contact_person,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':password' => $password,
            ':license_number' => $license_number,
            ':ngo_id' => $ngo_id
        ]);

        // File path for the CSV
        $file_path = "registrations.csv";

        // Check if file exists to write headers
        $file_exists = file_exists($file_path);

        // Open the file in append mode
        $file = fopen($file_path, "a");

        // Write the header row if the file doesn't exist
        if (!$file_exists) {
            fputcsv($file, ['Organization Type', 'Organization Name', 'Contact Person', 'Email', 'Phone', 'Address', 'License Number', 'NGO ID', 'Registration Date']);
        }

        // Write the data row
        fputcsv($file, [
            $org_type,
            $org_name,
            $contact_person,
            $email,
            $phone,
            $address,
            $license_number,
            $ngo_id,
            date("Y-m-d H:i:s") // Current timestamp
        ]);

        // Close the file
        fclose($file);

        // Redirect to the homepage with a success message
        echo "<script>
            alert('Registration successful! Redirecting to the homepage...');
            window.location.href = 'index.html';
        </script>";
        exit();
    }
} catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
}
?>
