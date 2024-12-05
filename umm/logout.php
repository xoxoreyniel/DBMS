<?php
session_start();
session_destroy();  // Destroy the session to log out

// Set a session message to notify the user
session_start();
$_SESSION['message'] = "You have been logged out successfully.";

// Redirect to index.php
header("Location: login.html");
exit;
?>
