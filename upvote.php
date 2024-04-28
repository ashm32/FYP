<?php
session_start();

// Include database connection
include 'db_connection.php';

// Check if project ID is received
if (isset($_POST['projectId'])) {
    $projectId = $_POST['projectId'];

    // Check if the user has already upvoted this project
    if (isset($_SESSION['upvoted_projects']) && in_array($projectId, $_SESSION['upvoted_projects'])) {
        // - the score by 1
        $updateQuery = "UPDATE projects SET score = score - 1 WHERE id = $projectId";
        $result = mysqli_query($connProject, $updateQuery);

        if ($result) {
            // Get updated score
            $scoreQuery = "SELECT score FROM projects WHERE id = $projectId";
            $scoreResult = mysqli_query($connProject, $scoreQuery);
            $row = mysqli_fetch_assoc($scoreResult);
            $updatedScore = $row['score'];

            // Remove the project ID from the session to allow downvoting
            $_SESSION['upvoted_projects'] = array_diff($_SESSION['upvoted_projects'], array($projectId));

            // Return updated score as JSON response
            echo json_encode(['success' => true, 'score' => $updatedScore]);
        } else {
            // Return error message if update fails
            echo json_encode(['success' => false, 'message' => 'Failed to update score']);
        }
    } else {
        // Update score in the database for upvoting
        $updateQuery = "UPDATE projects SET score = score + 1 WHERE id = $projectId";
        $result = mysqli_query($connProject, $updateQuery);

        if ($result) {
            // Get updated score
            $scoreQuery = "SELECT score FROM projects WHERE id = $projectId";
            $scoreResult = mysqli_query($connProject, $scoreQuery);
            $row = mysqli_fetch_assoc($scoreResult);
            $updatedScore = $row['score'];

            // Store the project ID in the session to prevent multiple upvotes
            $_SESSION['upvoted_projects'][] = $projectId;

            // Return updated score as JSON response
            echo json_encode(['success' => true, 'score' => $updatedScore]);
        } else {
            // Return error message if update fails
            echo json_encode(['success' => false, 'message' => 'Failed to update score']);
        }
    }
} else {
    // Return error message if project ID is not received
    echo json_encode(['success' => false, 'message' => 'Project ID not provided']);
}
?>
