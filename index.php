<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSaver - Home</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <header>
        <div class="logo">SmartSaver</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="contact.php">Contact Us</a>
            <a href="login.php">Login</a>
            <a href="signup.php">Sign Up</a>

        </nav>
    </header>

    <section class="about">
    <div class="about-content">
        <h1>Welcome to <span>SmartSaver</span> </h1>
        <p>Your first step towards smart salary management and achieving your financial goals with confidence.</p>
        <div class="cta">
            <a href="login.php" class="btn">Log In</a>

        </div>
        <p>Don't have an account?  <a href="signup.php" class="btn">Sign Up</a></p>
        </div>
        <div class="about-image">
            <img src="about-background.jpg" alt="SmartSaver">
        </div>
    </section>

    <footer>
        <p>&copy; 2025 SmartSaver. All rights reserved.</p>
    </footer>
</body>
</html>