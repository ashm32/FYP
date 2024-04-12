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
<!-- Project List Section -->
<section class="project-list-container">
   <div class="project-grid">
       <?php
       // Database connection
       include 'db_connection.php';

       // Define the limit for SQL based on the current page
       $results_per_page = 9;
       $page = isset($_GET['page']) ? $_GET['page'] : 1;
       $offset = ($page - 1) * $results_per_page;

       // Get projects from the database for the current page
       $query = "SELECT projects.id, projects.projectName, projects.projectSummary, projects.authorEmail, project_images.image_path FROM projects LEFT JOIN project_images ON projects.id = project_images.project_id WHERE projects.inHallOfFame = 1 LIMIT $results_per_page OFFSET $offset";
       $result = mysqli_query($connProject, $query);

       // Check if there are projects to display
       if ($result && mysqli_num_rows($result) > 0) {
           while ($row = mysqli_fetch_assoc($result)) {
               echo '<div class="project">';
               echo '<div class="project-info">';
               
               // SHow project image
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
               echo '<button class="medal-icon"><i class="fa-solid fa-medal"></i></button>';
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
   // Pagination
   $query = "SELECT COUNT(*) AS total FROM projects WHERE inHallOfFame = 1";
   $result = mysqli_query($connProject, $query);
   $row = mysqli_fetch_assoc($result);
   $total_projects = $row['total'];
   $projects_per_page = 9;
   $total_pages = ceil($total_projects / $projects_per_page);

   // Current page
   $page = isset($_GET['page']) ? $_GET['page'] : 1;

   // Validate current page value
   if ($page < 1 || $page > $total_pages) {
       $page = 1;
   }

   // Calculate sQL OFFSET
   $offset = ($page - 1) * $projects_per_page;

   // Gets projects for  current page
   $query = "SELECT id, projectName, projectSummary FROM projects WHERE inHallOfFame = 1 LIMIT $projects_per_page OFFSET $offset";
   $result = mysqli_query($connProject, $query);

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
</body>
</html>
