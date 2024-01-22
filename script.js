<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Student Submission</title>
</head>

<body>
    <!-- Navbar -->
    <nav>
        <div class="logo">Your Logo</div>
        <ul>
            <li><a href="index.html">Home</a></li>
            <!-- Add more menu items -->
        </ul>
    </nav>

    <!-- Student Submission Section -->
    <section class="student-submission">
        <h2>Make a Submission</h2>

        <!-- Main Submission Form -->
        <form id="mainSubmissionForm" action="process_submission.php" method="POST" enctype="multipart/form-data" onsubmit="validateMainSubmission(); return false;">
            <label for="projectName">Project Name:</label>
            <input type="text" id="projectName" required placeholder="Enter the project name">

            <label for="projectSummary">Project Summary:</label>
            <textarea id="projectSummary" required placeholder="Enter a brief summary of the project"></textarea>

            <label for="projectDetails">Project Details:</label>
            <textarea id="projectDetails" required placeholder="Enter detailed information about the project"></textarea>
         
            <label for="projectField">Project Field:</label>
            <select id="projectField" required>
                <!-- Initial blank option -->
                <option value="" disabled selected>Select Project Field</option>
                <!-- Project field options -->
                <option value="field1">Field 1</option>
                <option value="field2">Field 2</option>
            </select>
            
            <label for="projectYear">Project Year:</label>
            <select id="projectYear" required>
                <!-- Initial blank option -->
                <option value="" disabled selected>Select Project Year</option>
                <!-- Project year options -->
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>

            <!-- File input for video -->
            <label for="videoUpload">Video Upload:</label>
            <input type="file" id="videoUpload" accept="video/*">

            <!-- File input for image -->
            <label for="imageUpload">Image Upload:</label>
            <input type="file" id="imageUpload" accept="image/*">

            <div class="checkbox-section">
                <input type="checkbox" id="includeAuthorDetails" onchange="toggleAuthorDetails()">
                <label for="includeAuthorDetails">Include Author's Details</label>
            </div>

            <!-- Author Details Form, hide by default -->
            <div class="author-details-form" id="authorDetailsForm" style="display: none;">
                <label for="authorFirstName">First Name:</label>
                <input type="text" id="authorFirstName" placeholder="Enter Your First Name">
                <br>
                <label for="authorLastName">Last Name:</label>
                <input type="text" id="authorLastName" placeholder="Enter Your Last Name">
                <br>
                <label for="authorEmail">Your Email:</label>
                <input type="email" id="authorEmail" placeholder="Enter The Email Address You'd Like To Be Contacted On">
            </div>

            <!-- Submit Button for Main Submission Form -->
            <button type="button" onclick="validateMainSubmission()">Submit Project</button>
        </form>
    </section>

    <script src="script.js"></script>
</body>
</html>
