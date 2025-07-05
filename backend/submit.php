<?php
// DB connection variables — update with your own values
$host = "localhost";
$dbname = "u730929756_leadxcel_db";
$username = "my_user";
$password = "Leadxcel##321";

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data and sanitize
$name = htmlspecialchars(trim($_POST["firstName"] ?? ''));
$company = htmlspecialchars(trim($_POST["company"] ?? ''));
$phone = htmlspecialchars(trim($_POST["phone"] ?? ''));
$email = htmlspecialchars(trim($_POST["email"] ?? ''));
$service = htmlspecialchars(trim($_POST["industry"] ?? ''));
$goals = htmlspecialchars(trim($_POST["message"] ?? ''));

// Validate required fields
if (!$name || !$company || !$email || !$goals) {
    echo "Please fill in all required fields.";
    exit;
}

// Prepare SQL insert
$stmt = $conn->prepare("INSERT INTO contact (name, company_name, phone, email, service, goals) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $company, $phone, $email, $service, $goals);

// Execute and respond
if ($stmt->execute()) {
    echo "Thank you! Your data has been saved.";
    // Optional: redirect to a thank you page
    // header("Location: thank-you.html"); exit;
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
