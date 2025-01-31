<?php
session_start();
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expires in 1 hour

        $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, reset_token, expiry_date) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expiry]);

        // $reset_link = "http://website.com/reset_password.php?token=$token";

        $success = "A password reset link has been sent to your email.";
    } else {
        $error = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSaver - Forgot Password</title>
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

    <section class="form-container">
        <h2>Forgot Password</h2>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="forgot_password.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <p>Remember your password? <a href="login.php">Log In</a></p>
    </section>

    <footer>
        <p>&copy; 2025 SmartSaver. All rights reserved.</p>
    </footer>
</body>
</html>