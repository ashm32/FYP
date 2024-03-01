<!-- Lecturer Dashboard -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
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

    <!-- Lecturer Dashboard Section -->
    <section class="lecturer-dashboard">
        <h2>Lecturer Dashboard</h2>

        <!-- Submit Project Button -->
        <button onclick="openProjectsPage()">Submit Project</button>
    </section>

    <script>
        // Open Projects Page Function
        function openProjectsPage() {
            // Redirect to the projects page for lecturers
            window.location.href = "projects.html";
        }
    </script>
<script defer src="script.js"></script>
<script defer src="ajaxScript.js"></script>

</body>
</html>