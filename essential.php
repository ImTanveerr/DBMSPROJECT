<?php 

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit(); // Ensures the script stops execution after the redirect
}


// Logout function to destroy the session and redirect to login page for admin
function logout() {
    session_start();    // Start the session
    session_unset();    // Unset all session variables
    session_destroy();  // Destroy the session
    redirect('index.php');  // Redirect to login page
}
// Partner logout function
function partnerLogout() {
    // Destroy session and redirect to partner login page
    session_unset();    // Unset all session variables
    session_destroy();  // Destroy the session
    redirect('partnerlogin.php');  // Redirect to partner login page
}

// Partner login check function
function partnerLogin() {
    // Check if the partner is logged in
    if (!isset($_SESSION['partnerLogin']) || $_SESSION['partnerLogin'] !== true) {
        // Redirect to login page if not logged in
        redirect('partnerlogin.php'); // Ensure the path matches your login page
    }
}


// essential.php

session_start();

function adminLogin() {
    // Check if the admin is logged in
    if (!isset($_SESSION['adminLogin']) || $_SESSION['adminLogin'] !== true) {
        // Redirect to login page if not logged in
        header('Location: login.php'); // Make sure the path matches your actual login page
        exit(); // Stop further execution
    }
}


// Alert function with type-based styling
function alert($type, $message){
    $sbs_class = ($type == "Success") ? "alert-success" : "alert-danger"; // Corrected the assignment
    echo <<<HTML
    <div class="alert $sbs_class alert-dismissible fade show" role="alert">
        <strong>$message</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
HTML;
}
?>