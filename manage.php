<?php
    // Starting a session to get superglobal for username
    session_start(); 
    // Database connection details from settings.php
    require_once("settings.php");

    // Connecting to database - error if connection failed
    $dbcon = mysqli_connect($host, $username, $password, $database);
    if (!$dbcon) {
        die("Connection to database unsuccessful." . mysqli_connect_error());
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Innovexa Labs Manager Page">
    <meta name="keywords" content="Innovexa, Labs, Manage, Security">
    <meta name="author" content="G05">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innovexa Labs Manage</title>

    <!-- External stylesheet -->
    <link href="styles/styles.css" rel="stylesheet">
</head>

<body>
    <!-- ================== HEADER ================== -->
    <?php include_once "header.inc"; ?>

    <main>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post"> 
        <p>
            <button type="submit">Change</button>
        </p>
        <?php 
            if(!isset($_SESSION['username'])) {
                header('Location: login.php');
            }
            echo "Welcome " , $_SESSION['username'] , ".";
        ?>
        <?php
             $list = mysqli_query($dbcon, "SELECT EOInumber, job_ref, first_name, last_name, status FROM eoi ORDER BY EOInumber ASC");
             if ($list && mysqli_num_rows($list) > 0) {
        ?>
            <table>
                <tr>
                    <th>EOI Number</th>
                    <th>Job Reference</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Status</th>
                </tr>
                <?php 
                    while ($row = mysqli_fetch_assoc($list)) {
                ?>
                <tr>
                    <td><?php echo (int)$row['EOInumber']; ?></td>
                    <td><?php echo htmlspecialchars($row['job_ref']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
                <?php
                    }
                ?>
            </table>
            <?php
            }
            ?>
        </form>
    </main>

    <!-- ================== FOOTER ================== -->
    <?php include_once "footer.inc"; ?>
</body>
</html>