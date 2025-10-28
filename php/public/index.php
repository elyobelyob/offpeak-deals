<?php
require_once __DIR__ . '/../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_errno) {
    die('Database connection failed: ' . $conn->connect_error);
}

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : null;
$radius = isset($_GET['radius']) ? floatval($_GET['radius']) : null;

$sql = "SELECT d.title AS deal_title, d.description, d.day_of_week, d.start_time, d.end_time, b.name AS business_name, l.city, l.latitude, l.longitude FROM deals d JOIN locations l ON d.location_id = l.id JOIN businesses b ON l.business_id = b.id";
$conditions = [];
$params = [];
$types = '';

if ($q !== '') {
    $conditions[] = "(b.name LIKE ? OR l.city LIKE ? OR d.title LIKE ? OR d.description LIKE ?)";
    $param = '%' . $q . '%';
    $params = array_merge($params, [$param, $param, $param, $param]);
    $types .= 'ssss';
}

if ($lat !== null && $lng !== null && $radius !== null && $radius > 0) {
    $latRadius = $radius / 111.045;
    $lngRadius = $radius / (111.045 * cos(deg2rad($lat)));
    $conditions[] = "(ABS(l.latitude - ?) <= ? AND ABS(l.longitude - ?) <= ?)";
    $params = array_merge($params, [$lat, $latRadius, $lng, $lngRadius]);
    $types .= 'dddd';
}

if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
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
    <div class="logo"></div>
    <h1>Off-Peak Deals</h1>
</header>
<div class="container">
    <h2>Find Off-Peak Deals</h2>
    <form method="get" action="">
        <input type="text" name="q" placeholder="Search by restaurant, city or deal" value="<?php echo htmlspecialchars($q); ?>">
        <input type="text" name="lat" placeholder="Latitude" value="<?php echo htmlspecialchars(isset($_GET['lat']) ? $_GET['lat'] : ''); ?>">
        <input type="text" name="lng" placeholder="Longitude" value="<?php echo htmlspecialchars(isset($_GET['lng']) ? $_GET['lng'] : ''); ?>">
        <input type="text" name="radius" placeholder="Radius (km)" value="<?php echo htmlspecialchars(isset($_GET['radius']) ? $_GET['radius'] : ''); ?>">
        <button type="submit">Search</button>
    </form>
    <?php
    if ($result && $result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>{$row['deal_title']}</strong> at {$row['business_name']} ({$row['city']}) – {$row['description']} on {$row['day_of_week']}";
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
