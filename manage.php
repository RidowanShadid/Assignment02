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

    if (isset($_SESSION['username']) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {
        if (isset($_POST['eoi_no'])) {
            $eoi_no = $_POST['eoi_no'];
        } else {
            
            $eoi_no = 0;
        }
        if (isset($_POST['eoi_status'] )) {
           $eoi_status = $_POST['eoi_status'];
        } else {
           $eoi_status = '';
        }
        if ($eoi_no > 0) {
            $change = $dbcon -> prepare("UPDATE eoi SET eoi_status=? WHERE eoi_no =?");
            $change -> bind_param("si", $eoi_status, $eoi_no);
            $change -> execute();
            $change -> close();
        } else {
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
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
        <?php 
            if(!isset($_SESSION['username'])) {
                header('Location: login.php');
            }
            echo "Welcome " , $_SESSION['username'] , ".";
        ?>
        
        <p>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get"> 
            <p><button type="submit" value="search">Search</button></p>
            <label for="sort">List:</label>
            <select id="sort" name="sort">
                <option value="">Please Select</option>
                <option value="eoi_no">All EOIs</option>
                <option value="job_ref">Job Reference</option>
                <option value="first_name">First Name</option>
                <option value="last_name">Last Name</option>
                <option value="fullname">Full Name</option>
            </select>
            <br>
            <label for="list">Search:</label>
            <input type="text" id="search" name="search">
        </form>
        </p>

        <?php
            if (isset($_SESSION['username']) && ($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['sort']) && isset($_GET['search'])) {
                $sort = mysqli_real_escape_string($dbcon, $_GET['sort']);
                $search = mysqli_real_escape_string($dbcon, $_GET['search']);
                if (empty($sort)) {
                    $list = mysqli_query($dbcon, "SELECT eoi_no, job_ref, first_name, last_name, eoi_status FROM eoi ORDER BY eoi_no ASC");
                }
                if ($sort == "job_ref") {
                    $list = mysqli_query($dbcon, "SELECT eoi_no, job_ref, first_name, last_name, eoi_status FROM eoi WHERE job_ref = '$search'");
                }
                if ($sort == "first_name") {
                    $list = mysqli_query($dbcon, "SELECT eoi_no, job_ref, first_name, last_name, eoi_status FROM eoi WHERE first_name = '$search'");
                }
                if ($sort == "last_name") {
                    $list = mysqli_query($dbcon, "SELECT eoi_no, job_ref, first_name, last_name, eoi_status FROM eoi WHERE last_name = '$search'");
                }
            } else {
                $list = mysqli_query($dbcon, "SELECT eoi_no, job_ref, first_name, last_name, eoi_status FROM eoi ORDER BY eoi_no ASC");
            }
            
            if ($list && mysqli_num_rows($list) > 0) {
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"> 
            <p><button type="submit" value="change">Change</button></p>
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
                    <td><?php echo (int)$row['eoi_no']; ?></td>
                    <td><?php echo htmlspecialchars($row['job_ref']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td>
                        <input type="hidden" name="eoi_no" value="<?php echo (int)$row['eoi_no'];?>">
                        <select name="eoi_status">
                            <option value="new" <?= $row['eoi_status']=='NEW' ? 'selected' : '' ?>>New</option>
                            <option value="current" <?= $row['eoi_status']=='CURRENT' ? 'selected' : '' ?>>Current</option>
                            <option value="final" <?= $row['eoi_status']=='FINAL' ? 'selected' : '' ?>>Final</option>
                        </select>
                </tr>
                <?php
                    }
                ?>
            </table>
            <?php
            }
            mysqli_close($conn);
            ?>
        </form>
    </main>

    <!-- ================== FOOTER ================== -->
    <?php include_once "footer.inc"; ?>
</body>
</html>