document.addEventListener('DOMContentLoaded', function () {
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

    // Function to validate the main submission form
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

    // Main Submission Form Event Listener
    const mainSubmissionForm = document.getElementById('mainSubmissionForm');
    if (mainSubmissionForm) {
        mainSubmissionForm.addEventListener('submit', validateMainSubmission);
    }
});
