<?php
// Start the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Delete the session
session_destroy();

// Redirect to the login page after user has been logged out
header("Location: login.php");
exit;
?>
