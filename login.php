<?php
    // Starting a session to get superglobal for login error.
    session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Innovexa Labs Login Page">
    <meta name="keywords" content="Innovexa, Labs, Login, Security">
    <meta name="author" content="G05">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innovexa Labs Login</title>

    <!-- External stylesheet -->
    <link href="styles/styles.css" rel="stylesheet">
</head>

<body>
    <!-- ================== HEADER ================== -->
    <?php include_once "header.inc"; ?>

    <main>
        <section id="login_page">
            <h1>Log In</h1>
            <?php
                // Error message for login error
                if(isset($_SESSION['error'])) {
                    echo "<p style='color:red;'>", $_SESSION['error'], "<p>";
                    unset($_SESSION['error']);
                } 
                // Error message for accessing login page while logged in - redirects to index page
                if(isset($_SESSION['username'])) {
                    $_SESSION['error'] = "You are already logged in as ";
                    header('Location: index.php');
                    exit();
                }
            ?>
            <!-- Submit form to login_process.php -->
            <form action="login_process.php" method="post">
                <!-- Username entry -->
                <p>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                </p>
                <!-- Password entry -->
                <p>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                </p>
                <!-- Submit button -->
                <p>
                <button type="submit">Login</button>
            </form>
        </section>
    </main>

    <!-- ================== FOOTER ================== -->
    <?php include_once "footer.inc"; ?>
</body>
</html>