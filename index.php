<?php
// Database connection
include 'db_connection.php';

// Define the limit for SQL based on the current page and projects per page
$projects_per_page_options = array(9, 15, 24);
$projects_per_page = isset($_GET['per_page']) && in_array($_GET['per_page'], $projects_per_page_options) ? $_GET['per_page'] : $projects_per_page_options[0];
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $projects_per_page;

// Define filter parameters
$field = isset($_GET['field']) ? $_GET['field'] : 'all';
$year = isset($_GET['year']) ? $_GET['year'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent';
$openToWork = isset($_GET['openToWork']) ? 1 : 0; // Check if "Open to Work" filter is ticked

// Build the WHERE clause based on filter parameters
$where_clause = "WHERE projects.inHallOfFame = 1"; 

// Add condition for "Open to Work" filter
if ($openToWork == 1) {
    $where_clause .= " AND open_to_work = 1";
}

if ($field !== 'all') {
    $where_clause .= " AND projectField = '$field'";
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

$query = "SELECT DISTINCT projects.id, projects.projectName, projects.projectSummary, projects.authorEmail, 
          MAX(project_images.image_path) AS image_path, projects.score 
          FROM projects
          LEFT JOIN project_images ON projects.id = project_images.project_id 
          $where_clause
          GROUP BY projects.id
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
   <title>Your Website</title>
</head>
<body>
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

<section class="hero">
   <h1>Explore Aston's top projects</h1>
</section>

<!-- Filter Section -->
<section class="filter">
    <form id="filter-form">
        <label for="field">Filter by Field:</label>
        <select id="field" name="field">
        <option value="" disabled selected>Select Project Field</option>
        <option value="all">All Fields</option>
            <option value="AI">Artifical Intelligence</option>
            <option value="CS">Cyber Security</option>
            <option value="HCI">Human Computing Interactions</option>
            <option value="ML">Machine Learning</option>
            <option value="SE">Software Engineering</option>
            <option value="UCD">User Centered Design</option>
            <option value="WD">Web Development</option>
        </select>
        

        <label for="year">Filter by Year:</label>
        <select id="year" name="year">
            <option value="all">All Years</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
            <option value="2022">2022</option>
            <option value="2021">2021</option>
        </select>
        <label>
    
        <label for="sort">Sort by Most:</label>
        <select id="sort" name="sort">
            <option value="most-liked">Liked</option>
            <option value="recent">Recent</option>
        </select>

        <label for="per_page">Projects per Page:</label>
        <select id="per_page" name="per_page">
            <?php foreach ($projects_per_page_options as $option) { ?>
                <option value="<?php echo $option; ?>" <?php if ($projects_per_page == $option) echo 'selected'; ?>><?php echo $option; ?></option>
            <?php } ?>
        </select>
        <input type="checkbox" id="openToWork" name="openToWork"> Open to Work

        <button type="submit" id="filter-button">Filter</button>
    </form>
</section>
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
               // Show like button and score
               echo '<button class="heart-icon" data-project-id="' . $row['id'] . '"><i class="fa-solid fa-heart"></i> <span class="score">' . $row['score'] . '</span></button>';
               // Add the contact icon button with preloaded mail to
               if (!empty($row['authorEmail'])) {
                echo '<a href="mailto:' . $row['authorEmail'] . '?subject=Regarding%20Project%20' . $row['projectName'] . '"><button class="contact-icon"><i class="fa-solid fa-address-book"></i></button></a>';
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

<section class="pagination-section">
<?php
// Display previous arrow if not on the first page
if ($page > 1) {
    echo '<a href="index.php?page='.($page - 1).'&per_page='.$projects_per_page.'">&lt;</a>';
}

// Display current page number
echo '<span class="current-page">' . $page . '</span>';

// Display next arrow if not on the last page
if ($page < $total_pages) {
    echo '<a href="index.php?page='.($page + 1).'&per_page='.$projects_per_page.'">&gt;</a>';
}
?>
</section>


<script src="https://kit.fontawesome.com/188d621110.js" crossorigin="anonymous"></script>
<script>
 // Add event listener to the filter form submission
 document.getElementById('filter-form').addEventListener('submit', function(event) {
     event.preventDefault(); // Prevent form submission
     var formData = new FormData(this); // Get form data
     var queryString = new URLSearchParams(formData).toString(); // Convert form data to query string
     window.location.href = 'index.php?' + queryString; // Redirect to index.php with filter parameters
 });

 // Add event listener to the heart button
 document.querySelectorAll('.heart-icon').forEach(function(button) {
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
</script>
</body>
</html>


