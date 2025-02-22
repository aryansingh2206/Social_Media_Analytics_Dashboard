<?php
require 'auth.php'; // Ensure API key is correctly fetched

$host = "localhost";
$user = "root";
$password = "";
$database = "social_media_db";
$port = 3307;

// Connect to MySQL
$conn = new mysqli($host, $user, $password, $database, $port);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Fetch Twitter API key from the database
$sql = "SELECT api_key FROM api_keys WHERE platform = 'twitter' LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $twitter_api_key = $row["api_key"];
} else {
    die(json_encode(["error" => "Twitter API key not found in database."]));
}

// Twitter API request
$twitter_bearer_token = $twitter_api_key;
$twitter_url = "https://api.twitter.com/2/tweets/search/recent?query=technology&tweet.fields=created_at,public_metrics,author_id";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $twitter_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $twitter_bearer_token"]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code != 200) {
    die(json_encode(["error" => "API request failed with HTTP Code: $http_code", "response" => $response]));
}

// Decode JSON response
$tweets = json_decode($response, true);

// Validate API response
if (!isset($tweets['data']) || !is_array($tweets['data'])) {
    die(json_encode(["error" => "No valid tweets found", "response" => $tweets]));
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO posts (platform, post_id, username, content, likes, comments, shares, created_at) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?) 
ON DUPLICATE KEY UPDATE content=VALUES(content), likes=VALUES(likes), comments=VALUES(comments), shares=VALUES(shares), created_at=VALUES(created_at)");

foreach ($tweets['data'] as $tweet) {
    $platform = "twitter";
    $post_id = $tweet['id'];
    $content = $tweet['text'];
    $username = "User_" . $tweet['author_id']; // Placeholder username
    $likes = $tweet['public_metrics']['like_count'];
    $comments = $tweet['public_metrics']['reply_count'];
    $shares = $tweet['public_metrics']['retweet_count'];
    $created_at = $tweet['created_at'];

    $stmt->bind_param("ssssiiis", $platform, $post_id, $username, $content, $likes, $comments, $shares, $created_at);
    $stmt->execute();
}

// Close connections
$stmt->close();
$conn->close();

// Success response
echo json_encode(["success" => "Tweets fetched and stored successfully"]);
?>
