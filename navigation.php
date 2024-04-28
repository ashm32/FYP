<?php
session_start();

// Function to determine the user type (stident/lecturer)
function getUserType() {
    if (isset($_SESSION['user_type'])) {
        return $_SESSION['user_type'];
    } else {
        return 'guest'; // Default to guest if user type is not set
    }
}
?>

<!-- Navbar -->
<nav>
    <div class="logo">Aston University’s Hall of Fame</div>
    <ul>
        <li class="<?php echo ($_SERVER['PHP_SELF'] == '/index.html') ? 'active' : ''; ?>"><a href="index.html">Home</a></li>
        <?php
        $userType = getUserType();
        if ($userType == 'student' || $userType == 'lecturer') {
            // Display dashboard link based on user type
            echo '<li class="' . (($userType == 'student' && $_SERVER['PHP_SELF'] == '/student_dash.php') || ($userType == 'lecturer' && $_SERVER['PHP_SELF'] == '/lecturer_dash.php') ? 'active' : '') . '"><a href="' . $userType . '_dash.php">' . ucfirst($userType) . ' Dashboard</a></li>';
            echo '<li><a href="logout.php">Logout</a></li>';
        } else {
            // Display login link if user is not logged in
            echo '<li class="' . ($_SERVER['PHP_SELF'] == '/login.php' ? 'active' : '') . '"><a href="login.php">Login</a></li>';
        }
        ?>
    </ul>
</nav>
