<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>
    <!-- Navbar -->
<nav>
    <div class="logo">Aston University’s Hall of Fame</div>
    <ul>
        <li class="active"><a href="index.html">Home</a></li>
        <?php
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
                echo '<li><a href="lecturers_dash.html">Lecturer Dashboard</a></li>';
            } else {
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
                            echo '<li><a href="student_dash.html">Student Dashboard</a></li>';
                        } else {
                            // Invalid input, display error message on the webpage and clear the form
                            header('Location: login.php?error=invalid_credentials');
                            exit();
                        }
                    } else {
                        // Handle failed prepared statement and redirect
                        http_response_code(500);  // Internal Server Error
                        echo 'Database error. Please try again later.';
                    }
                } catch (Exception $e) {
                    // Handle exceptions, log the error, or display an appropriate message and redirect
                    http_response_code(500);  
                    echo 'An unexpected error occurred. Please try again later.';
                }
            }
        } else {
            // Displays login link if the form is not submitted
            echo '<li><a href="login.php">Login</a></li>';
        }
        ?>
    </ul>
</nav>

    <div class="login-container">

        <form id="loginForm" action="process_login.php" method="POST" class="login-form">
            <h2>Welcome Back</h2>
            <h3>Login:</h3>
            <div class="text-box-div">
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="text-box-div">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
            <?php
        // Check for error parameter in the URL
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            // Display the corresponding error message
            if ($error === 'invalid_credentials') {
                echo '<p class="error"> Invalid Login Details </p>';
            } elseif ($error === 'database_error') {
                echo '<p class="error"> Invalid Login Details </p>';
            } elseif ($error === 'exception_error') {
                echo '<p class="error"> Invalid Login Details </p>';
            }
        }
        ?>
            <div class="signup">
                <h2>Don't have an account?</h2> 
                <h3><a href="signup.html">Sign Up</a></h3>
            </div>
        </form>

    
    </div>

    <script defer src="script.js"></script>
    <script defer src="ajaxScript.js"></script>
</body>
</html>
