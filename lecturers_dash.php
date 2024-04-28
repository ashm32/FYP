<?php

session_start();

// Define filter parameters
$field = isset($_GET['field']) ? $_GET['field'] : 'all';
$year = isset($_GET['year']) ? $_GET['year'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent';

// Build the WHERE clause based on filter parameters
$where_clause = "WHERE projects.inHallOfFame = 0";
if ($field !== 'all') {
    $where_clause .= " AND field = '$field'";
}
if ($year !== 'all') {
    // Make sure the year format matches what's stored in the database
    $where_clause .= " AND projectYear = '$year'";
}

// Get total number of projects after applying filters
$total_query = "SELECT COUNT(DISTINCT projects.id) AS total FROM projects LEFT JOIN project_images ON projects.id = project_images.project_id $where_clause";
$total_result = mysqli_query($connProject, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_projects = $total_row['total'];

// Calculate total pages based on the total number of filtered projects and results per page
$total_pages = ceil($total_projects / $projects_per_page);

// Get projects for the current page with applied filters and sorting
$query = "SELECT DISTINCT projects.id, projects.projectName, projects.projectSummary, projects.authorEmail, project_images.image_path, projects.score 
          FROM projects 
          LEFT JOIN project_images ON projects.id = project_images.project_id 
          $where_clause 
          ORDER BY ";

// Determine the sorting order based on the selected option
if ($sort === 'most-liked') {
    $query .= "projects.score DESC";
} else {
    // Default to sorting by most recent (greatest ID to lowest)
    $query .= "projects.id DESC";
}

$query .= " LIMIT $projects_per_page OFFSET $offset";

$result = mysqli_query($connProject, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- FontAwesome CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Lecturer Dashboard</title>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="logo">Aston University’s Hall of Fame</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>        
    </nav>
    <!-- Filter Section -->
    <section class="filter">
            <form id="filter-form" action="lecturers_dash.php" method="GET">
                <label for="field">Filter by Field:</label>
                <select id="field" name="field">
                    <option value="all">All Fields</option>
                    <option value="field1">Computer Science</option>
                    <option value="field2">Engineering</option>
                    <option value="field3">Business</option>
                    <option value="field4">Cyber Security</option>
                    <option value="field5">IT</option>
                </select>

                <label for="year">Filter by Year:</label>
                <select id="year" name="year">
                    <option value="all">All Years</option>
                    <option value="2024">2022</option>
                    <option value="2023">2022</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                </select>

                <label for="sort">Sort by:</label>
                <select id="sort" name="sort">
                    <option value="most-liked">Most Liked</option>
                    <option value="recent">Recent</option>
                </select>

                <button type="submit" id="filter-button">Filter</button>
            </form>
        </section>
    <!-- Project List -->
    <section class="project-list">
        <h2>Review Projects Submitted</h2>
        <?php
        // Include the database connection file
        include 'db_connection.php';

        // Retrieve projects from the database where inHallOfFame = 0
        $query = "SELECT id, projectName, projectSummary FROM projects WHERE inHallOfFame = 0";
        $result = mysqli_query($connProject, $query);

        // Check if there are projects to display
        if ($result && mysqli_num_rows($result) > 0) {
            echo '<div class="project-table">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="project-row">';
                echo '<div class="project-cell">';
                echo "<h3>{$row['projectName']}</h3>";
                echo "<p>{$row['projectSummary']}</p>";
                echo "<div class='button-container'>";
                echo "<a href='projects.php?id={$row['id']}' class='button'> <i class='fa-regular fa-folder-open'></i></a>";
                echo "<a href='hallOfFame.php?project_id={$row['id']}' class='button'><i class='fa-solid fa-check'></i></a>";
                echo "</div>"; 
                echo "</div>"; 
                echo "</div>"; 
            }
            echo '</div>'; 
        } else {
            echo "<p>No projects found.</p>";
        }
        ?>
    </section>

    <script defer src="script.js"></script>
    <script defer src="ajaxScript.js"></script>
    <script>
        // Add event listener to the filter form submission
        document.getElementById('filter-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            var formData = new FormData(this); // Get form data
            var queryString = new URLSearchParams(formData).toString(); // Convert form data to query string
            window.location.href = 'lecturers_dash.php?' + queryString; // Redirect to lecturers_dash.php with filter parameters
        });
    </script>
</body>
</html>