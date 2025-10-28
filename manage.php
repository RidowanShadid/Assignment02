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

    // Used Atie's lecture 11 code as reference - wrote the if statements in their full form
    if (isset($_SESSION['username']) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {
        // Query for deleting with given job reference - chose for input via text to avoid accidental deletion on pressing delete
        if (isset($_POST['delete'])) {
            $delete = $_POST['delete'];
            $remove = $dbcon -> prepare("DELETE FROM eoi WHERE jobref =?");
            $remove -> bind_param("s", $delete);
            $remove -> execute();
            $remove -> close();
        }
        // Query for modifying eol status
        if (isset($_POST['EOInumber'])) {
            $EOInumber = (int)$_POST['EOInumber'];
        } else {
            $EOInumber = 0;
        }
        if (isset($_POST['status'] )) {
           $status = $_POST['status'];
        } else {
           $status = '';
        }
        if ($EOInumber > 0 && in_array($status, ['new','current', 'final'], true)) {
            $change = $dbcon -> prepare("UPDATE eoi SET status=? WHERE EOInumber =?");
            $change -> bind_param("si", $status, $EOInumber);
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
        
        <div>
        <!-- List options for manager with a text field -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get"> 
            <p><button type="submit" value="search">Search</button></p>
            <label for="sort">List:</label>
            <select id="sort" name="sort">
                <option value="">All EOIs</option>
                <option value="jobref">Job Reference</option>
                <option value="firstname">First Name</option>
                <option value="lastname">Last Name</option>
                <option value="fullname">Full Name</option>
            </select>
            <br>
            <label for="list">Search:</label>
            <input type="text" id="search" name="search">
        </form>
        </div>

        <?php
            // Sort selected columns and switch from descending to ascending plus vice versa
            // ChatGPT was used to figure out this idea
            if (isset($_GET['order_by'])) {
                $order_by = $_GET['order_by'];
            } else {
                $order_by = "EOInumber";
            }
            if (isset($_GET['order_dir'])) {
                $order_dir = $_GET['order_dir'];
            } else {
                $order_dir = 'ASC';
                $order_symbol = '&#8593;';
            }
            if ($order_dir == 'ASC') {
                $order_dir_opp = 'DESC';
                $order_symbol = '&#8595;';
            } else {
                $order_dir_opp = 'ASC';
                $order_symbol = '&#8593;';
            }

            // Prepared statements for queries relating to manager options above
            if (isset($_SESSION['username']) && ($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['sort']) && isset($_GET['search'])) {
                $sort = mysqli_real_escape_string($dbcon, $_GET['sort']);
                $search = mysqli_real_escape_string($dbcon, $_GET['search']);
                if (empty($sort)) {
                    $query = $dbcon -> prepare("SELECT * FROM eoi ORDER BY $order_by $order_dir");
                    $query -> execute();
                    $list = $query -> get_result();
                    $query -> close();
                }
                if ($sort == "fullname") {
                    $search = explode(" ", $search);
                    $query = $dbcon -> prepare("SELECT * FROM eoi WHERE ((firstname=?) AND (lastname=?)) ORDER BY $order_by $order_dir");
                    $query -> bind_param("ss", $search[0], $search[1]);
                    $query -> execute();
                    $list = $query -> get_result();
                    $query -> close();
                }
                elseif (!empty($sort) && !empty($search)) {
                    $query = $dbcon -> prepare("SELECT * FROM eoi WHERE $sort=? ORDER BY $order_by $order_dir");
                    $query -> bind_param("s", $search);
                    $query -> execute();
                    $list = $query -> get_result();
                    $query -> close();
                }
            } else {
                $query = $dbcon -> prepare("SELECT * FROM eoi ORDER BY $order_by $order_dir");
                $query -> execute();
                $list = $query -> get_result();
                $query -> close();
            }
            
            // EOI list displaying on successful query
        if ($list && mysqli_num_rows($list) > 0) {
            ?>
            <table border='1' cellpadding='5'>
                <tr>
                <!-- Modified table headers to be selectable and sort rows to switch from descending to ascending plus vice versa - ChatGPT was used to figure out this idea -->
                    <th>
                        <a href="?order_by=EOInumber&order_dir=<?php echo $order_dir_opp ?>">
                            EOI Number 
                            <?php if($order_by == "EOInumber") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=jobref&order_dir=<?php echo $order_dir_opp ?>">Job Reference 
                            <?php if($order_by == "jobref") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=firstname&order_dir=<?php echo $order_dir_opp ?>">First Name 
                            <?php if($order_by == "firstname") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=lastname&order_dir=<?php echo $order_dir_opp ?>">Last Name 
                            <?php if($order_by == "lastname") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>

                    <th>
                        <a href="?order_by=dob&order_dir=<?php echo $order_dir_opp ?>">DOB 
                            <?php if($order_by == "dob") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=gender&order_dir=<?php echo $order_dir_opp ?>">Gender 
                            <?php if($order_by == "gender") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=street&order_dir=<?php echo $order_dir_opp ?>">Street 
                            <?php if($order_by == "street") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=suburb&order_dir=<?php echo $order_dir_opp ?>">Suburb 
                            <?php if($order_by == "suburb") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=state&order_dir=<?php echo $order_dir_opp ?>">State 
                            <?php if($order_by == "state") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=email&order_dir=<?php echo $order_dir_opp ?>">Email 
                            <?php if($order_by == "email") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=phone&order_dir=<?php echo $order_dir_opp ?>">Phone 
                            <?php if($order_by == "phone") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=skills&order_dir=<?php echo $order_dir_opp ?>">Skills 
                            <?php if($order_by == "skills") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>
                    <th>
                        <a href="?order_by=otherskillsdesc&order_dir=<?php echo $order_dir_opp ?>">Other Skills 
                            <?php if($order_by == "otherskillsdesc") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>

                    <th>
                        <a href="?order_by=status&order_dir=<?php echo $order_dir_opp ?>">Status 
                            <?php if($order_by == "status") {
                                echo $order_symbol;
                                } ?> 
                        </a>
                    </th>

                    <th>Confirm Status</th>
                    
                </tr>
                <?php 
                    while ($row = mysqli_fetch_assoc($list)) {
                        $EOInumber = (int)$row['EOInumber'];
                        $jobref = htmlspecialchars($row['jobref']);
                        $firstname = htmlspecialchars($row['firstname']);
                        $lastname = htmlspecialchars($row['lastname']);
                        $dob = htmlspecialchars($row['dob']);
                        $gender = htmlspecialchars($row['gender']);
                        $street = htmlspecialchars($row['street']);
                        $suburb = htmlspecialchars($row['suburb']);
                        $state = htmlspecialchars($row['state']);
                        $email = htmlspecialchars($row['email']);
                        $phone = htmlspecialchars($row['phone']);
                        $skills = htmlspecialchars($row['skills']);
                        $otherskillsdesc = htmlspecialchars($row['otherskillsdesc']);
                        $status = $row['status'];
                ?>
                <tr>
                    <td><?php echo $EOInumber; ?></td>
                    <td><?php echo $jobref ?></td>
                    <td><?php echo $firstname; ?></td>
                    <td><?php echo $lastname; ?></td>
                    <td><?php echo $dob; ?></td>
                    <td><?php echo $gender; ?></td>
                    <td><?php echo $street; ?></td>
                    <td><?php echo $suburb; ?></td>
                    <td><?php echo $state; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $phone; ?></td>
                    <td><?php echo $skills; ?></td>
                    <td><?php echo $otherskillsdesc; ?></td>
                    <!-- Used Atie's lecture 11 code as reference - if statements were written in their full form
                    Tried to figure out a way to make the form submit every individual change (or lack of change) for status,
                    but ended up using Atie's method of having a submit button for each eoi -->
                    <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post"> 
                        <td>
                            <input type="hidden" name="EOInumber" value="<?php echo $EOInumber;?>">
                            <select name="status">
                                <option value="new" <?php 
                                    if ($status=='New') {
                                        echo 'selected';
                                    } else {
                                        echo '';
                                    } ?>>New</option>
                                <option value="current" <?php 
                                    if ($status=='Current') {
                                        echo 'selected';
                                    } else {
                                        echo '';
                                    } ?>>Current</option>
                                <option value="final" <?php 
                                    if ($status=='Final') {
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
                    } // While loop end
                ?>
            </table>
        <?php // If loop end
        } elseif ($list && mysqli_num_rows($list) == 0) {
            echo "<p>No entries were found.</p>";
        }
        mysqli_close($conn);
        ?>

        <div>
        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post"> 
            <p><button type="submit" value="delete">Delete</button></p>
            <label for="list">Job Reference:</label>
            <input type="text" id="delete" name="delete" maxlength=5>
        </form>
        </div>
    </main>

    <!-- ================== FOOTER ================== -->
    <?php include_once "footer.inc"; ?>
</body>
</html>