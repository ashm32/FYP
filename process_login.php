<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve login data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if input matches lecturer credentials
    $lecturerUsername = "lecturer@example.com";  // lecturers have shared account
    $lecturerPassword = "adminpass"; 
    $lecturerPasswordHash = password_hash($lecturerPassword, PASSWORD_DEFAULT);

    // Debugging: Output the lecturer's hashed password
    echo "Lecturer Password Hash: " . $lecturerPasswordHash;

    // Check if the entered credentials match lecturer's credentials
    if ($email === $lecturerUsername && password_verify($password, $lecturerPasswordHash)) {
        // Redirect to lecturer dashboard
        session_start();
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_role'] = 'lecturer';
        header('Location: lecturers_dash.php');
        exit();
    } else {
        // Check if entered credentials match student's credentials
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
                    // Set session variables for student
                    session_start();
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_role'] = 'student';
                    // Redirect to student dashboard
                    header('Location: student_dash.php');
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
    }
} else {
    // Invalid request
    http_response_code(400); 
    echo "Invalid request.";
}
?>
