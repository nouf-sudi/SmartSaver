<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM financial_records WHERE user_id = ? ORDER BY record_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$records = $stmt->fetchAll();

$selectedRecordId = $_GET['record_id'] ?? ($records[0]['id'] ?? null);
$selectedRecord = null;
$commitmentsByCategory = [];

if ($selectedRecordId) {
    $stmt = $pdo->prepare("SELECT * FROM financial_records WHERE id = ?");
    $stmt->execute([$selectedRecordId]);
    $selectedRecord = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT category, amount FROM commitments WHERE record_id = ?");
    $stmt->execute([$selectedRecordId]);
    $commitmentsByCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spending Summary - SmartSaver</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <section class="spending-summary">
        <h2>Spending Summary</h2>

        <label for="recordSelect">Select a Record:</label>
        <select id="recordSelect" onchange="window.location.href = 'spending_summary.php?record_id=' + this.value">
            <?php foreach ($records as $record): ?>
                <option value="<?= $record['id'] ?>" <?= $record['id'] == $selectedRecordId ? 'selected' : '' ?>>
                    <?= date('Y-m-d', strtotime($record['record_date'])) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="financial-summary">
            <h3>Financial Summary</h3>
            <p>Salary: <span><?= isset($selectedRecord['salary']) ? number_format($selectedRecord['salary'], 2) : '0.00' ?></span></p>
            <p>Savings: <span><?= isset($selectedRecord['savings']) ? number_format($selectedRecord['savings'], 2) : '0.00' ?></span></p>
            <p>Surplus: <span><?= isset($selectedRecord['surplus']) ? number_format($selectedRecord['surplus'], 2) : '0.00' ?></span></p>
        </div>

        <div class="chart-container" style="width: 500px; height: 500px; margin: 0 auto;">
            <canvas id="spendingChart"></canvas>
        </div>

        <h3>Detailed Spending Breakdown</h3>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($commitmentsByCategory)): ?>
                    <?php foreach ($commitmentsByCategory as $commitment): ?>
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

        <script>
            const categories = <?= json_encode(array_column($commitmentsByCategory, 'category')) ?>;
            const amounts = <?= json_encode(array_column($commitmentsByCategory, 'amount')) ?>;

            const ctx = document.getElementById('spendingChart').getContext('2d');
            const spendingChart = new Chart(ctx, {
                type: 'pie', 
                data: {
                    labels: categories,
                    datasets: [{
                        label: 'Amount',
                        data: amounts,
                        backgroundColor: [
                            'rgba(1, 18, 255, 0.2)',
                            'rgba(255, 251, 0, 0.2)',
                            'rgba(4, 255, 0, 0.2)',
                            'rgba(0, 225, 255, 0.2)',
                            'rgba(255, 0, 0, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(54, 162, 235, 1)',
                            
                            
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: true, 
                    plugins: {
                        legend: {
                            position: 'bottom', 
                        }
                    }
                }
            });
        </script>
    </section>

    <footer>
        <p>&copy; 2025 SmartSaver. All rights reserved.</p>
    </footer>
</body>
</html>