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
</head>
<body>
    <h1>Welcome to Off‑Peak Deals</h1>
    <p>Discover lunchtime specials and off‑peak offers near you.</p>
</body>
</html>
<?php
$conn->close();
?>
