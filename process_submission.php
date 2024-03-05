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
    // Check if video file is uploaded
    if ($_FILES['videoUpload']['error'] == UPLOAD_ERR_OK) {
        $videoTempName = $_FILES['videoUpload']['tmp_name'];
        $videoName = $_FILES['videoUpload']['name'];
        $videoPath = 'uploads/' . $videoName; // Define the path where the video will be stored on the server

        // Move the uploaded video file 
        if (move_uploaded_file($videoTempName, $videoPath)) {
            // File moved successfully
            $videoPaths[] = $videoPath; // Store video path for later use
        } else {
            // File move failed, handle the error
            echo 'Failed to move uploaded video file.';
        }
    }

    // Check if image files are uploaded
    $imagePaths = array();
    if (!empty($_FILES['imageUpload'])) {
        foreach ($_FILES['imageUpload']['tmp_name'] as $key => $imageTempName) {
            if ($_FILES['imageUpload']['error'][$key] == UPLOAD_ERR_OK) {
                $imageName = $_FILES['imageUpload']['name'][$key];
                $imagePath = 'uploads/' . $imageName; // Defines the path where the image will be stored on the server

                // Move the uploaded image file
                if (move_uploaded_file($imageTempName, $imagePath)) {
                    // File moved successfully
                    $imagePaths[] = $imagePath; // Store image path for later use
                } else {
                    // File move failed, handle the error
                    echo 'Failed to move uploaded image file: ' . $imageName;
                }
            }
        }
    }

    // Prepare SQL statement using the appropriate database connection
    $query = "INSERT INTO projects (projectName, projectSummary, projectDetails, projectField, projectYear, videoUpload, authorFirstName, authorLastName, authorEmail) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connProject->prepare($query);

    if ($stmt) {
        // Bind parameters to prepared statement
        $stmt->bind_param("sssssssss", $projectName, $projectSummary, $projectDetails, $projectField, $projectYear, $videoPath, $authorFirstName, $authorLastName, $authorEmail);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Get the ID of the inserted project
            $projectId = $stmt->insert_id;

            // Insert image paths into separate table
            if (!empty($imagePaths)) {
                $insertImageQuery = "INSERT INTO project_images (project_id, image_path) VALUES (?, ?)";
                $stmtImage = $connProject->prepare($insertImageQuery);

                if ($stmtImage) {
                    foreach ($imagePaths as $imagePath) {
                        $stmtImage->bind_param("is", $projectId, $imagePath);
                        $stmtImage->execute();
                    }
                    $stmtImage->close();
                } else {
                    echo "Error: Failed to prepare image insertion statement.";
                }
            }

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
