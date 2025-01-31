<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

$stmt = $pdo->prepare("SELECT category FROM commitment_categories WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$userCategories = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $newCategory = trim($_POST['new_category']);
        if (!empty($newCategory)) {
            if (!in_array($newCategory, $userCategories)) {
                $stmt = $pdo->prepare("INSERT INTO commitment_categories (user_id, category) VALUES (?, ?)");
                $stmt->execute([$_SESSION['user_id'], $newCategory]);
                $userCategories[] = $newCategory; 
                $success = "Category added successfully!";
            } else {
                $error = "Category already exists.";
            }
        } else {
            $error = "Category name cannot be empty.";
        }
    } else {
        $salary = $_POST['salary'];
        $savingsPercentage = $_POST['savings'];
        $commitments = []; 

        foreach ($_POST['commitments'] as $category => $amount) {
            if (!empty($amount) && $amount > 0) {
                $commitments[$category] = $amount;
            }
        }

        if ($salary <= 0 || $savingsPercentage < 0 || $savingsPercentage > 100) {
            $error = "Invalid input. Please check your values.";
        } elseif (empty($commitments)) {
            $error = "Please enter at least one commitment.";
        } else {
            $savingsAmount = ($salary * $savingsPercentage) / 100;
            $totalCommitments = array_sum($commitments);
            $surplus = $salary - $totalCommitments - $savingsAmount;

            $stmt = $pdo->prepare("INSERT INTO financial_records (user_id, salary, savings, surplus, record_date) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$_SESSION['user_id'], $salary, $savingsAmount, $surplus]);
            $recordId = $pdo->lastInsertId();

            foreach ($commitments as $category => $amount) {
                $stmt = $pdo->prepare("INSERT INTO commitments (record_id, category, amount) VALUES (?, ?, ?)");
                $stmt->execute([$recordId, $category, $amount]);
            }

            $success = "Record saved successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Salary and Commitments - SmartSaver</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function calculate() {
            const salary = parseFloat(document.getElementById('salary').value) || 0;
            const savingsPercentage = parseFloat(document.getElementById('savings').value) || 0;
            let totalCommitments = 0;

            document.querySelectorAll('.commitment-amount').forEach(input => {
                totalCommitments += parseFloat(input.value) || 0;
            });

            const savingsAmount = (salary * savingsPercentage) / 100;
            const surplus = salary - totalCommitments - savingsAmount;

            document.getElementById('savingsAmount').textContent = savingsAmount.toFixed(2);
            document.getElementById('totalCommitments').textContent = totalCommitments.toFixed(2);
            document.getElementById('surplus').textContent = surplus.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('salary').addEventListener('input', calculate);
            document.getElementById('savings').addEventListener('input', calculate);
            document.querySelectorAll('.commitment-amount').forEach(input => {
                input.addEventListener('input', calculate);
            });
        });
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

    <section class="form-container">
        <h2>Enter Salary and Commitments</h2>
        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form action="enter_salary.php" method="POST" style="margin-bottom: 20px;">
            <label for="new_category">Add New Commitment Category:</label>
            <input type="text" id="new_category" name="new_category" placeholder="Enter a new category" required>
            <button type="submit" name="add_category">Add Category</button>
        </form>

        <form action="enter_salary.php" method="POST">
            <label for="salary">Salary:</label>
            <input type="number" id="salary" name="salary" required oninput="calculate()">

            <label for="savings">Savings (%):</label>
            <input type="number" id="savings" name="savings" min="0" max="100" required oninput="calculate()">

            <h3>Commitments by Category</h3>
            <?php foreach ($userCategories as $category): ?>
                <label for="commitment_<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?>:</label>
                <input type="number" id="commitment_<?= htmlspecialchars($category) ?>" name="commitments[<?= htmlspecialchars($category) ?>]" class="commitment-amount" oninput="calculate()">
            <?php endforeach; ?>

            <button type="submit">Submit</button>
        </form>

        <div class="calculation-results">
            <h3>Calculation Results</h3>
            <p>Savings: <span id="savingsAmount">0.00</span></p>
            <p>Total Commitments: <span id="totalCommitments">0.00</span></p>
            <p>Surplus: <span id="surplus">0.00</span></p>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 SmartSaver. All rights reserved.</p>
    </footer>
</body>
</html>