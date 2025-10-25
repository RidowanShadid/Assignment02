<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="jobs.php">Jobs</a></li>
        <li><a href="apply.php">Apply</a></li>
        <li><a href="about.php">About</a></li>
        <?php
            if (!isset($_SESSION['username'])) {
                echo "<li><a href='login.php'>Log In</a></li>";
            } else if (isset($_SESSION['username'])) {
                echo "<li><a href='manage.php'>Manage</a></li>";
                echo "<li><a href='logout.php'>Log Out</a></li>";
            }
        ?>
    </ul>
</nav>