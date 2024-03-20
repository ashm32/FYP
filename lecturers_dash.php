<?php
session_start();
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

    <!-- Project List -->
    <section class="project-list">
        <h2>Review Projects Submitted</h2>
        <!-- Filter Section -->
        <section class="filter">
            <label for="field">Filter by Field:</label>
            <select id="field">
                <option value="all">All Fields</option>
                <option value="field1">Computer Science</option>
                <option value="field2">Engineering</option>
                <option value="field2">Business</option>
                <option value="field2">Cyber Security</option>
                <option value="field2">IT</option>
            </select>

            <label for="year">Filter by Year:</label>
            <select id="year">
                <option value="all">All Years</option>
                <option value="2024">2022</option>
                <option value="2023">2022</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
            </select>

            <label for="sort">Sort by:</label>
            <select id="sort">
                <option value="most-liked">Most Liked</option>
                <option value="recent">Recent</option>
            </select>

            <button id="filter-button">Filter</button>
        </section>

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
</body>
</html>
