<?php
// Database connection
include 'db_connection.php';

// Define the limit for SQL based on the current page and projects per page
$results_per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 9;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $results_per_page;

// Query to fetch projects for the current page
$query = "SELECT SQL_CALC_FOUND_ROWS DISTINCT projects.id, projects.projectName, projects.projectSummary, projects.authorEmail, project_images.image_path, projects.score 
          FROM projects 
          LEFT JOIN project_images ON projects.id = project_images.project_id 
          WHERE projects.inHallOfFame = 1
          ORDER BY projects.id DESC
          LIMIT $results_per_page OFFSET $offset";

$result = mysqli_query($connProject, $query);

// Fetch total number of projects
$total_result = mysqli_query($connProject, "SELECT FOUND_ROWS() AS total");
$total_row = mysqli_fetch_assoc($total_result);
$total_projects = $total_row['total'];

// Calculate total pages
$total_pages = ceil($total_projects / $results_per_page);

// Generate project list HTML
$projectHtml = '';
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Generate HTML for each project item
        // Append it to $projectHtml
    }
} else {
    // No projects found
    $projectHtml = "<p>No projects found.</p>";
}

// Generate pagination HTML
$paginationHtml = '';
if ($total_pages > 1) {
    // Generate pagination HTML with links for each page
    // Append it to $paginationHtml
}

// Prepare JSON response
$response = array(
    'projectHtml' => $projectHtml,
    'paginationHtml' => $paginationHtml
);

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
