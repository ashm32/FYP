// Ensures dom is loaded before executing
document.addEventListener('DOMContentLoaded', function () { 
    console.log('DOMContentLoaded event fired');

    // Get the signupForm element
    const signupForm = document.getElementById('signupForm');

    // Check if signupForm is found
    if (!signupForm) {
        console.error('Signup form not found!');
        return;
    }

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
        return /^[a-zA-Z]+$/.test(name);
    }

    // Function to validate email
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Function to validate password
    function isValidPassword(password) {
        // Checking if it's at least 8 characters long and includes 1 letter, 1 number, and 1 special character
        const regex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return regex.test(password);
    }

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

    // Function to toggle author details visibility
    function toggleAuthorDetails() {
        const authorDetailsForm = document.getElementById('authorDetailsForm');
        const includeAuthorDetails = document.getElementById('includeAuthorDetails');

        // Toggle the display of the author details form based on checkbox state
        authorDetailsForm.style.display = includeAuthorDetails.checked ? 'block' : 'none';
    }

    // Main Submission Form Validation
    function validateMainSubmission() {
        const form = document.getElementById('mainSubmissionForm');

        const projectName = form.querySelector('#projectName').value;
        const projectSummary = form.querySelector('#projectSummary').value;
        const projectDetails = form.querySelector('#projectDetails').value;
        const projectField = form.querySelector('#projectField').value;
        const projectYear = form.querySelector('#projectYear').value;
        const videoUpload = form.querySelector('#videoUpload').value;
        const imageUpload = form.querySelector('#imageUpload').value;
        const includeAuthorDetails = form.querySelector('#includeAuthorDetails').checked;

        // Validation for required fields
        if (!projectName || !projectSummary || !projectDetails || !projectField || !projectYear || !videoUpload || !imageUpload) {
            alert('Please fill in all required fields.');
            return;
        }

        // Validation for email if author details are included
        if (includeAuthorDetails) {
            const authorFirstName = form.querySelector('#authorFirstName').value;
            const authorLastName = form.querySelector('#authorLastName').value;
            const authorEmail = form.querySelector('#authorEmail').value;

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
        clearForm(form);
    }

    // Function to clear form after submission
    function clearForm(form) {
        const formElements = form.elements;

        for (let i = 0; i < formElements.length; i++) {
            if (['input', 'textarea', 'select'].includes(formElements[i].tagName.toLowerCase())) {
                formElements[i].value = '';
            }
        }

        form.querySelector('#includeAuthorDetails').checked = false;
        form.querySelector('#authorDetailsForm').style.display = 'none';
    }

    // Main Submission Form Event Listener
    const mainSubmissionForm = document.getElementById('mainSubmissionForm');
    console.log('mainSubmissionForm:', mainSubmissionForm);

    if (mainSubmissionForm) {
        mainSubmissionForm.addEventListener('submit', function (event) {
            event.preventDefault();
            validateMainSubmission();
        });
    }

    // Signup Form Event Listener
    console.log('signupForm:', signupForm);
    if (signupForm) {
        signupForm.addEventListener('submit', validateForm);
    } else {
        console.error('Signup form not found!');
    }

    // Event listener for includeAuthorDetails checkbox
    const includeAuthorDetailsCheckbox = document.getElementById('includeAuthorDetails');
    if (includeAuthorDetailsCheckbox) {
        includeAuthorDetailsCheckbox.addEventListener('change', toggleAuthorDetails);
    }

    // Project page details 
    document.addEventListener('DOMContentLoaded', function () {
        // get project details from your database 

        const projectDetails = {
            title: 'Project Title',
            description: 'Project Description',
            videoSource: 'path/to/your/video.mp4',
            images: ['path/to/image1.jpg', 'path/to/image2.jpg'],
            studentName: 'Student Name',
            studentEmail: 'student@example.com'
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
});
