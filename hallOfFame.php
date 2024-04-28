<?php
// Check if project_id is set and not empty
if (isset($_GET['project_id']) && !empty($_GET['project_id'])) {
    // Get the project_id from the GET parameters
    $project_id = $_GET['project_id'];
    
    // Include the database connection file
    include 'db_connection.php';

    // Prepare and execute the SQL query to update the project status in the database
    $query = "UPDATE projects SET inHallOfFame = 1 WHERE id = ?";
    $stmt = $connProject->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirect back to index.php with the project_id as parameter
    header("Location: index.php?id=$project_id");
    exit();
} else {
    // Display error message for invalid request
    echo "Invalid request.";
}


?>
