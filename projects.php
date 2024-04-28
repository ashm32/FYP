<?php
// Include the database connection file
include 'db_connection.php';

// Check if the database connection is successful
if (!$connProject) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if project ID is provided in the URL
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    // Retrieve project details from the database based on the provided project ID
    $query = "SELECT * FROM projects WHERE id = $project_id";
    $result = mysqli_query($connProject, $query);

    // Check if project exists
    if (mysqli_num_rows($result) == 1) {
        $project = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?php echo $project['projectName']; ?></title>
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

    <div class="projectsContainer">
        <div class="section">
            <h1>Title: <?php echo $project['projectName']; ?></h1>
            <h2>Project Summary:</h2>
            <p><?php echo $project['projectSummary']; ?></p>
            <h2>Project Description:</h2>
            <p><?php echo $project['projectDetails']; ?></p>
        </div>
        <div class="section">
            <h2>Project Walkthrough :</h2>
            <?php
            // Split the file paths by comma to get an array of file paths
            $videoPaths = explode(',', $project['videoUpload']);
            foreach ($videoPaths as $videoPath) {
            ?>
                <div class="video-container">
                    <video controls>
                        <source src="<?php echo $videoPath; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="section">
            <h2>Project Highlights:</h2>
            <div class="slideshow-container">
                <?php
                // Retrieve images for the current project
                $imageQuery = "SELECT * FROM project_images WHERE project_id = $project_id";
                $imageResult = mysqli_query($connProject, $imageQuery);

                // Check if there are images to display
                if ($imageResult && mysqli_num_rows($imageResult) > 0) {
                    $index = 0; // Initialize index for the slides
                    while ($image = mysqli_fetch_assoc($imageResult)) {
                ?>
                        <div class="mySlides fade">
                            <img src="<?php echo $image['image_path']; ?>" alt="Project Image">
                        </div>
                <?php
                        $index++;
                    }
                } else {
                    echo "<p>No images uploaded for this project.</p>";
                }
                ?>
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
            <br>
            <div style="text-align:center">
                <?php
                // Add dots for each slide
                for ($i = 0; $i < $index; $i++) {
                ?>
                    <span class="dot" onclick="currentSlide(<?php echo $i + 1; ?>)"></span>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="section note">
            <h3>Project Field:</h3>
            <p><?php echo $project['projectField']; ?></p>
            <h3>Year of Submission:</h3>
            <p><?php echo $project['projectYear']; ?></p>
        </div>
        <?php if ($project['authorFirstName'] && $project['authorLastName'] && $project['authorEmail']) { ?>
            <div class="section note">
                <h2>Contact Details:</h2>
                <h3>Project Author:</h3>
                <p><?php echo $project['authorFirstName'] . ' ' . $project['authorLastName']; ?></p>
                <h3>Email Address:</h3>
                <p><?php echo $project['authorEmail']; ?></p>
            </div>
        <?php } ?>
    </div>

    <script>
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("dot");
            if (n > slides.length) { slideIndex = 1; }
            if (n < 1) { slideIndex = slides.length; }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
        }
    </script>
</body>

</html>

<?php
    } else {
        echo "Project not found.";
    }
} else {
    echo "Invalid project ID.";
}
?>
