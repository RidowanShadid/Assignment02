<?php
// Starting the session
session_start();
// Database connection details from settings.php

// Checking if a user is actually logged in before logging out
if(isset($_SESSION['username'])) {
    // Unsets variables, destroys session, and sends user back to login page
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
} else {
    header('Location: index.php');
    exit();
}

?>