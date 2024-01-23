<?php
// Include the database connection file
include '/Applications/MAMP/htdocs/db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get form data
    $projectName = isset($_POST["projectName"]) ? htmlspecialchars($_POST["projectName"]) : '';
    $projectSummary = isset($_POST["projectSummary"]) ? htmlspecialchars($_POST["projectSummary"]) : '';
    $projectDetails = isset($_POST["projectDetails"]) ? htmlspecialchars($_POST["projectDetails"]) : '';
    $projectField = isset($_POST["projectField"]) ? htmlspecialchars($_POST["projectField"]) : '';
    $projectYear = isset($_POST["projectYear"]) ? htmlspecialchars($_POST["projectYear"]) : '';
    $includeAuthorDetails = isset($_POST["includeAuthorDetails"]) ? $_POST["includeAuthorDetails"] : false;
    $authorFirstName = $includeAuthorDetails ? (isset($_POST["authorFirstName"]) ? htmlspecialchars($_POST["authorFirstName"]) : '') : null;
    $authorLastName = $includeAuthorDetails ? (isset($_POST["authorLastName"]) ? htmlspecialchars($_POST["authorLastName"]) : '') : null;
    $authorEmail = $includeAuthorDetails ? (isset($_POST["authorEmail"]) ? htmlspecialchars($_POST["authorEmail"]) : '') : null;

    // Validate and process file uploads
    $videoUpload = handleFileUpload($_FILES["videoUpload"]);
    $imageUpload = handleFileUpload($_FILES["imageUpload"]);

    try {
        $connProject = connectToDatabase("project_submission");

        // Validate data before proceeding
        if ($projectName && $projectSummary && $projectField && $projectYear && $videoUpload && $imageUpload) {
            // Insert project data into the 'projects' table
            $insertProjectQuery = "INSERT INTO projects (project_name, summary, description, field, year, video_url, photo_url, author_first_name, author_last_name, author_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtInsertProject = $connProject->prepare($insertProjectQuery);

            if ($stmtInsertProject) {
                // Bind parameters ('s' for string and 'i' for integer)
                $stmtInsertProject->bind_param("ssssisssss", $projectName, $projectSummary, $projectDetails, $projectField, $projectYear, $videoUpload, $imageUpload, $authorFirstName, $authorLastName, $authorEmail);

                // Check inserted successfully
                if ($stmtInsertProject->execute()) {
                    // Project submission successful
                    $response = array(
                        'status' => 'success',
                        'message' => 'Project submitted successfully!',
                        'data' => array(
                            'projectName' => $projectName,
                            'projectSummary' => $projectSummary,
                        ),
                    );

                    // Redirect to home page
                    header("Location: index.html");
                    exit();
                } else {
                    // Project submission failed
                    $response = array(
                        'status' => 'error',
                        'message' => 'Project submission failed. Please try again.',
                    );
                    error_log("Insertion failed: " . $stmtInsertProject->error);
                }

                // Close the database statement
                $stmtInsertProject->close();
            } else {
                throw new Exception("Failed to prepare the SQL statement: " . $connProject->error);
            }
        } else {
            // Invalid data provided
            $response = array(
                'status' => 'error',
                'message' => 'Invalid data provided. Please check your input.',
            );
        }
    } catch (Exception $e) {
        // Handle exceptions, log the error, or display an appropriate message
        $response = array(
            'status' => 'error',
            'message' => 'An error occurred. Please try again later.',
        );
        error_log("Exception: " . $e->getMessage());
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request
    http_response_code(400);
    echo "Invalid request.";
}

// Function to handle file uploads
// function handleFileUpload($file) {
//     $targetDirectory = "uploads/";
//     $targetFile = $targetDirectory . basename($file["name"]);
    
//     // Check if the file was uploaded successfully
//     if (move_uploaded_file($file["tmp_name"], $targetFile)) {
//         return $targetFile; // Return the file URL or path
//     } else {
//         return false; // Return false if the upload fails
//     }
// }
?>
