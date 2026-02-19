<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini LIMS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container row">
        <h1>Mini LIMS</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="add_sample.php">Add Sample</a>
            <a href="view_samples.php">View Samples</a>
            <a href="add_test.php">Add Test</a>
            <a href="add_result.php">Add Result</a>
            <a href="reports.php">Reports</a>
        </nav>
    </div>
</header>
<main class="container">
<?php if (!empty($_GET['msg'])): ?>
    <div class="alert <?= h($_GET['type'] ?? 'success') ?>"><?= h($_GET['msg']) ?></div>
<?php endif; ?>
