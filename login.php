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
            <li><a href="index.php">Home</a></li>
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