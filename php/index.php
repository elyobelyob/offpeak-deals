<?php
require_once 'config.php';

// Simple DB connection test
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_errno) {
    die("Failed to connect to MySQL: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OffPeak Deals</title>
    <link rel="stylesheet" href="/php/css/style.css">
</head>
<body>
<header>
    <h1>Off‑Peak Deals</h1>
</header>
<div class="container">
    <p>Connected successfully to the database!</p>
</div>
</body>
</html>
<?php
$conn->close();
?>
