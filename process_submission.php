<?php
echo "Script is executing.";

// Include the database connection file
include 'db_connection.php';

// Check if all required form fields are set
if (
    isset($_POST["projectName"]) && isset($_POST["projectSummary"]) && isset($_POST["projectDetails"]) &&
    isset($_POST["projectField"]) && isset($_POST["projectYear"]) && isset($_POST["authorFirstName"]) &&
    isset($_POST["authorLastName"]) && isset($_POST["authorEmail"])
) {
    // Retrieve form data
    $projectName = $_POST["projectName"];
    $projectSummary = $_POST["projectSummary"];
    $projectDetails = $_POST["projectDetails"];
    $projectField = $_POST["projectField"];
    $projectYear = $_POST["projectYear"];
    $openToWork = isset($_POST['OpenToWork']) ? 1 : 0;
    $authorFirstName = $_POST["authorFirstName"];
    $authorLastName = $_POST["authorLastName"];
    $authorEmail = $_POST["authorEmail"];

    // Check if video file is uploaded
    $videoUpload = isset($_FILES['videoUpload']) ? $_FILES['videoUpload']['name'] : '';
    $videoPath = '';
    if (!empty($videoUpload)) {
        if ($_FILES['videoUpload']['error'] == UPLOAD_ERR_OK) {
            $videoTempName = $_FILES['videoUpload']['tmp_name'];
            $videoName = $_FILES['videoUpload']['name'];
            $videoPath = 'uploads/' . $videoName;

            // Move the uploaded video file 
            if (move_uploaded_file($videoTempName, $videoPath)) {
                // File moved successfully
            } else {
                // File move failed, handle the error
                echo 'Failed to move uploaded video file.';
            }
        }
    }

    // Check if image files are uploaded
    $imageUpload = isset($_FILES['imageUpload']) ? $_FILES['imageUpload']['name'] : '';
    $imagePaths = array();
    if (!empty($imageUpload)) {
        foreach ($_FILES['imageUpload']['tmp_name'] as $key => $imageTempName) {
            if ($_FILES['imageUpload']['error'][$key] == UPLOAD_ERR_OK) {
                $imageName = $_FILES['imageUpload']['name'][$key];
                $imagePath = 'uploads/' . $imageName;

                // Move the uploaded image file
                if (move_uploaded_file($imageTempName, $imagePath)) {
                    // File moved successfully
                    $imagePaths[] = $imagePath;
                } else {
                    // File move failed, handle the error
                    echo 'Failed to move uploaded image file: ' . $imageName;
                }
            }
        }
    }

    // Prepare SQL statement using the appropriate database connection
    $stmt = $connProject->prepare("INSERT INTO projects (projectName, projectSummary, projectDetails, projectField, projectYear, videoUpload, imageUpload, authorFirstName, authorLastName, authorEmail, inHallOfFame, open_to_work) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)");

    // Check if the statement is prepared successfully
    if ($stmt) {
        // Bind parameters to prepared statement
        $stmt->bind_param("ssssssssssi", $projectName, $projectSummary, $projectDetails, $projectField, $projectYear, $videoPath, $imageUpload, $authorFirstName, $authorLastName, $authorEmail, $openToWork);

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
        echo "Error: Failed to prepare the SQL statement.";
    }
} else {
    echo "Error: Not all required form fields are set.";
}
?>
