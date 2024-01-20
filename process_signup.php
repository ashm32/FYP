<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include '/Applications/MAMP/htdocs/db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

    // Insert user data into the 'users' table
    $query = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
    
    // Use a try-catch block to handle any potential exceptions
    try {
        $connAuthentication = connectToDatabase("user_authentication"); // Connect to the correct database
        $stmt = $connAuthentication->prepare($query); // Use $connAuthentication instead of $connProject

        if ($stmt) {
            $stmt->bind_param("ssss", $firstName, $lastName, $email, $password);

            // Check if the insertion was successful
            if ($stmt->execute()) {
                // Registration successful
                $response = array(
                    'status' => 'success',
                    'message' => 'Registration successful!',
                );

                // Redirect based on user role
                $userId = $stmt->insert_id; // Assuming user_id is auto-incremented
                $redirectUrl = determineRedirectUrl($userId);
                header("Location: $redirectUrl");
                exit(); // Ensure script stops executing after redirection
            } else {
                // Registration failed
                $response = array(
                    'status' => 'error',
                    'message' => 'Registration failed. Please try again.',
                );
            }

            // Close the database statement
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare the SQL statement.");
        }
    } catch (Exception $e) {
        // Handle exceptions, log the error, or display an appropriate message
        $response = array(
            'status' => 'error',
            'message' => 'An error occurred. Please try again later.',
        );
    }

    // No need to close the database connection here

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request
    http_response_code(400);
    echo "Invalid request.";
}

// Function to determine the appropriate dashboard based on user role
function determineRedirectUrl($userId) {
    // Implement your logic here to determine whether the user is a student or lecturer
    // For example, you might check a 'user_role' field in the database

    // Replace the following lines with your actual logic
    $isStudent = true; // Replace this with your logic
    if ($isStudent) {
        return 'student_dash.html';
    } else {
        return 'lecturers_dash.html';
    }
}
?>
