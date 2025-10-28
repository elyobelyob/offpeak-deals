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
    <title>Business Portal</title>
</head>
<body>
    <h1>Business Portal</h1>
    <p>This area allows businesses to manage their deals and locations. Future functionality will enable multi-location support.</p>
</body>
</html>
<?php $conn->close(); ?>
