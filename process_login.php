<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve login data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Include the database connection file
    include 'db_connection.php';

    // Check if entered credentials match admin's credentials
    $connAdmins = connectToDatabase("user_authentication");

    // Prepare and execute query to fetch admin with provided email and password
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $connAdmins->prepare($query);

    if ($stmt === false) {
        die('Failed to prepare the statement: ' . $connAdmins->error);
    }

    // Bind parameters
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows > 0) {
        // Admin found, start session and set session variables
        session_start();
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_role'] = 'admin';

        // Redirect to lecturers dashboard
        header('Location: lecturers_dash.php');
        exit();
    } else {
        // Check if entered credentials match student's credentials (hashed)
        $connStudents = connectToDatabase("user_authentication");
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $connStudents->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            if (password_verify($password, $student['password'])) {
                // Redirect to student dashboard
                session_start();
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_role'] = 'student';
                header('Location: student_dash.php');
                exit();
            }
        }

        // Invalid input, display error message on the webpage and clear the form
        header('Location: login.html?error=invalid_credentials');
        exit();
    }
} else {
    // Invalid request
    http_response_code(400);
    echo "Invalid request.";
}
?>
