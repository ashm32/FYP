document.addEventListener('DOMContentLoaded', function () {
    // Wait for the DOM to be fully loaded

    // Form element
    const mainSubmissionForm = document.getElementById('mainSubmissionForm');
    console.log('mainSubmissionForm:', mainSubmissionForm);

    if (mainSubmissionForm) {
        // Event listener for form submission
        mainSubmissionForm.addEventListener('submit', function (event) {
            // Prevent the form from submitting
            event.preventDefault();

            // Validate the form
            if (validateMainSubmission()) {
                // If validation is successful, submit the form using AJAX
                submitForm();
            }
        });
    }
});
