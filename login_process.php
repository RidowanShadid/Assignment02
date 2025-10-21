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
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input from the login form, and remove whitespaces
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    // Hash the password for security purposes using default algorithm
    // $passhash = password_hash($password, PASSWORD_DEFAULT);
    // SQL query to check for match in database
    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($dbcon, $query);
    if ($user = mysqli_fetch_assoc($result)) {
        $_SESSION['username'] = $user['username'];
        header('Location: manage.php');
    } else {
        // Login error
        $_SESSION['error'] = "Please try again (Invalid username and/or password).";
        header('Location: login.php');
    }
}
?>