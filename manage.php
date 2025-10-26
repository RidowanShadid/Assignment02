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

    // Used Ati's lecture 11 code as reference - wrote the if statements in their full form
    if (isset($_SESSION['username']) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {
        // Query for deleting with given job reference - chose for input via text to avoid accidental deletion on pressing delete
        if (isset($_POST['delete'])) {
            $delete = $_POST['delete'];
            $remove = $dbcon -> prepare("DELETE FROM eoi WHERE job_ref =?");
            $remove -> bind_param("s", $delete);
            $remove -> execute();
            $remove -> close();
        }
        // Query for modifying eol status
        if (isset($_POST['eoi_no'])) {
            $eoi_no = (int)$_POST['eoi_no'];
        } else {
            $eoi_no = 0;
        }
        if (isset($_POST['eoi_status'] )) {
           $eoi_status = $_POST['eoi_status'];
        } else {
           $eoi_status = '';
        }
        if ($eoi_no > 0 && in_array($eoi_status, ['new','current', 'final'], true)) {
            $change = $dbcon -> prepare("UPDATE eoi SET eoi_status=? WHERE eoi_no =?");
            $change -> bind_param("si", $eoi_status, $eoi_no);
            $change -> execute();
            $change -> close();
        } else {
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
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
            // Output message welcoming manager
            if(!isset($_SESSION['username'])) {
                header('Location: login.php');
            }
            echo "Welcome " , $_SESSION['username'] , ".";
        ?>
        
        <p>
        <!-- List options for manager with a text field -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get"> 
            <p><button type="submit" value="search">Search</button></p>
            <label for="sort">List:</label>
            <select id="sort" name="sort">
                <option value="">All EOIs</option>
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
            // Prepared statements for queries relating to manager options above
            if (isset($_SESSION['username']) && ($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['sort']) && isset($_GET['search'])) {
                $sort = mysqli_real_escape_string($dbcon, $_GET['sort']);
                $search = mysqli_real_escape_string($dbcon, $_GET['search']);
                if (empty($sort)) {
                    $query = $dbcon -> prepare("SELECT eoi_no, job_ref, first_name, last_name, eoi_status FROM eoi ORDER BY eoi_no ASC");
                    $query -> execute();
                    $list = $query -> get_result();
                    $query -> close();
                }
                if ($sort == "fullname") {
                    $search = explode(" ", $search);
                    $query = $dbcon -> prepare("SELECT eoi_no, job_ref, first_name, last_name, eoi_status FROM eoi WHERE ((first_name=?) AND (last_name=?)) ORDER BY first_name ASC, last_name ASC");
                    $query -> bind_param("ss", $search[0], $search[1]);
                    $query -> execute();
                    $list = $query -> get_result();
                    $query -> close();
                }
                elseif (!empty($sort) && !empty($search)) {
                    $query = $dbcon -> prepare("SELECT eoi_no, job_ref, first_name, last_name, eoi_status FROM eoi WHERE $sort=? ORDER BY $sort ASC");
                    $query -> bind_param("s", $search);
                    $query -> execute();
                    $list = $query -> get_result();
                    $query -> close();
                }
            } else {
                $query = $dbcon -> prepare("SELECT eoi_no, job_ref, first_name, last_name, eoi_status FROM eoi ORDER BY eoi_no ASC");
                $query -> execute();
                $list = $query -> get_result();
                $query -> close();
            }
            
            // EOI list displaying on successful query
            // Used Ati's lecture 11 code as reference - if statements were written in their full form
            // Tried to figure out a way to make the form submit every individual change (or lack of change) for status,
            // but couldn't figure out a way (arrays?), so ended up using a submit button for each eoi
            if ($list && mysqli_num_rows($list) > 0) {
        ?>
        <table border='1' cellpadding='5'>
            <tr>
                <th>EOI Number</th>
                <th>Job Reference</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Status</th>
                
            </tr>
            <?php 
                while ($row = mysqli_fetch_assoc($list)) {
                    $eoi_no = (int)$row['eoi_no'];
                    $job_ref = htmlspecialchars($row['job_ref']);
                    $first_name = htmlspecialchars($row['first_name']);
                    $last_name = htmlspecialchars($row['last_name']);
                    $eoi_status = $row['eoi_status'];
            ?>
            <tr>
                <td><?php echo $eoi_no; ?></td>
                <td><?php echo $job_ref ?></td>
                <td><?php echo $first_name; ?></td>
                <td><?php echo $last_name; ?></td>
                <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post"> 
                    <td>
                        <input type="hidden" name="eoi_no" value="<?php echo $eoi_no;?>">
                        <select name="eoi_status">
                            <option value="new" <?php 
                                if ($eoi_status=='NEW') {
                                    echo 'selected';
                                } else {
                                    echo '';
                                } ?>>New</option>
                            <option value="current" <?php 
                                if ($eoi_status=='CURRENT') {
                                    echo 'selected';
                                } else {
                                    echo '';
                                } ?>>Current</option>
                            <option value="final" <?php 
                                if ($eoi_status=='FINAL') {
                                    echo 'selected';
                                } else {
                                    echo '';
                                } ?>>Final</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" value="change">Change</button>
                    </td>
                </form>
            </tr>
            <?php
                }
            ?>
        </table>
        <?php
        }
        mysqli_close($conn);
        ?>

        <p>
        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post"> 
            <p><button type="submit" value="delete">Delete</button></p>
            <label for="list">Job Reference:</label>
            <input type="text" id="delete" name="delete" maxlength=5>
        </form>
        </p>
    </main>

    <!-- ================== FOOTER ================== -->
    <?php include_once "footer.inc"; ?>
</body>
</html>