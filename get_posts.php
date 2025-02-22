<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$host = "127.0.0.1"; // Use 127.0.0.1 instead of 'localhost' to avoid DNS resolution issues
$user = "root";
$password = ""; // Keep empty if no password is set
$database = "social_media_db";
$port = 3307; // Change to 3306 if needed

// Establish database connection
$conn = new mysqli($host, $user, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Fetch posts from the database
$sql = "SELECT platform, post_id, content, likes, comments, shares, created_at, username FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check if query executed successfully
if (!$result) {
    die(json_encode(["error" => "Query failed: " . $conn->error]));
}

// Fetch data as an array
$tweets = [];
while ($row = $result->fetch_assoc()) {
    $tweets[] = $row;
}

// Close the database connection
$conn->close();

// Return JSON response
echo json_encode($tweets);
?>
