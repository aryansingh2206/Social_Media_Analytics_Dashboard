<?php
$host = "localhost";
$user = "root";  // Default XAMPP MySQL user
$pass = "";      // Default password is empty
$dbname = "social_media_db"; // The correct database name
$port = 3307;    // Your MySQL port

$conn = new mysqli($host, $user, $pass, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
