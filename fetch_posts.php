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

$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

$tweets = [];
while ($row = $result->fetch_assoc()) {
    $tweets[] = $row;
}

header('Content-Type: application/json');
echo json_encode($tweets);
$conn->close();
?>
