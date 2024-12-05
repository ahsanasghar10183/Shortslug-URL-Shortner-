<?php
session_start(); // Start the session

// Destroy the session to log the user out
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page (or another page of your choice)
header("Location: login.php");
exit;
?>
