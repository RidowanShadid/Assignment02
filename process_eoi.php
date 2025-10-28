<?php
// Block direct access (must be POST and must have jobref)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['jobref'])) {
    header("Location: apply.php");
    exit();
}
require_once("settings.php");

// ---------- Create Table if Not Exists ----------
$createTable = "
CREATE TABLE IF NOT EXISTS eoi (
  EOInumber INT AUTO_INCREMENT PRIMARY KEY,
  jobref VARCHAR(5) NOT NULL,
  firstname VARCHAR(20) NOT NULL,
  lastname VARCHAR(20) NOT NULL,
  dob DATE NOT NULL,
  gender ENUM('Male','Female','Other') NOT NULL,
  street VARCHAR(40) NOT NULL,
  suburb VARCHAR(40) NOT NULL,
  state ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(12) NOT NULL,
  skills TEXT,
  otherskillsdesc TEXT,
  status ENUM('New','Current','Final') DEFAULT 'New'
)";
mysqli_query($conn, $createTable);

// ---------- Sanitisation Helper ----------
function sanitise($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// ---------- Collect & Sanitise ----------
$jobref   = sanitise($_POST['jobref']);
$firstname = sanitise($_POST['firstname']);
$lastname = sanitise($_POST['lastname']);
$dob = sanitise($_POST['dob']);
$gender = sanitise($_POST['gender']);
$street = sanitise($_POST['street']);
$suburb = sanitise($_POST['suburb']);
$state = sanitise($_POST['state']);
$email = sanitise($_POST['email']);
$phone = sanitise($_POST['phone']);
$skills = isset($_POST['skills']) ? implode(",", $_POST['skills']) : "";
$otherskillsdesc = sanitise($_POST['otherskillsdesc']);

// ---------- Server-side Validation ----------
$errors = [];

if (!preg_match("/^[A-Za-z0-9]{5}$/", $jobref)) $errors[] = "Invalid Job Reference (5 alphanumeric).";
if (!preg_match("/^[A-Za-z]{1,20}$/", $firstname)) $errors[] = "Invalid First Name.";
if (!preg_match("/^[A-Za-z]{1,20}$/", $lastname)) $errors[] = "Invalid Last Name.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid Email.";
if (!preg_match("/^[0-9]{8,12}$/", $phone)) $errors[] = "Invalid Phone Number (8–12 digits).";

if (count($errors) > 0) {
    echo "<h2>Form Errors</h2><ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul><a href='apply.php'>Go back</a>";
    exit();
}

$query = "INSERT INTO eoi 
(jobref, firstname, lastname, dob, gender, street, suburb, state, email, phone, skills, otherskillsdesc) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("<p>Prepare failed: " . mysqli_error($conn) . "</p>");
}
mysqli_stmt_bind_param($stmt, "ssssssssssss", 
    $jobref, $firstname, $lastname, $dob, $gender, $street, $suburb, $state, $email, $phone, $skills, $otherskillsdesc);

if (mysqli_stmt_execute($stmt)) {
    $eoiNumber = mysqli_insert_id($conn);
    echo "<h2>Application Submitted Successfully</h2>";
    echo "<p>Your Expression of Interest Number is: <strong>$eoiNumber</strong></p>";
    echo "<p>Want to apply again? <a href='apply.php'>Click here<a><p>";
    echo "<p>Need another look at the jobs? <a href='jobs.php'>Click here<a><p>";
    echo "<p>Want to return to the home page? <a href='index.php'>Click here<a><p>";
} else {
    echo "<p>Error saving record: " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
?>