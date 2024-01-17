<?php
// Database configuration
$hostname = "localhost"; 
$username = "xcache";
$password = "yes";
$database = "project_database";

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

session_start();
?>
