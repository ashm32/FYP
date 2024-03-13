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
           <li class="active"><a href="index.php">Home</a></li>
           <?php
           session_start();
           if (isset($_SESSION['user_logged_in'])) {
               if ($_SESSION['user_role'] == 'student') {
                   echo '<li><a href="student_dash.php">Student Dashboard</a></li>';
               } elseif ($_SESSION['user_role'] == 'lecturer') {
                   echo '<li><a href="lecturers_dash.php">Lecturer Dashboard</a></li>';
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
       <h1>Submit your work and engage with each other's content!</h1>
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
           $query = "SELECT id, projectName, projectSummary FROM projects WHERE inHallOfFame = 1 LIMIT $results_per_page OFFSET $offset";
           $result = mysqli_query($connProject, $query);

           // Check if there are projects to display
           if ($result && mysqli_num_rows($result) > 0) {
               while ($row = mysqli_fetch_assoc($result)) {
                   echo '<div class="project">';
                   echo '<div class="project-info">';
                   
                   // Retrieve 1 image for the project cover
                   $project_id = $row['id'];
                   $imageQuery = "SELECT * FROM project_images WHERE project_id = $project_id LIMIT 1";
                   $imageResult = mysqli_query($connProject, $imageQuery);

                   // Check if there is an image to display
                   if ($imageResult && mysqli_num_rows($imageResult) > 0) {
                       $image = mysqli_fetch_assoc($imageResult);
                       echo '<img src="' . $image['image_path'] . '" alt="' . $row['projectName'] . ' Image">';
                   } else {
                       echo '<img src="default_image.png" alt="Default Image">'; // Default image if no image found
                   }
                   
                   echo '<h2>' . $row['projectName'] . '</h2>';
                   echo '<p>' . $row['projectSummary'] . '</p>';
                   echo '</div>';
                   echo '<div class="project-actions">';
                   // Link "View Project" button to the project details page
                   echo '<a href="projects.php?id=' . $row['id'] . '"><button class="view-project"><i class="fa-regular fa-folder-open"></i></button></a>';
                   echo '<button class="medal-icon"><i class="fa-solid fa-medal"></i></button>';
                   echo '<button class="contact-icon"><i class="fa-solid fa-address-book"></i></button>';
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

       // Calculate the offset for SQL 
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
