<?php
$config = require __DIR__ . '/../config/database.php';

$mysqli = new mysqli(
    $config['host'],
    $config['username'],
    $config['password'],
    $config['database'],
    $config['port']
);

if ($mysqli->connect_error) {
    http_response_code(500);
    die('Database connection failed: ' . htmlspecialchars($mysqli->connect_error));
}

if (!$mysqli->set_charset($config['charset'])) {
    http_response_code(500);
    die('Error setting charset: ' . htmlspecialchars($mysqli->error));
}
