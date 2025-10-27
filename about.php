<?php session_start(); /* about.php — Project 2 Task #7 */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Innovexa Labs About Page">
    <meta name="keywords" content="Innovexa, Labs, About">
    <meta name="author" content="G05">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Innovexa Labs</title>
    <link href="styles/aboutStyles.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
</head>
<body>

<!-- ================== HEADER + NAV ================== -->
<?php include_once "header.inc"; ?>
<?php include_once "nav.inc"; ?>

<h2>About</h2>

<?php
// ===== DB bootstrap for About section =====
require_once('settings.php'); // provides $conn

// Create table if needed
$createSql = "CREATE TABLE IF NOT EXISTS about_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL,
    student_id VARCHAR(16) NOT NULL,
    role VARCHAR(80) DEFAULT NULL,
    project1_tasks TEXT NOT NULL,
    project2_tasks TEXT NOT NULL,
    quote VARCHAR(255) DEFAULT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
@mysqli_query($conn, $createSql);

// Seed once if empty (set to false after first run)
$SEED_IF_EMPTY = true;
if ($SEED_IF_EMPTY) {
    $countRes = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM about_members");
    $row = $countRes ? mysqli_fetch_assoc($countRes) : null;
    if (!$row || intval($row['c']) === 0) {
        $seedSql = "INSERT INTO about_members
        (name, student_id, role, project1_tasks, project2_tasks, quote, photo) VALUES
        ('Ridowan Ahmed', '103534450', 'UI/UX & Front-end',
         'Designed index + apply pages, branding, navigation & footer.',
         'Converted pages to PHP includes; designed EOI schema & server validation plan.',
         'Design is how it works.', NULL),
        ('Dillon', '106045201', 'Release & Docs',
         'Versioning, packaging, submission docs.',
         'Testing checklist & packaging improvements.', NULL, NULL),
        ('Lewis', '105921300', 'Content & QA',
         'Copywriting and proofreading across pages.',
         'Prepared test data and QA passes.', NULL, NULL),
        ('Andrew', '106273840', 'PM',
         'Scope, task allocation, timeline.',
         'DB tasks & manager features planning.', NULL, NULL)";
        @mysqli_query($conn, $seedSql);
    }
    if ($countRes) { mysqli_free_result($countRes); }
}

// Fetch members for display
$sql = "SELECT id, name, student_id, role, project1_tasks, project2_tasks, quote, photo
        FROM about_members
        ORDER BY name ASC";
$result = @mysqli_query($conn, $sql);

// HTML escape helper
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>

<main id="about">

    <!-- ===== Class Info ===== -->
    <section id="TimeTable">
        <h2>Class Info</h2>
        <p><strong>Intro to Web Development COS10026</strong></p>
        <ul>
            <li>Monday Live Lecture
                <ul><li>11:30 AM &ndash; 12:30 PM</li></ul>
            </li>
            <li>Tuesday Workshop
                <ul><li>2:30 PM &ndash; 4:30 PM</li></ul>
            </li>
        </ul>
    </section>

    <!-- ===== Contributions (Dynamic from DB) ===== -->
    <section id="CandQ">
        <h2>Contributions and Quotes of Group 05</h2>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <section class="member-grid">
                <?php while ($m = mysqli_fetch_assoc($result)): ?>
                    <article class="member-card">
                        <header class="member-head">
                            <?php if (!empty($m['photo'])): ?>
                                <img src="<?php echo h($m['photo']); ?>" alt="<?php echo h($m['name']); ?>" class="member-photo">
                            <?php endif; ?>
                            <div>
                                <h3 class="member-name"><?php echo h($m['name']); ?></h3>
                                <p class="member-meta">
                                    <span class="student-id">ID: <?php echo h($m['student_id']); ?></span>
                                    <?php if (!empty($m['role'])): ?> • <span class="member-role"><?php echo h($m['role']); ?></span><?php endif; ?>
                                </p>
                            </div>
                        </header>

                        <div class="member-body">
                            <details open>
                                <summary><strong>Project 1</strong> — Contributions</summary>
                                <p><?php echo nl2br(h($m['project1_tasks'])); ?></p>
                            </details>
                            <details>
                                <summary><strong>Project 2</strong> — Contributions</summary>
                                <p><?php echo nl2br(h($m['project2_tasks'])); ?></p>
                            </details>
                            <?php if (!empty($m['quote'])): ?>
                                <blockquote class="member-quote">“<?php echo h($m['quote']); ?>”</blockquote>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </section>
        <?php else: ?>
            <p>No member records found yet. Add rows into <code>about_members</code> to populate this section.</p>
        <?php endif; ?>
    </section>

    <!-- ===== Group Photo ===== -->
    <section id="GPhoto">
        <h2>Group Photo</h2>
        <figure>
            <img src="images/group-photo.jpeg" alt="Innovexa Team Photo" width="600" height="400">
            <figcaption>Innovexa Team Photo</figcaption>
        </figure>
    </section>

    <!-- ===== Fun Facts (kept as your static table) ===== -->
    <section id="FunFacts">
        <h2>Fun Facts</h2>
        <table>
            <caption>Innovexa Team Member Fun Facts</caption>
            <thead>
            <tr>
                <th>Name</th>
                <th>Dream Job</th>
                <th>Coding Snack</th>
                <th>Hometown</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Andrew</td><td>Data Scientist</td><td>Trail mix</td><td>Melbourne</td></tr>
            <tr><td>Lewis</td><td>UX Designer</td><td>Dark chocolate</td><td>Melbourne</td></tr>
            <tr><td>Dillon</td><td>Game Developer</td><td>Pizza</td><td>Melbourne</td></tr>
            <tr><td>Ridowan</td><td>Computor Scientist</td><td>Pizza</td><td>Melbourne</td></tr>
            </tbody>
        </table>
    </section>

</main>

<?php
// tidy up DB handles
if ($result) { mysqli_free_result($result); }
mysqli_close($conn);
?>

<!-- ================== FOOTER ================== -->
<?php include_once "footer.inc"; ?>

</body>
</html>
