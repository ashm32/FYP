<?php
// Include the database connection file
include '/Applications/MAMP/htdocs/db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve login data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if entered credentials match lecturer credentials
    $lecturerUsername = "lecturer@example.com";  // Change this to the desired lecturer email later
    $lecturerPasswordHash = password_hash("lecturer_password", PASSWORD_DEFAULT);

    if ($email === $lecturerUsername && password_verify($password, $lecturerPasswordHash)) {
        // Redirect to lecturer dashboard
        header('Location: lecturers_dash.html');
        exit();
    }

    // Attempt to log in as a regular user
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
                // Redirect to student dashboard
                header('Location: student_dash.html');
                exit();
            } else {
                // Invalid credentials, handle appropriately (e.g., display error message)
                header('Location: login.html');
                exit();
            }
        } else {
            // Handle failed prepared statement
            header('Location: login.html');
            exit();
        }
    } catch (Exception $e) {
        // Handle exceptions, log the error, or display an appropriate message
        header('Location: login.html');
        exit();
    }
} else {
    // Invalid request
    http_response_code(400);
    echo "Invalid request.";
}
?>
