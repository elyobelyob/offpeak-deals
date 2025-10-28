<?php
require_once __DIR__ . '/../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_errno) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Off-Peak Deals</title>
    <link rel="stylesheet" href="/php/css/style.css">
</head>
<body>
<header>
    <h1>Off‑Peak Deals</h1>
</header>
<div class="container">
    <h2>Welcome to Off‑Peak Deals</h2>
    <p>Discover lunchtime specials and off‑peak offers near you.</p>
</div>
</body>
</html>
<?php
$conn->close();
?>
