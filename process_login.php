<?php
// Include the database connection file
include '/Applications/MAMP/htdocs/db_connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialise error flag
$errorFlag = false;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve login data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if input matches lecturer credentials
    $lecturerUsername = "lecturer@example.com";  // lecturers have shared account
    $lecturerPasswordHash = password_hash("lecturer_password", PASSWORD_DEFAULT);

    if ($email === $lecturerUsername && password_verify($password, $lecturerPasswordHash)) {
        // Redirect to lecturer dashboard
        header('Location: lecturers_dash.html');
        exit();
    }

    // Checks if logged in as a regular user (student)
    $connAuthentication = connectToDatabase("user_authentication");
    $query = "SELECT * FROM users WHERE email = ?";

    try {
        $stmt = $connAuthentication->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                // Redirect to student dashboard page
                header('Location: student_dash.html');
                exit();
            } else {
                // Invalid input, display error message on the webpage and clear the form
                header('Location: login.html?error=invalid_credentials');
                exit();
            }
        } else {
            // Handle failed prepared statement and redirect
            http_response_code(500);  // Internal Server Error
            echo 'Database error. Please try again later.';
        }
    } catch (Exception $e) {
        // Handle exceptions, log the error, or display an appropriate message and redirect
        http_response_code(500);  // Internal Server Error
        echo 'An unexpected error occurred. Please try again later.';
    }
} else {
    // Invalid request
    http_response_code(400); 
    echo "Invalid request.";
}
?>
