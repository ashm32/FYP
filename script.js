// Function to reset previous error messages
function resetErrors(form) {
    const errorMessages = form.querySelectorAll('.error');
    errorMessages.forEach((errorMsg) => {
        errorMsg.remove();
    });
}

// Function to display an error message
function showError(form, message) {
    const errorElement = document.createElement('p');
    errorElement.className = 'error';
    errorElement.textContent = message;
    form.appendChild(errorElement);
}

// Function to check if there are any errors
function hasErrors(form) {
    return form.querySelectorAll('.error').length > 0;
}

// Function to validate signup form
function validateForm(event) {
    const form = event.target;
    event.preventDefault();
    resetErrors(form);

    const firstName = form.querySelector('input[name="firstName"]').value;
    const lastName = form.querySelector('input[name="lastName"]').value;
    const email = form.querySelector('input[name="email"]').value;
    const password = form.querySelector('input[name="password"]').value;

    if (!isValidName(firstName)) {
        showError(form, 'First name is invalid');
    }

    if (!isValidName(lastName)) {
        showError(form, 'Last name is invalid');
    }

    if (!isValidEmail(email)) {
        showError(form, 'Email is invalid');
    }

    if (!isValidPassword(password)) {
        showError(form, 'Password is invalid');
    }

    if (!hasErrors(form)) {
        form.submit();
    }
}

function toggleAuthorDetails() {
    const authorDetailsForm = document.getElementById('authorDetailsForm');
    const includeAuthorDetails = document.getElementById('includeAuthorDetails');

    // Toggle the display of the author details form depending on checkbox
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

        // Validate other author details 
        if (!authorFirstName || !authorLastName) {
            alert('Please fill in all author details.');
            return;
        }
    }

    // If all fields are valid, submit the project
    alert('Project submitted successfully!');

    // Clear the form fields
    clearForm(form);
}

// Clear the form after submission
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

document.addEventListener('DOMContentLoaded', function () {
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
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', validateForm);
    }

    // Event listeners
    const includeAuthorDetailsCheckbox = document.getElementById('includeAuthorDetails');
    if (includeAuthorDetailsCheckbox) {
        includeAuthorDetailsCheckbox.addEventListener('change', toggleAuthorDetails);
    }

    // project_page needs editing
    document.addEventListener('DOMContentLoaded', function () {
        // Fetch project details from project_submission database 
        const projectDetails = {
            title: "Project Title",
            description: "Project Description",
            videoSource: "video.mp4",
            images: ["image1.jpg", "image2.jpg"],
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


});
