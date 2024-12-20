s<?php
// logout.php
session_start();

// Destroy session
session_destroy();

// Redirect to the homepage
header("Location: login.php"); // Replace 'index.php' with your homepage file if it's different
exit();
?>
