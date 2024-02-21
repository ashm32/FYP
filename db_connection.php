<?php
/// Function to create a database connection
function connectToDatabase($databaseName) {
    $hostname = "localhost";
    $username = "xcache";
    $password = "fyp";

    // Create connection
    $conn = new mysqli($hostname, $username, $password, $databaseName);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8");

    // Temporary logging
    error_log("Connection successful to database: $databaseName");
    echo "Connection successful to database: $databaseName"; // Add this line for debugging

    return $conn;
}

// Connect to project_database
$connProject = connectToDatabase("project_submission");

// Connect to user_authentication database
$connAuthentication = connectToDatabase("user_authentication");

session_start();

?>

