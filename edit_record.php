<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$recordId = $_GET['record_id'] ?? null;
$error = '';
$success = '';

if (!$recordId) {
    header('Location: previous_records.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM financial_records WHERE id = ?");
$stmt->execute([$recordId]);
$record = $stmt->fetch();

if (!$record) {
    header('Location: previous_records.php');
    exit();
}

$stmt = $pdo->prepare("SELECT category, amount FROM commitments WHERE record_id = ?");
$stmt->execute([$recordId]);
$commitmentsByCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salary = $_POST['salary'];
    $savingsPercentage = $_POST['savings'];
    $commitments = $_POST['commitments'] ?? [];

    if ($salary <= 0 || $savingsPercentage < 0 || $savingsPercentage > 100) {
        $error = "Invalid input. Please check your values.";
    } else {
        $savingsAmount = ($salary * $savingsPercentage) / 100;
        $totalCommitments = array_sum($commitments);
        $surplus = $salary - $totalCommitments - $savingsAmount;

        $stmt = $pdo->prepare("UPDATE financial_records SET salary = ?, savings = ?, surplus = ? WHERE id = ?");
        $stmt->execute([$salary, $savingsAmount, $surplus, $recordId]);

        $stmt = $pdo->prepare("DELETE FROM commitments WHERE record_id = ?");
        $stmt->execute([$recordId]);

        foreach ($commitments as $category => $amount) {
            if (!empty($amount) && $amount > 0) {
                $stmt = $pdo->prepare("INSERT INTO commitments (record_id, category, amount) VALUES (?, ?, ?)");
                $stmt->execute([$recordId, $category, $amount]);
            }
        }

        $success = "Record updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record - SmartSaver</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">SmartSaver</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Log Out</a>
        </nav>
    </header>

    <section class="form-container">
        <h2>Edit Record</h2>
        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form action="edit_record.php?record_id=<?= $recordId ?>" method="POST">
            <label for="salary">Salary:</label>
            <input type="number" id="salary" name="salary" value="<?= $record['salary'] ?>" required>

            <label for="savings">Savings (%):</label>
            <input type="number" id="savings" name="savings" value="<?= ($record['savings'] / $record['salary']) * 100 ?>" min="0" max="100" required>

            <h3>Commitments by Category</h3>
            <?php foreach ($commitmentsByCategory as $commitment): ?>
                <label for="commitment_<?= htmlspecialchars($commitment['category']) ?>"><?= htmlspecialchars($commitment['category']) ?>:</label>
                <input type="number" id="commitment_<?= htmlspecialchars($commitment['category']) ?>" name="commitments[<?= htmlspecialchars($commitment['category']) ?>]" value="<?= $commitment['amount'] ?>">
            <?php endforeach; ?>

            <button type="submit">Update Record</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2025 SmartSaver. All rights reserved.</p>
    </footer>
</body>
</html>