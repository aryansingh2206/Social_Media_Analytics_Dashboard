<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "social_media_db";
$port = 3307;

$conn = new mysqli($host, $user, $password, $database, $port);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch Bearer Token from database
$sql = "SELECT api_key FROM api_keys WHERE platform = 'twitter' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $twitter_bearer_token = $row["api_key"];
} else {
    die("Twitter Bearer Token not found in database.");
}

$conn->close();
?>
