<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Student Dashboard</title>
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

   <!-- Student Submission Section -->
   <section class="student-submission">
    <h2>Make a Submission</h2>

    <!-- Main Submission Form -->
    <!-- enctype for file inputs, included name attributes -->
    <form id="mainSubmissionForm" action="process_submission.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm();">
    
        <label for="projectName">Project Name:</label>
        <input type="text" id="projectName" name="projectName" required placeholder="Enter the project name">
    
        <label for="projectSummary">Project Summary:</label>
        <textarea id="projectSummary" name="projectSummary" required placeholder="Enter a brief summary of the project"></textarea>
    
        <label for="projectDetails">Project Details:</label>
        <textarea id="projectDetails" name="projectDetails" required placeholder="Enter detailed information about the project"></textarea>
     
        <label for="projectField">Project Field:</label>
        <select id="projectField" name="projectField" required>
            <option value="" disabled selected>Select Project Field</option>
            <option value="AI">Artifical Intelligence</option>
            <option value="CS">Cyber Security</option>
            <option value="HCI">Human Computing Interactions</option>
            <option value="ML">Machine Learning</option>
            <option value="SE">Software Engineering</option>
            <option value="UCD">User Centered Design</option>
            <option value="WD">Web Development</option>
        </select>
        
        <label for="projectYear">Project Year:</label>
        <select id="projectYear" name="projectYear" required>
            <option value="" disabled selected>Select Project Year</option>
            <option value="all">All Years</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
        </select>
    
        <!-- File input for video -->
        <label for="videoUpload">Video Upload:</label> 
        <input type="file" id="videoUpload" name="videoUpload" accept="video/*">
    
<!-- File input for image -->
<label for="imageUpload">Image Upload (Drag Images To Add Multiple):</label>
<input type="file" id="imageUpload" name="imageUpload[]" accept="image/*" multiple>

        <div class="checkbox-section">
            <input type="checkbox" id="OpenToWork" name="OpenToWork">
            <label for="OpenToWork">Open To Work</label>
        </div>
    
        <div class="checkbox-section">
            <input type="checkbox" id="includeAuthorDetails" name="includeAuthorDetails">
            <label for="includeAuthorDetails">Include Author's Details</label>
        </div>
    
        <!-- Author Details Form, hide by default -->
        <div class="author-details-form" id="authorDetailsForm" style="display: none;">
            <label for="authorFirstName">First Name:</label>
            <input type="text" id="authorFirstName" name="authorFirstName" placeholder="Enter Your First Name">
            <br>
            <label for="authorLastName">Last Name:</label>
            <input type="text" id="authorLastName" name="authorLastName" placeholder="Enter Your Last Name">
            <br>
            <label for="authorEmail">Your Email:</label>
            <input type="email" id="authorEmail" name="authorEmail" placeholder="Enter Your Email">
        </div>
        <!-- Submit Button for Main Submission Form -->
        <button type="submit">Submit Project</button>
    </form>
    
</section>

    <script defer src="script.js"></script>
    <script defer src="ajaxScript.js"></script>
    <script>
function validateForm() {
    var projectName = document.getElementById("projectName").value.trim();
    var projectSummary = document.getElementById("projectSummary").value.trim();
    var projectDetails = document.getElementById("projectDetails").value.trim();
    var projectField = document.getElementById("projectField").value;
    var projectYear = document.getElementById("projectYear").value;
    var videoUpload = document.getElementById("videoUpload").value.trim();
    var imageUpload = document.getElementById("imageUpload").value.trim();

    // Check if any of the required fields are empty or contain only spaces
    if (projectName === "" || projectSummary === "" || projectDetails === "" ||
        projectField === "" || projectYear === "" || videoUpload === "" || imageUpload === "") {
        alert("Please fill in all required fields.");
        return false; // Prevent form submission
    }

    // Check if including author details is selected
    var includeAuthorDetails = document.getElementById("includeAuthorDetails").checked;
    if (includeAuthorDetails) {
        var authorFirstName = document.getElementById("authorFirstName").value.trim();
        var authorLastName = document.getElementById("authorLastName").value.trim();
        var authorEmail = document.getElementById("authorEmail").value.trim();

        // Check if any of the author details fields are empty or contain only spaces
        if (authorFirstName === "" || authorLastName === "" || authorEmail === "") {
            alert("Please fill in all author details.");
            return false; // Prevent form submission
        }
    }

    return true; // Allow form submission
}
</script>


</body>
</html>