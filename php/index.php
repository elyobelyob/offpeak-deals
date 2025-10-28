<?php
require_once 'config.php';

// Simple DB connection test
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_errno) {
    die("Failed to connect to MySQL: " . $conn->connect_error);
}

echo "<h1>Off‑Peak Deals</h1>";
echo "<p>Connected successfully to the database!</p>";

$conn->close();
?>
