<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$userId = $_GET['user_id'] ?? null;
$error = '';
$success = '';

if (!$userId) {
    header('Location: admin_dashboard.php');
    exit();
}

$stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: admin_dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($role)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $userId]);
        $success = "User updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - SmartSaver</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">SmartSaver</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="logout.php">Log Out</a>
        </nav>
    </header>

    <section class="form-container">
        <h2>Edit User</h2>
        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form action="edit_user.php?user_id=<?= $userId ?>" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>

            <button type="submit">Update User</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2025 SmartSaver. All rights reserved.</p>
    </footer>
</body>
</html>