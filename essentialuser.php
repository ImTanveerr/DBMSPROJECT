<?php

// Database connection (ensure $con is globally available)
//require_once('db_config.php');

// Reusable alert function for displaying messages
function alert($type, $message) {
    echo "<div class='alert alert-$type'>$message</div>";
}

// Redirect function for ease of navigation
function redirect($url) {
    header("Location: $url");
    exit(); // Ensures the script stops execution after the redirect
}

// Function to log out the user
function userLogout() {
    session_start();  // Start the session (if not already started)
    session_unset();  // Clear session variables
    session_destroy(); // Destroy the session
    redirect('userlogin.php'); // Redirect to login page
}


session_start();
function userLogin() {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        // Redirect to user login page if not logged in
        header('userlogin.php'); // Ensure this path matches your user login page
        exit(); // Stop further execution
    }
}


?>