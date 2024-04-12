<?php
// Database connection
include 'db_connection.php';

// Define the limit for SQL based on the current page
$results_per_page = 9;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Get total number of projects
$total_query = "SELECT COUNT(DISTINCT projects.id) AS total FROM projects LEFT JOIN project_images ON projects.id = project_images.project_id WHERE projects.inHallOfFame = 1";
$total_result = mysqli_query($connProject, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_projects = $total_row['total'];

// Calculate total pages
$total_pages = ceil($total_projects / $results_per_page);

// Get projects for the current page
$query = "SELECT DISTINCT projects.id, projects.projectName, projects.projectSummary, projects.authorEmail, project_images.image_path, projects.score 
          FROM projects 
          LEFT JOIN project_images ON projects.id = project_images.project_id 
          WHERE projects.inHallOfFame = 1 
          ORDER BY projects.id DESC
          LIMIT $results_per_page OFFSET $offset";

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
   <title>Your Website</title>
</head>
<body>
  <!-- Navbar -->
<nav>
    <div class="logo">Aston University’s Hall of Fame</div>
    <ul>
    <ul>
    <li class="active"><a href="index.php">Home</a></li>
        <?php
        session_start();
        if (isset($_SESSION['user_logged_in'])) {
            if ($_SESSION['user_role'] == 'student') {
                echo '<li><a href="student_dash.php">My Dashboard</a></li>';
            } elseif ($_SESSION['user_role'] == 'admin') {
                echo '<li><a href="lecturers_dash.php">My Dashboard</a></li>';
            }
            echo '<li><a href="logout.php">Logout</a></li>';
        } else {
            echo '<li><a href="login.php">Login</a></li>';
        }
        ?>
    </ul>        
</nav>


<!-- Hero Section -->
<section class="hero">
   <h1>Explore Aston's top projects</h1>
</section>
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
                <option value="2024">2024</option>
                <option value="2023">2023</option>
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
<!-- Project List Section -->
<section class="project-list-container">
   <div class="project-grid">
       <?php
       // Check if there are projects to display
       if ($result && mysqli_num_rows($result) > 0) {
           while ($row = mysqli_fetch_assoc($result)) {
               echo '<div class="project">';
               echo '<div class="project-info">';
               
               // Show project image
               if (!empty($row['image_path'])) {
                   echo '<img src="' . $row['image_path'] . '" alt="' . $row['projectName'] . ' Image">';
               } else {
                   echo '<img src="default_image.png" alt="Default Image">';
               }
               
               echo '<h2>' . $row['projectName'] . '</h2>';
               echo '<p>' . $row['projectSummary'] . '</p>';
               echo '</div>';
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
           // No projects found
           echo "<p>No projects found.</p>";
       }
       ?>
   </div>
</section>

<!-- Pagination Section -->
<section class="pagination-section">
   <?php
   // Display previous arrow if not on the first page
   if ($page > 1) {
       echo '<a href="index.php?page='.($page - 1).'">&lt;</a>';
   }

   // Display current page number
   echo '<span class="current-page">' . $page . '</span>';

   // Display next arrow if not on the last page
   if ($page < $total_pages) {
       echo '<a href="index.php?page='.($page + 1).'">&gt;</a>';
   }
   ?>
</section>


<script src="https://kit.fontawesome.com/188d621110.js" crossorigin="anonymous"></script>
<script>
    // Add event listener to the medal button
    document.querySelectorAll('.medal-icon').forEach(function(button) {
        button.addEventListener('click', function() {
            var projectId = this.getAttribute('data-project-id');
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'upvote.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Update the score in the UI
                            var scoreSpan = button.querySelector('.score');
                            scoreSpan.textContent = response.score;
                            // Disable the button to prevent multiple upvotes
                            button.disabled = true;
                        } else {
                            // Handle error
                            console.error('Failed to upvote: ' + response.message);
                        }
                    } else {
                        // Handle error
                        console.error('Failed to upvote: ' + xhr.statusText);
                    }
                }
            };
            xhr.send('projectId=' + projectId);
        });
    });

    // Add event listener to the filter button
    document.getElementById('filter-button').addEventListener('click', function() {
        var field = document.getElementById('field').value;
        var year = document.getElementById('year').value;
        var sort = document.getElementById('sort').value;

        // Check if the filter values have changed
        if (field !== sessionStorage.getItem('filterField') || year !== sessionStorage.getItem('filterYear') || sort !== sessionStorage.getItem('filterSort')) {
            // Store filter values in sessionStorage
            sessionStorage.setItem('filterField', field);
            sessionStorage.setItem('filterYear', year);
            sessionStorage.setItem('filterSort', sort);
        }
        
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'filter.php?field=' + field + '&year=' + year + '&sort=' + sort, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Update project list with filtered results
                    document.querySelector('.project-grid').innerHTML = xhr.responseText;
                } else {
                    // Handle error
                    console.error('Failed to fetch filtered projects: ' + xhr.statusText);
                }
            }
        };
        xhr.send();
    });

    // Apply stored filter values on page load
    window.onload = function() {
        var storedField = sessionStorage.getItem('filterField');
        var storedYear = sessionStorage.getItem('filterYear');
        var storedSort = sessionStorage.getItem('filterSort');

        if (storedField !== null) {
            document.getElementById('field').value = storedField;
        }

        if (storedYear !== null) {
            document.getElementById('year').value = storedYear;
        }

        if (storedSort !== null) {
            document.getElementById('sort').value = storedSort;
        }

        // Trigger filter button click to fetch filtered projects
        document.getElementById('filter-button').click();
    };
</script>
</body>
</html>
