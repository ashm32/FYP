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

    // Event listener for form submission
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', validateForm);
    }
});
