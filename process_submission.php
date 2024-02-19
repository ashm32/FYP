<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include_once "db_connection.php"; 
    
    // Retrieve form data
    $projectName = $_POST['projectName'];
    $projectSummary = $_POST['projectSummary'];
    $projectDetails = $_POST['projectDetails'];
    $projectField = $_POST['projectField'];
    $projectYear = $_POST['projectYear'];
    $authorFirstName = $_POST['authorFirstName'] ?? '';
    $authorLastName = $_POST['authorLastName'] ?? '';
    $authorEmail = $_POST['authorEmail'] ?? '';
    
    // Sanitize input
    $projectName = mysqli_real_escape_string($conn, $projectName);
    $projectSummary = mysqli_real_escape_string($conn, $projectSummary);
    $projectDetails = mysqli_real_escape_string($conn, $projectDetails);
    $projectField = mysqli_real_escape_string($conn, $projectField);
    $projectYear = mysqli_real_escape_string($conn, $projectYear);
    $authorFirstName = mysqli_real_escape_string($conn, $authorFirstName);
    $authorLastName = mysqli_real_escape_string($conn, $authorLastName);
    $authorEmail = mysqli_real_escape_string($conn, $authorEmail);
    
    // Insert data into the database
    $sql = "INSERT INTO project_submissions (project_name, project_summary, project_details, project_field, project_year, author_first_name, author_last_name, author_email) 
            VALUES ('$projectName', '$projectSummary', '$projectDetails', '$projectField', '$projectYear', '$authorFirstName', '$authorLastName', '$authorEmail')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Project submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    // Close database connection
    mysqli_close($conn);
} else {
    // Redirect to the form page if accessed directly
    header("Location: student_dash.html");
    exit();
}
?>
