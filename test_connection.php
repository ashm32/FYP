<?php
$hostname = "localhost";
$username = "xcache";
$password = "fyp";
$databaseName = "project_submission";

$conn = new mysqli($hostname, $username, $password, $databaseName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully to database: $databaseName";
?>
