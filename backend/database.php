<?php
$host = "localhost";
$dbname = "u730929756_leadxcel_db";
$username = "u730929756_my_user";
$password = "Leadxcel##321";

// Create the connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
