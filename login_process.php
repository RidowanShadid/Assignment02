<?php
// Starting the session
session_start();
// Database connection details from settings.php
require_once("settings.php");

// Connecting to database - error if connection failed
$dbcon = mysqli_connect($host, $username, $password, $database);
if(!$dbcon) {
    die("Connection to database unsuccessful." . mysqli_connect_error());
}

// Checking for proper form submission
if(($_SERVER['REQUEST_METHOD'] == 'POST') && !empty($_POST['username']) && !empty($_POST['password'])) {
    // Get the input from the login form, and remove whitespaces
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    // SQL query to check match in database for username - grabs both username and password fields
    // Prepared statements to prevent SQL injection
    $query = $dbcon -> prepare("SELECT * FROM user WHERE username = ?");
    $query -> bind_param("s", $username);
    $query -> execute();
    $result = $query -> get_result();
    if(($user = mysqli_fetch_assoc($result)) && (password_verify($password, $user['password']))) {
        // Check if query was successful and entered password matches hashed password
        // Set session variable as username, closes mysql connection, and redirect to manager page
        session_regenerate_id(true); // Replaces session ID on successful login - prevents Fixation
        $_SESSION['username'] = $user['username'];
        mysqli_close($dbcon);
        header('Location: manage.php');
        exit();
    } else {
        // Login error if query returns nothing at all or there is an invalid username/password - closes sql connection and sends back to login page
        $_SESSION['error'] = "Please try again (Invalid username and/or password).";
         mysqli_close($dbcon);
        header('Location: login.php');
        exit();
    }
}
?>