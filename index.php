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

           // Pages
           $results_per_page = 8; // 4 columns * 2 rows = 8 projects per page
           $page = isset($_GET['page']) ? $_GET['page'] : 1;
           $start_limit = ($page - 1) * $results_per_page;

           // Retrieve projects from the database with pagination
           $query = "SELECT id, projectName, projectSummary FROM projects WHERE inHallOfFame = 1 LIMIT $start_limit, $results_per_page";
           $result = mysqli_query($connProject, $query);

           // Check if there are projects to display
           if ($result && mysqli_num_rows($result) > 0) {
               $count = 0;
               while ($row = mysqli_fetch_assoc($result)) {
                   // Display each project
                   if ($count % 4 == 0) {
                       echo '<div class="row">'; // Start a new row for every 4 projects
                   }
                   echo '<div class="project">';
                   echo '<div class="project-info">';
                   echo '<img src="img eg.png" alt="' . $row['projectName'] . ' Image">';
                   echo '<h2>' . $row['projectName'] . '</h2>';
                   echo '<p>' . $row['projectSummary'] . '</p>';
                   echo '</div>';
                   echo '<div class="project-actions">';
                   echo '<button class="view-project"><i class="fa-regular fa-folder-open"></i></button>';
                   echo '<button class="medal-icon"><i class="fa-solid fa-medal"></i></button>';
                   echo '<button class="contact-icon"><i class="fa-solid fa-address-book"></i></button>';
                   echo '</div>';
                   echo '</div>';
                   $count++;
                   // Close the row after every 4 projects
                   if ($count % 4 == 0) {
                       echo '</div>';
                   }
               }
               // Close the row if the total projects are not a multiple of 4
               if ($count % 4 != 0) {
                   echo '</div>';
               }
           } else {
               // No projects found
               echo "<p>No projects found.</p>";
           }

           // Page links at the bottom right
           echo '<div class="pagination" style="position: fixed; bottom: 10px; right: 10px;">';
           $query = "SELECT COUNT(*) AS total FROM projects WHERE inHallOfFame = 1";
           $result = mysqli_query($connProject, $query);
           $row = mysqli_fetch_assoc($result);
           $total_pages = ceil($row['total'] / $results_per_page);
           for ($i = 1; $i <= $total_pages; $i++) {
               echo '<a href="index.php?page=' . $i . '">' . $i . '</a>';
           }
           echo '</div>';
           ?>
       </div>
   </section>

   <script src="https://kit.fontawesome.com/188d621110.js" crossorigin="anonymous"></script>
</body>
</html>
