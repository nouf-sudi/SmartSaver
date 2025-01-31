<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT id, name, email, role FROM users");
$stmt->execute();
$users = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    
    $stmt = $pdo->prepare("DELETE FROM financial_records WHERE user_id = ?");
    $stmt->execute([$userId]);

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    header('Location: admin_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SmartSaver</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user? This action cannot be undone.")) {
                document.getElementById(`delete-form-${userId}`).submit();
            }
        }
    </script>
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

    <section class="admin-dashboard">
        <h2>Admin Dashboard</h2>

        <h3>Users</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <a href="edit_user.php?user_id=<?= $user['id'] ?>" class="btn-edit">Edit</a>
                            <form id="delete-form-<?= $user['id'] ?>" action="admin_dashboard.php" method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="delete_user" value="1">
                                <button type="button" onclick="confirmDelete(<?= $user['id'] ?>)" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <footer>
        <p>&copy; 2025 SmartSaver. All rights reserved.</p>
    </footer>
</body>
</html>