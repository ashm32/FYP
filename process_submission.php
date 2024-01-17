<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $projectName = $_POST["projectName"];
    $projectSummary = $_POST["projectSummary"];
    $projectDetails = $_POST["projectDetails"];
    $projectField = $_POST["projectField"];
    $projectYear = $_POST["projectYear"];
    $videoUpload = $_FILES["videoUpload"];
    $imageUpload = $_FILES["imageUpload"];
    $includeAuthorDetails = isset($_POST["includeAuthorDetails"]) ? $_POST["includeAuthorDetails"] : false;
    $authorFirstName = $includeAuthorDetails ? $_POST["authorFirstName"] : null;
    $authorLastName = $includeAuthorDetails ? $_POST["authorLastName"] : null;
    $authorEmail = $includeAuthorDetails ? $_POST["authorEmail"] : null;

    // Process and save the data (replace this with your logic)
    // For example, you can store the data in a database, file, or send it via email.

    // Dummy response for testing
    $response = array(
        'status' => 'success',
        'message' => 'Project submitted successfully!',
        'data' => array(
            'projectName' => $projectName,
            'projectSummary' => $projectSummary,
            // Add more data as needed
        ),
    );

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request
    http_response_code(400);
    echo "Invalid request.";
}
?>
