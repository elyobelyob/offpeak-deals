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
    <title>Off-Peak Admin Panel</title>
</head>
<body>
    <h1>Administration</h1>
    <p>This is the admin portal. Future functionality: manage users, deals, and settings.</p>
</body>
</html>
<?php $conn->close(); ?>
