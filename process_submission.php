<?php
echo "Script is executing.";

// Include the database connection file
include 'db_connection.php';

// Retrieve form data
$projectName = $_POST["projectName"];
$projectSummary = $_POST["projectSummary"];
$projectDetails = $_POST["projectDetails"];
$projectField = $_POST["projectField"];
$projectYear = $_POST["projectYear"];

$authorFirstName = isset($_POST["authorFirstName"]) ? $_POST["authorFirstName"] : "";
$authorLastName = isset($_POST["authorLastName"]) ? $_POST["authorLastName"] : "";
$authorEmail = isset($_POST["authorEmail"]) ? $_POST["authorEmail"] : "";

// Debugging: Output received form data
echo "Received form data:";
var_dump($_POST);

try {
    // Prepare SQL statement using the appropriate database connection
    $query = "INSERT INTO projects (projectName, projectSummary, projectDetails, projectField, projectYear, authorFirstName, authorLastName, authorEmail) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connProject->prepare($query);

    if ($stmt) {
        // Bind parameters to prepared statement
        $stmt->bind_param("ssssssss", $projectName, $projectSummary, $projectDetails, $projectField, $projectYear, $authorFirstName, $authorLastName, $authorEmail);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Set session variable for success message
            session_start();
            $_SESSION['success_message'] = "Project submitted successfully";
            // Redirect to the homepage
            header("Location: index.php");
            exit(); // Stop further execution
        } else {
            // If execution fails, output the MySQL error
            echo "Error: " . mysqli_error($connProject);
        }

        // Close the statement
        $stmt->close();
    } else {
        throw new Exception("Failed to prepare the SQL statement.");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
