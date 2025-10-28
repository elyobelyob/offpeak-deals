<?php
session_start();
require_once __DIR__ . '/../config.php';

// Ensure the user is logged in with the business role
if (!isset($_SESSION['roles']) || !in_array('business', $_SESSION['roles'])) {
    header('Location: /php/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_errno) {
    die('Database connection failed: ' . $conn->connect_error);
}

$message = '';

// Fetch businesses associated with the logged-in user
$businesses = [];
$stmt = $conn->prepare('SELECT b.id, b.name FROM businesses b JOIN business_users bu ON bu.business_id = b.id WHERE bu.user_id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $businesses[] = $row;
}
$stmt->close();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add_business') {
        $businessName = trim($_POST['business_name']);
        if ($businessName !== '') {
            $stmt = $conn->prepare('INSERT INTO businesses (name) VALUES (?)');
            $stmt->bind_param('s', $businessName);
            if ($stmt->execute()) {
                $businessId = $stmt->insert_id;
                // map this business to the user
                $map = $conn->prepare('INSERT INTO business_users (business_id, user_id) VALUES (?, ?)');
                $map->bind_param('ii', $businessId, $userId);
                $map->execute();
                $map->close();
                $message = 'Business added successfully.';
            } else {
                $message = 'Error adding business.';
            }
            $stmt->close();
        }
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    } elseif ($action === 'add_location') {
        // Only allow adding a location for a business the user owns
        $businessId = intval($_POST['business_id']);
        $addr1 = trim($_POST['address_line1']);
        $addr2 = trim($_POST['address_line2']);
        $city = trim($_POST['city']);
        $state = trim($_POST['state']);
        $postal = trim($_POST['postal_code']);
        $latitude = floatval($_POST['latitude']);
        $longitude = floatval($_POST['longitude']);
        $check = $conn->prepare('SELECT 1 FROM business_users WHERE business_id = ? AND user_id = ?');
        $check->bind_param('ii', $businessId, $userId);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $stmt = $conn->prepare('INSERT INTO locations (business_id, address_line1, address_line2, city, state, postal_code, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('isssssdd', $businessId, $addr1, $addr2, $city, $state, $postal, $latitude, $longitude);
            if ($stmt->execute()) {
                $message = 'Location added successfully.';
            } else {
                $message = 'Error adding location.';
            }
            $stmt->close();
        } else {
            $message = 'You do not have permission to add a location for this business.';
        }
        $check->close();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    } elseif ($action === 'add_deal') {
        // Placeholder for adding deals; ensure you associate deals with locations that belong to the user
        $message = 'Add deal functionality not yet implemented.';
    }
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
    <div class="logo"></div>
    <h1>Business Dashboard</h1>
</header>
<div class="container">
    <?php if ($message) : ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h2>Add Business</h2>
    <form method="post">
        <input type="hidden" name="action" value="add_business">
        <label>Business Name: <input type="text" name="business_name" required></label>
        <button type="submit">Add Business</button>
    </form>

    <h2>Add Location</h2>
    <form method="post">
        <input type="hidden" name="action" value="add_location">
        <label>Business:
            <select name="business_id" required>
                <?php foreach ($businesses as $b) : ?>
                    <option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Address Line 1: <input type="text" name="address_line1" required></label>
        <label>Address Line 2: <input type="text" name="address_line2"></label>
        <label>City: <input type="text" name="city" required></label>
        <label>State: <input type="text" name="state"></label>
        <label>Postal Code: <input type="text" name="postal_code"></label>
        <label>Latitude: <input type="text" name="latitude" required></label>
        <label>Longitude: <input type="text" name="longitude" required></label>
        <button type="submit">Add Location</button>
    </form>

    <!-- Additional forms for adding deals can be implemented here -->
</div>
</body>
</html>
<?php $conn->close(); ?>
