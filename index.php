<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$sampleCount = (int)($mysqli->query('SELECT COUNT(*) AS c FROM samples')->fetch_assoc()['c'] ?? 0);
$pendingCount = (int)($mysqli->query("SELECT COUNT(*) AS c FROM samples WHERE status='Pending'")->fetch_assoc()['c'] ?? 0);
$progressCount = (int)($mysqli->query("SELECT COUNT(*) AS c FROM samples WHERE status='In Progress'")->fetch_assoc()['c'] ?? 0);
$completedCount = (int)($mysqli->query("SELECT COUNT(*) AS c FROM samples WHERE status='Completed'")->fetch_assoc()['c'] ?? 0);

require __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <h2>Laboratory Sample Management System</h2>
    <p>Track samples, assign tests, enter results, and generate reports.</p>
    <div class="quick-actions">
        <a class="btn" href="add_sample.php">Add Sample</a>
        <a class="btn" href="view_samples.php">View Samples</a>
        <a class="btn" href="add_test.php">Add Test</a>
        <a class="btn" href="add_result.php">Add Result</a>
        <a class="btn" href="reports.php">Reports</a>
    </div>
</section>
<section class="grid stats">
    <article class="card"><h3>Total Samples</h3><p><?= $sampleCount ?></p></article>
    <article class="card"><h3>Pending</h3><p><?= $pendingCount ?></p></article>
    <article class="card"><h3>In Progress</h3><p><?= $progressCount ?></p></article>
    <article class="card"><h3>Completed</h3><p><?= $completedCount ?></p></article>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
