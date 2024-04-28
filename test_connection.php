<?php
// Database credentials
$db_host = 'localhost';
$db_user = 'xcache';
$db_pass = 'fyp';
$db_name = 'project_submission';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection and log result
if ($conn->connect_error) {
    // Log connection failure
    error_log("Database connection failed: " . $conn->connect_error);
    // Output error (optional)
    die("Database connection failed: " . $conn->connect_error);
} else {
    // Log connection success
    error_log("Database connection successful");
    // Success message 
    echo "Database connection successful";
}

?>
