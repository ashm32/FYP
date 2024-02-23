<?php
// Include the database connection file
include 'db_connection.php';

// Initialise error flag
$errorFlag = false;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $projectName = $_POST["projectName"];
    $projectSummary = $_POST["projectSummary"];
    $projectDetails = $_POST["projectDetails"];
    $projectField = $_POST["projectField"];
    $projectYear = $_POST["projectYear"];
    $videoUpload = $_FILES["videoUpload"]["name"];
    $imageUpload = $_FILES["imageUpload"]["name"];
    $authorFirstName = isset($_POST["authorFirstName"]) ? $_POST["authorFirstName"] : "";
    $authorLastName = isset($_POST["authorLastName"]) ? $_POST["authorLastName"] : "";
    $authorEmail = isset($_POST["authorEmail"]) ? $_POST["authorEmail"] : "";

    // Upload files
    $targetDir = "uploads/";
    move_uploaded_file($_FILES["videoUpload"]["tmp_name"], $targetDir . $videoUpload);
    move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $targetDir . $imageUpload);

    // Debugging: Output received form data
    echo "Received form data:";
    var_dump($_POST);

    try {
        // Prepare SQL statement
        $query = "INSERT INTO projects (projectName, projectSummary, projectDetails, projectField, projectYear, videoUpload, imageUpload, authorFirstName, authorLastName, authorEmail) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connProject->prepare($query); // Use $connProject for project_submission database

        if ($stmt) {
            // Bind parameters to prepared statement
            $stmt->bind_param("ssssssssss", $projectName, $projectSummary, $projectDetails, $projectField, $projectYear, $videoUpload, $imageUpload, $authorFirstName, $authorLastName, $authorEmail);

            // Debugging: Output prepared statement and bound parameters
            echo "Prepared statement:";
            var_dump($query);
            echo "Bound parameters:";
            var_dump([$projectName, $projectSummary, $projectDetails, $projectField, $projectYear, $videoUpload, $imageUpload, $authorFirstName, $authorLastName, $authorEmail]);

            // Execute the prepared statement
            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: Execution failed";
            }

            // Close the statement
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare the SQL statement.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
