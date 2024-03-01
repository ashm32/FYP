<?php
// Include the database connection file
include 'db_connection.php';

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
    $lecturerPasswordHash = "lecturer_password"; 
    // Debugging: Print out the lecturer's hashed password
    echo "Stored Lecturer Password Hash: " . $lecturerPasswordHash;

    if ($email === $lecturerUsername && password_verify($password, $lecturerPasswordHash)) {
        // Redirect to lecturer dashboard
        header('Location: lecturers_dash.php');
        exit();
    } else {
        // Debugging: Print out the entered password and its hash
        $enteredPasswordHash = password_hash($password, PASSWORD_DEFAULT);
        echo "Entered Password: " . $password;
        echo "Entered Password Hash: " . $enteredPasswordHash;

        // Debugging: Print a message to indicate the comparison failed
        echo "Password verification failed.";
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
                // Set session variables
                session_start();
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_role'] = 'student'; // student = default, change to 'lecturer' if applicable
                // Redirect to appropriate dashboard
                if ($email === $lecturerUsername) {
                    $_SESSION['user_role'] = 'lecturer';
                    header('Location: lecturers_dash.php');
                    exit();
                } else {
                    header('Location: student_dash.php');
                    exit();
                }
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
