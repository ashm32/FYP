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

    // Validation function for sign up form
    function validateSignUpForm(event) {
        // Prevent the form from submitting
        event.preventDefault();

        // Get the form
        const form = event.target;

        // Reset previous error messages
        resetErrors(form);

        // Get form inputs
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
            showError(form, 'Password is invalid - Include a letter, number and special character');
        }

        // If there are no errors, submit the form
        if (!hasErrors(form)) {
            form.submit();
        }
    }

    // Validation function for main submission form
    function validateMainSubmission(event) {
        event.preventDefault();
        const form = event.target;

        const projectName = form.querySelector('#projectName').value;
        const projectSummary = form.querySelector('#projectSummary').value;
        const projectDetails = form.querySelector('#projectDetails').value;
        const projectField = form.querySelector('#projectField').value;
        const projectYear = form.querySelector('#projectYear').value;
        const videoUpload = form.querySelector('#videoUpload').value;
        const imageUpload = form.querySelector('#imageUpload').value;

        if (!projectName || !projectSummary || !projectDetails || !projectField || !projectYear || !videoUpload || !imageUpload) {
            alert('Please fill in all required fields.');
            return;
        }

        const includeAuthorDetails = form.querySelector('#includeAuthorDetails').checked;
        if (includeAuthorDetails) {
            const authorFirstName = form.querySelector('#authorFirstName').value;
            const authorLastName = form.querySelector('#authorLastName').value;
            const authorEmail = form.querySelector('#authorEmail').value;

            if (!authorFirstName || !authorLastName || !authorEmail) {
                alert('Please fill in all author details.');
                return;
            }
        }

        alert('Project submitted successfully!');
        clearForm(form);
    }

    // Function to clear form after submission
    function clearForm(form) {
        form.reset();
        form.querySelector('#includeAuthorDetails').checked = false;
        form.querySelector('#authorDetailsForm').style.display = 'none';
    }

    // Event listener for sign up form submission
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', validateSignUpForm);
    }

    // Function to toggle author details visibility
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

    // Event listener for main submission form submission
    const mainSubmissionForm = document.getElementById('mainSubmissionForm');
    if (mainSubmissionForm) {
        mainSubmissionForm.addEventListener('submit', validateMainSubmission);
    }

    // Handling invalid password error (login.php)
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            const password = loginForm.querySelector('input[name="password"]').value;

            if (!isValidPassword(password)) {
                event.preventDefault(); // Prevent form submission
                showError(loginForm, 'Invalid password');
            }
        });
    }
});
