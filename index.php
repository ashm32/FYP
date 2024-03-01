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
    <title>Your Website</title>
</head>

<body>
    <!-- Navbar -->
    <nav>
        <div class="logo">Aston University’s Hall of Fame</div>
        <ul>
            <li class="active"><a href="index.php">Home</a></li>
            <?php
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

    <!-- Filter Section -->
    <section class="filter">
        <label for="field">Filter by Field:</label>
        <select id="field">
            <option value="all">All Fields</option>
            <option value="field1">Field 1</option>
            <option value="field2">Field 2</option>
            <!-- Add more field options as needed -->
        </select>

        <label for="year">Filter by Year:</label>
        <select id="year">
            <option value="all">All Years</option>
            <option value="2022">2022</option>
            <option value="2021">2021</option>
            <!-- Add more year options as needed -->
        </select>

        <label for="sort">Sort by:</label>
        <select id="sort">
            <option value="most-liked">Most Liked</option>
            <option value="recent">Recent</option>
        </select>
    </section>

    <!-- Project List Section -->
    <section class="project-list-container">
        <div class="project-list">
            <div class="project">
                <div class="project-info">
                    <img src="img eg.png" alt="Project 1 Image">
                    <h2>Project Title 1</h2>
                    <p>Brief description of Project 1.</p>
                </div>
                <div class="project-actions">
                    <button class="view-project"><i class="fa-regular fa-folder-open"></i></button>
                    <button class="medal-icon"><i class="fa-solid fa-medal"></i></button>
                    <button class="contact-icon"><i class="fa-solid fa-address-book"></i></button>
                </div>
            </div>
            <div class="project">
                <div class="project-info">
                    <img src="img eg.png" alt="Project 2 Image">
                    <h2>Project Title 2</h2>
                    <p>Brief description of Project 2.</p>
                </div>
                <div class="project-actions">
                    <button class="view-project"><i class="fa-regular fa-folder-open"></i></button>
                    <button class="medal-icon"><i class="fa-solid fa-medal"></i></button>
                    <button class="contact-icon"><i class="fa-solid fa-address-book"></i></button>
                </div>
            </div>
            <div class="project">
                <div class="project-info">
                    <img src="img eg.png" alt="Project 3 Image">
                    <h2>Project Title 3</h2>
                    <p>Brief description of Project 3.</p>
                </div>
                <div class="project-actions">
                    <button class="view-project"><i class="fa-regular fa-folder-open"></i></button>
                    <button class="medal-icon"><i class="fa-solid fa-medal"></i></button>
                    <button class="contact-icon"><i class="fa-solid fa-address-book"></i></button>
                </div>
            </div>
            <div class="project">
                <div class="project-info">
                    <img src="img eg.png" alt="Project 4 Image">
                    <h2>Project Title 4</h2>
                    <p>Brief description of Project 4.</p>
                </div>
                <div class="project-actions">
                    <button class="view-project"><i class="fa-regular fa-folder-open"></i></button>
                    <button class="medal-icon"><i class="fa-solid fa-medal"></i></button>
                    <button class="contact-icon"><i class="fa-solid fa-address-book"></i></button>
                </div>
            </div>
            <div class="project">
                <div class="project-info">
                    <img src="img eg.png" alt="Project 5 Image">
                    <h2>Project Title 5</h2>
                    <p>Brief description of Project 5.</p>
                </div>
                <div class="project-actions">
                    <button class="view-project"><i class="fa-regular fa-folder-open"></i></button>
                    <button class="medal-icon"><i class="fa-solid fa-medal"></i></button>
                    <button class="contact-icon"><i class="fa-solid fa-address-book"></i></button>
                </div>
            </div>
            
        </div>
    </section>

    <script defer src="script.js"></script>
    <script defer src="ajaxScript.js"></script>
    <script src="https://kit.fontawesome.com/188d621110.js" crossorigin="anonymous"></script>

    <?php
    // Check if success message is set
    if (isset($_SESSION['success_message'])) {
        echo "<script>alert('{$_SESSION['success_message']}');</script>";
        // Unset the session variable after displaying the message
        unset($_SESSION['success_message']);
    }
    ?>
</body>

</html>
