<?php
require_once __DIR__ . '/../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_errno) {
    die("Database connection failed: " . $conn->connect_error);
}

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q !== '') {
    $stmt = $conn->prepare("SELECT b.name AS business_name, l.address, l.city, d.name AS deal_name, d.description, d.day_of_week, d.start_time, d.end_time FROM deals d JOIN locations l ON d.location_id = l.id JOIN businesses b ON l.business_id = b.id WHERE b.name LIKE ? OR l.city LIKE ? OR d.name LIKE ? OR d.description LIKE ?");
    $param = '%' . $q . '%';
    $stmt->bind_param('ssss', $param, $param, $param, $param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT b.name AS business_name, l.address, l.city, d.name AS deal_name, d.description, d.day_of_week, d.start_time, d.end_time FROM deals d JOIN locations l ON d.location_id = l.id JOIN businesses b ON l.business_id = b.id");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Off‑Peak Deals</title>
    <link rel="stylesheet" href="/php/css/style.css">
</head>
<body>
<header>
    <h1>Off‑Peak Deals</h1>
</header>
<div class="container">
    <h2>Find Off‑Peak Deals</h2>
    <form method="get" action="">
        <input type="text" name="q" placeholder="Search by restaurant or city" value="<?php echo htmlspecialchars($q); ?>">
        <button type="submit">Search</button>
    </form>
    <?php
    if ($result && $result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>{$row['deal_name']}</strong> at {$row['business_name']} ({$row['city']}) – {$row['description']} on {$row['day_of_week']}";
            if (!empty($row['start_time']) && !empty($row['end_time'])) {
                echo " ({$row['start_time']} - {$row['end_time']})";
            }
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No deals found.</p>";
    }
    ?>
</div>
</body>
</html>
<?php
$conn->close();
?>
