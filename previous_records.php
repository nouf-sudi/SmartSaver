<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_record'])) {
    $recordId = $_POST['record_id'];
    
    $stmt = $pdo->prepare("DELETE FROM commitments WHERE record_id = ?");
    $stmt->execute([$recordId]);

    $stmt = $pdo->prepare("DELETE FROM financial_records WHERE id = ?");
    $stmt->execute([$recordId]);

    header('Location: previous_records.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM financial_records WHERE user_id = ? ORDER BY record_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$records = $stmt->fetchAll();

$commitmentsByRecord = [];
foreach ($records as $record) {
    $stmt = $pdo->prepare("SELECT category, amount FROM commitments WHERE record_id = ?");
    $stmt->execute([$record['id']]);
    $commitmentsByRecord[$record['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous Records - SmartSaver</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleCommitments(recordId) {
            const commitmentsRow = document.getElementById(`commitments-${recordId}`);
            if (commitmentsRow.style.display === "none") {
                commitmentsRow.style.display = "table-row";
            } else {
                commitmentsRow.style.display = "none";
            }
        }

        function confirmDelete(recordId) {
            if (confirm("Are you sure you want to delete this record? This action cannot be undone.")) {
                document.getElementById(`delete-form-${recordId}`).submit();
            }
        }
    </script>
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

    <section class="previous-records">
        <h2>Previous Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Salary</th>
                    <th>Savings</th>
                    <th>Surplus</th>
                    <th>Commitments</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= date('Y-m-d', strtotime($record['record_date'])) ?></td>
                        <td><?= number_format($record['salary'], 2) ?></td>
                        <td><?= number_format($record['savings'], 2) ?></td>
                        <td><?= number_format($record['surplus'], 2) ?></td>
                        <td>
                            <?php if (!empty($commitmentsByRecord[$record['id']])): ?>
                                <button onclick="toggleCommitments(<?= $record['id'] ?>)">View Details</button>
                            <?php else: ?>
                                No commitments
                            <?php endif; ?>
                        </td>
                        <td>
                        <button onclick="window.location.href='edit_record.php?record_id=<?= $record['id'] ?>'" class="btn-edit">Edit</button>
                            <form id="delete-form-<?= $record['id'] ?>" action="previous_records.php" method="POST" style="display: inline;">
                                <input type="hidden" name="record_id" value="<?= $record['id'] ?>">
                                <input type="hidden" name="delete_record" value="1">
                                <button type="button" onclick="confirmDelete(<?= $record['id'] ?>)" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="commitments-<?= $record['id'] ?>" style="display: none;">
                        <td colspan="6">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($commitmentsByRecord[$record['id']])): ?>
                                        <?php foreach ($commitmentsByRecord[$record['id']] as $commitment): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($commitment['category']) ?></td>
                                                <td><?= number_format($commitment['amount'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2">No commitments found for this record.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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