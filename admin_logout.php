<?php
session_start();

// Destroy the admin session
session_unset(); // Unsets all session variables
session_destroy(); // Destroys the session

// Redirect to the login page (or any page you'd like after logout)
header("Location: admin_login.php"); // Replace 'login.php' with the actual login page
exit();
?>