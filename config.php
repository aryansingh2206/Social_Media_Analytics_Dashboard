<?php
$host = "localhost";
$user = "root"; // Default XAMPP username
$pass = ""; // Default XAMPP password is empty
$db = "social_media_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Database connected successfully!";
?>
