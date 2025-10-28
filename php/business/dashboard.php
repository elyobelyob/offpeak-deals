<?php
require_once __DIR__ . '/../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_errno) {
    die("Database connection failed: " . $conn->connect_error);
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $business_name = trim($_POST['business_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $deal_name = trim($_POST['deal_name']);
    $description = trim($_POST['description']);
    $day_of_week = trim($_POST['day_of_week']);
    $start_time = trim($_POST['start_time']);
    $end_time = trim($_POST['end_time']);

    // Find or create business
    $stmt = $conn->prepare('SELECT id FROM businesses WHERE name = ?');
    $stmt->bind_param('s', $business_name);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $business_id = $row['id'];
    } else {
        $ins = $conn->prepare('INSERT INTO businesses (name) VALUES (?)');
        $ins->bind_param('s', $business_name);
        $ins->execute();
        $business_id = $ins->insert_id;
    }

    // Insert location
    $loc = $conn->prepare('INSERT INTO locations (business_id, address, city) VALUES (?, ?, ?)');
    $loc->bind_param('iss', $business_id, $address, $city);
    $loc->execute();
    $location_id = $loc->insert_id;

    // Insert deal
    $deal_stmt = $conn->prepare('INSERT INTO deals (location_id, name, description, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)');
    $deal_stmt->bind_param('isssss', $location_id, $deal_name, $description, $day_of_week, $start_time, $end_time);
    $deal_stmt->execute();

    $message = 'Deal added successfully!';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Business Dashboard</title>
    <link rel="stylesheet" href="/php/css/style.css">
</head>
<body>
<header>
    <h1>Business Dashboard</h1>
</header>
<div class="container">
    <h2>Add a New Deal</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>
    <form method="post" action="">
        <label>Business Name:<br><input type="text" name="business_name" required></label><br>
        <label>Address:<br><input type="text" name="address" required></label><br>
        <label>City:<br><input type="text" name="city" required></label><br>
        <label>Deal Name:<br><input type="text" name="deal_name" required></label><br>
        <label>Description:<br><input type="text" name="description" required></label><br>
        <label>Day of Week:<br><input type="text" name="day_of_week" required></label><br>
        <label>Start Time (HH:MM):<br><input type="time" name="start_time"></label><br>
        <label>End Time (HH:MM):<br><input type="time" name="end_time"></label><br>
        <button type="submit">Add Deal</button>
    </form>
</div>
</body>
</html>
<?php
$conn->close();
?>
