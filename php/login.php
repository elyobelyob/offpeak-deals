<?php
require_once 'config.php';
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_errno) {
        die('Database error: ' . $conn->connect_error);
    }
    $stmt = $conn->prepare('SELECT id, password_hash FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($id, $hash);
    if ($stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id;
        // TODO: load user roles to session and redirect accordingly
        header('Location: /php/public/index.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
</head>
<body>
<h1>Login</h1>
<?php if($error): ?>
<p style="color:red"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<form method="POST">
<label>Username: <input type="text" name="username"></label><br>
<label>Password: <input type="password" name="password"></label><br>
<button type="submit">Login</button>
</form>
</body>
</html>
