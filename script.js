// Login Signup
document.addEventListener('DOMContentLoaded', function () {
    // Form element
    const signupForm = document.getElementById('signupForm');

    // Validation function
    function validateForm(event) {
        // Prevent the form from submitting
        event.preventDefault();

        // Reset previous error messages
        resetErrors();

        // Get form inputs
        const firstName = signupForm.querySelector('input[name="firstName"]').value;
        const lastName = signupForm.querySelector('input[name="lastName"]').value;
        const email = signupForm.querySelector('input[name="email"]').value;
        const password = signupForm.querySelector('input[name="password"]').value;

        // Validate inputs
        if (!isValidName(firstName)) {
            showError('First name is invalid');
        }

        if (!isValidName(lastName)) {
            showError('Last name is invalid');
        }

        if (!isValidEmail(email)) {
            showError('Email is invalid');
        }

        if (!isValidPassword(password)) {
            showError('Password is invalid');
        }

        // If there are no errors, submit the form
        if (!hasErrors()) {
            signupForm.submit();
        }
    }

    // Event listener for form submission
    signupForm.addEventListener('submit', validateForm);

    // Function to reset previous error messages
    function resetErrors() {
        const errorMessages = signupForm.querySelectorAll('.error');
        errorMessages.forEach((errorMsg) => {
            errorMsg.remove();
        });
    }

    // Function to display an error message
    function showError(message) {
        const errorElement = document.createElement('p');
        errorElement.className = 'error';
        errorElement.textContent = message;
        signupForm.appendChild(errorElement);
    }

    // Function to check if there are any errors
    function hasErrors() {
        return signupForm.querySelectorAll('.error').length > 0;
    }

    // Function to validate name
    function isValidName(name) {
        // Add your custom validation logic for names here
        return /^[a-zA-Z]+$/.test(name);
    }

    // Function to validate email
    function isValidEmail(email) {
        // Add your custom validation logic for email here
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Function to validate password
    function isValidPassword(password) {
        // Add your custom validation logic for password here
        // For example, checking if it's at least 8 characters long and includes 1 letter, 1 number, and 1 special character
        const regex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return regex.test(password);
    }
});

// Student Dashboard
function openSubmitProjectForm() {
    // Display the project submission form
    document.getElementById('projectForm').style.display = 'block';
}

function validateProjectForm() {
    const projectTitle = document.getElementById('projectTitle').value;
    const projectDescription = document.getElementById('projectDescription').value;
    const projectArea = document.getElementById('projectArea').value;
    const submissionDate = document.getElementById('submissionDate').value;
}

// Toggle author details visibility
function toggleAuthorDetails() {
    const authorDetailsForm = document.getElementById('authorDetailsForm');
    const includeAuthorDetails = document.getElementById('includeAuthorDetails');

    // Toggle the display of the author details form based on checkbox state
    authorDetailsForm.style.display = includeAuthorDetails.checked ? 'block' : 'none';
}

// Main Submission Form Validation
function validateMainSubmission() {
    const projectName = document.getElementById('projectName').value;
    const projectSummary = document.getElementById('projectSummary').value;
    const projectDetails = document.getElementById('projectDetails').value;
    const projectField = document.getElementById('projectField').value;
    const projectYear = document.getElementById('projectYear').value;
    const videoUpload = document.getElementById('videoUpload').value;
    const imageUpload = document.getElementById('imageUpload').value;
    const includeAuthorDetails = document.getElementById('includeAuthorDetails').checked;

    // Validation for required fields
    if (!projectName || !projectSummary || !projectDetails || !projectField || !projectYear || !videoUpload || !imageUpload) {
        alert('Please fill in all required fields.');
        return;
    }

    // Validation for email if author details are included
    if (includeAuthorDetails) {
        const authorFirstName = document.getElementById('authorFirstName').value;
        const authorLastName = document.getElementById('authorLastName').value;
        const authorEmail = document.getElementById('authorEmail').value;

        // Validate email using a regular expression
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(authorEmail)) {
            alert('Invalid author email address');
            return;
        }

        // Validate other author details as needed
        if (!authorFirstName || !authorLastName) {
            alert('Please fill in all author details.');
            return;
        }
    }

    // If all fields are valid, you can proceed with submitting the project
    alert('Project submitted successfully!');

    // Clear the form fields
    clearForm();
}

// Clear form after submission
function clearForm() {
    const formElements = document.getElementById('mainSubmissionForm').elements;

    for (let i = 0; i < formElements.length; i++) {
        if (['input', 'textarea', 'select'].includes(formElements[i].tagName.toLowerCase())) {
            formElements[i].value = '';
        }
    }

    document.getElementById('includeAuthorDetails').checked = false;
    document.getElementById('authorDetailsForm').style.display = 'none';
}

// project_page

document.addEventListener('DOMContentLoaded', function () {
    // Fetch project details from your database or wherever they are stored
    // Replace the following with your actual data retrieval mechanism

    const projectDetails = {
        title: "Project Title",
        description: "Project Description",
        videoSource: "path/to/your/video.mp4",
        images: ["path/to/image1.jpg", "path/to/image2.jpg"],
        studentName: "Student Name",
        studentEmail: "student@example.com"
    };

    // Populate placeholders with project details
    document.getElementById('projectTitle').innerText = projectDetails.title;
    document.getElementById('projectDescription').innerText = projectDetails.description;

    // Set video source
    document.getElementById('videoDemo').src = projectDetails.videoSource;

    // Populate image gallery
    const imageGallery = document.getElementById('imageGallery');
    projectDetails.images.forEach(image => {
        const imgElement = document.createElement('img');
        imgElement.src = image;
        imageGallery.appendChild(imgElement);
    });

    // Populate student contact info
    document.getElementById('studentName').innerText = `Student: ${projectDetails.studentName}`;
    document.getElementById('studentEmail').innerText = `Email: ${projectDetails.studentEmail}`;
});

