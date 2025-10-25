<?php
// settings.php — central DB connection

$host     = "localhost";  // XAMPP local server
$username = "root";
$password = "";           // per assignment requirement: no password
$database = "ildb";       // your database name

// Create a single reusable mysqli connection
$conn = @mysqli_connect($host, $username, $password, $database);

// Fail fast with a clean message if connection is not available
if (!$conn) {
    die("<p class='error'>Database connection failed: " . htmlspecialchars(mysqli_connect_error()) . "</p>");
}
?>
