<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmartSaver</title>
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

    <section class="dashboard">
        <h2>Dashboard</h2>

        <div class="dashboard-buttons">
            <a href="enter_salary.php" class="btn">Enter Salary and Commitments</a>
            <a href="previous_records.php" class="btn">View Previous Records</a>
            <a href="spending_summary.php" class="btn">View Spending Summary</a>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 SmartSaver. All rights reserved.</p>
    </footer>
</body>
</html>