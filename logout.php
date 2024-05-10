<?php
// Initialize the session
session_start();

// Check if the user is an admin
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === 1) {
    // Admin is logging out
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect admin to login page
    header("location: login.php");
    exit;
} else {
    // Regular user is logging out
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect regular user to index page
    header("location: index.php");
    exit;
}
?>
