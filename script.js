// Ensures DOM is loaded before executing
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOMContentLoaded event fired');

    // Function to reset previous error messages
    function resetErrors(form) {
        const errorMessages = form.querySelectorAll('.error');
        errorMessages.forEach((errorMsg) => {
            errorMsg.remove();
        });
    }

    // Function to display an error message
    function showError(form, message) {
        // Find or create a container for error messages
        let errorContainer = form.querySelector('.error-container');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.className = 'error-container';
            form.appendChild(errorContainer);
        }

        // Append error message to the container
        const errorElement = document.createElement('p');
        errorElement.className = 'error';
        errorElement.textContent = message;
        errorContainer.appendChild(errorElement);
    }

    // Function to check if there are any errors
    function hasErrors(form) {
        return form.querySelectorAll('.error').length > 0;
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

        // Get the form
        const form = event.target;

        // Reset previous error messages
        resetErrors(form);

        // Get form inputs (sign up)
        const firstName = form.querySelector('input[name="firstName"]').value;
        const lastName = form.querySelector('input[name="lastName"]').value;
        const email = form.querySelector('input[name="email"]').value;
        const password = form.querySelector('input[name="password"]').value;

        // Validate inputs
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

        // If there are no errors, submit the form
        if (!hasErrors(form)) {
            form.submit();
        }
    }

    // Author Details: Function to toggle author details visibility
    function toggleAuthorDetails() {
        const authorDetailsForm = document.getElementById('authorDetailsForm');
        const includeAuthorDetails = document.getElementById('includeAuthorDetails');

        // Toggle the display of the author details form based on checkbox state
        authorDetailsForm.style.display = includeAuthorDetails.checked ? 'block' : 'none';
    }

    // Event listener for includeAuthorDetails checkbox
    const includeAuthorDetailsCheckbox = document.getElementById('includeAuthorDetails');
    if (includeAuthorDetailsCheckbox) {
        includeAuthorDetailsCheckbox.addEventListener('change', toggleAuthorDetails);
    }

    // Main Submission Form Validation (project submission)
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

            // Validate email using standard format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(authorEmail)) {
                alert('Invalid author email address');
                return;
            }

            // Validate author details
            if (!authorFirstName || !authorLastName) {
                alert('Please fill in all author details.');
                return;
            }
        }

        // If all fields are valid, proceed with submitting the project
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
    // Tells browser not to perform the default form submission action
    if (mainSubmissionForm) {
        mainSubmissionForm.addEventListener('submit', function (event) {
            event.preventDefault();
            validateMainSubmission();
        });
    }
});
