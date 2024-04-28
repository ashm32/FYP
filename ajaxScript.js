// // ajaxScript.js
// document.addEventListener('DOMContentLoaded', function () {
//     // Wait for the DOM to be fully loaded

//     // Form element
//     const loginForm = document.getElementById('loginForm');
//     console.log('loginForm:', loginForm);

//     if (loginForm) {
//         // Event listener for form submission
//         loginForm.addEventListener('submit', function (event) {
//             // Prevent the form from submitting
//             event.preventDefault();

//             // Validate the form
//             if (validateLoginForm()) {
//                 // If validation is successful, submit the form using AJAX
//                 submitLoginForm();
//             }
//         });
//     }

//     // Function to validate the login form
//     function validateLoginForm() {
//         const email = loginForm.querySelector('#email').value;
//         const password = loginForm.querySelector('#password').value;

//         if (!email || !password) {
//             alert('Email and password cannot be empty');
//             return false;
//         }

//         return true; // Return true if validation is successful
//     }

//     // Function to submit the login form
//     function submitLoginForm() {
//         // Fetch API used to send form data to the server
//         const formData = new FormData(loginForm);
//         fetch('process_login.php', {
//             method: 'POST',
//             body: formData
//         })
//             .then(response => {
//                 if (response.ok) {
//                     // Handle success (redirect or other actions)
//                     console.log('Login successful!');
//                     window.location.href = response.url;
//                 } else {
//                     // Handle error (display error message or other actions)
//                     console.error('Login failed');
//                 }
//             })
//             .catch(error => {
//                 console.error('An unexpected error occurred', error);
//             });
//     }
// });