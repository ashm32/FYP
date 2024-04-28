<?php
// Database connection
include 'db_connection.php';

// Get filter parameters
$field = isset($_GET['field']) ? $_GET['field'] : 'all';
$year = isset($_GET['year']) ? $_GET['year'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent';

// Build SQL query based on filter parameters
$query = "SELECT projects.id, projects.projectName, projects.projectSummary, projects.authorEmail, project_images.image_path, projects.score 
          FROM projects 
          LEFT JOIN project_images ON projects.id = project_images.project_id 
          WHERE projects.inHallOfFame = 1";

// Add conditions based on filter parameters
if ($field !== 'all' || $year !== 'all') {
    $query .= " AND (";

    if ($field !== 'all') {
        $query .= " field = '$field'";
    }

    if ($field !== 'all' && $year !== 'all') {
        $query .= " OR";
    }

    if ($year !== 'all') {
        // Make sure the year format matches what's stored in the database
        $query .= " projectYear = '$year'";
    }

    $query .= ")";
}

// Sorting
if ($sort === 'most-liked') {
    $query .= " ORDER BY projects.score DESC";
} elseif ($sort === 'recent') {
    $query .= " ORDER BY projects.id DESC";
}

// Execute query
$result = mysqli_query($connProject, $query);

// Generate HTML for filtered project list
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="project">';
        // Display project information
        echo '<div class="project-info">';
        // Display project image
        if (!empty($row['image_path'])) {
            echo '<img src="' . $row['image_path'] . '" alt="' . $row['projectName'] . ' Image">';
        } else {
            echo '<img src="default_image.png" alt="Default Image">';
        }
        // Display project name and summary
        echo '<h2>' . $row['projectName'] . '</h2>';
        echo '<p>' . $row['projectSummary'] . '</p>';
        echo '</div>';
        // Project actions
        echo '<div class="project-actions">';
        // Link "View Project" button to the project details page
        echo '<a href="projects.php?id=' . $row['id'] . '"><button class="view-project"><i class="fa-regular fa-folder-open"></i></button></a>';
        // Show medal button and score
        echo '<button class="medal-icon" data-project-id="' . $row['id'] . '"><i class="fa-solid fa-medal"></i> <span class="score">' . $row['score'] . '</span></button>';
        // Add the contact icon button with preloaded mail to
        if (!empty($row['authorEmail'])) {
            echo '<a href="mailto:' . $row['authorEmail'] . '?subject=Regarding%20Project%20' . $row['id'] . '"><button class="contact-icon"><i class="fa-solid fa-address-book"></i></button></a>';
        } else {
            echo '<button class="contact-icon" disabled><i class="fa-solid fa-address-book"></i></button>';
        }
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "<p>No projects found.</p>";
}
?>