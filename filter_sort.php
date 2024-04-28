<?php
// Database connection
include 'db_connection.php';

// Include filter parameters from session storage if available
session_start();
$filterField = isset($_SESSION['filterField']) ? $_SESSION['filterField'] : 'all';
$filterYear = isset($_SESSION['filterYear']) ? $_SESSION['filterYear'] : 'all';
$filterSort = isset($_SESSION['filterSort']) ? $_SESSION['filterSort'] : 'recent';

// Get total number of projects
$total_query = "SELECT COUNT(DISTINCT projects.id) AS total FROM projects LEFT JOIN project_images ON projects.id = project_images.project_id WHERE projects.inHallOfFame = 1";

// Modify total query based on filter parameters
if ($filterField !== 'all' || $filterYear !== 'all') {
    $total_query .= " AND (";

    if ($filterField !== 'all') {
        $total_query .= " field = '$filterField'";
    }

    if ($filterField !== 'all' && $filterYear !== 'all') {
        $total_query .= " OR";
    }

    if ($filterYear !== 'all') {
        // Make sure the year format matches what's stored in the database
        $total_query .= " projectYear = '$filterYear'";
    }

    $total_query .= ")";
}

$total_result = mysqli_query($connProject, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_projects = $total_row['total'];

// Calculate total pages
$total_pages = ceil($total_projects / $results_per_page);

// Get projects for the current page with applied filters
$query = "SELECT DISTINCT projects.id, projects.projectName, projects.projectSummary, projects.authorEmail, project_images.image_path, projects.score 
          FROM projects 
          LEFT JOIN project_images ON projects.id = project_images.project_id 
          WHERE projects.inHallOfFame = 1";

// Apply filters
if ($filterField !== 'all' || $filterYear !== 'all') {
    $query .= " AND (";

    if ($filterField !== 'all') {
        $query .= " field = '$filterField'";
    }

    if ($filterField !== 'all' && $filterYear !== 'all') {
        $query .= " OR";
    }

    if ($filterYear !== 'all') {
        // Making sure the year format matches what's stored in the database
        $query .= " projectYear = '$filterYear'";
    }

    $query .= ")";
}

// Sorting
if ($filterSort === 'most-liked') {
    $query .= " ORDER BY projects.score DESC";
} elseif ($filterSort === 'recent') {
    $query .= " ORDER BY projects.id DESC";
}

$query .= " LIMIT $results_per_page OFFSET $offset";

$result = mysqli_query($connProject, $query);

// Build HTML for projects
$html = '';
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<div class="project">';
        $html .= '<div class="project-info">';
        
        // Show project image
        if (!empty($row['image_path'])) {
            $html .= '<img src="' . $row['image_path'] . '" alt="' . $row['projectName'] . ' Image">';
        } else {
            $html .= '<img src="default_image.png" alt="Default Image">';
        }
        
        $html .= '<h2>' . $row['projectName'] . '</h2>';
        $html .= '<p>' . $row['projectSummary'] . '</p>';
        $html .= '</div>';
        $html .= '<div class="project-actions">';
        // Link "View Project" button to the project details page
        $html .= '<a href="projects.php?id=' . $row['id'] . '"><button class="view-project"><i class="fa-regular fa-folder-open"></i></button></a>';
        // Show medal button and score
        $html .= '<button class="medal-icon" data-project-id="' . $row['id'] . '"><i class="fa-solid fa-medal"></i> <span class="score">' . $row['score'] . '</span></button>';
        // Add the contact icon button with preloaded mail to
        if (!empty($row['authorEmail'])) {
            $html .= '<a href="mailto:' . $row['authorEmail'] . '?subject=Regarding%20Project%20' . $row['id'] . '"><button class="contact-icon"><i class="fa-solid fa-address-book"></i></button></a>';
        } else {
            $html .= '<button class="contact-icon" disabled><i class="fa-solid fa-address-book"></i></button>';
        }
        $html .= '</div>';
        $html .= '</div>';
    }
} else {
    // No projects found
    $html .= "<p>No projects found.</p>";
}

echo json_encode(['projectHtml' => $html]);
?>
