<?php
session_start();

require 'email_config.php'; // Include PHPMailer configuration

$conn = new mysqli('localhost', 'root', '', 'bookstore');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $message = "Please enter your email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Check if email exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $token = bin2hex(random_bytes(50));
            $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Store token in database
            $sql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $token, $expires_at, $email);
            $stmt->execute();

            // Send password reset email
            $reset_link = "http://localhost/bookstore-main/reset_password.php?token=" . $token;
            
            $message = sendPasswordResetEmail($email, $reset_link);
        } else {
            $message = "Email not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h2>Forgot Password</h2>
        
        <!-- Display any messages if available -->
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="forgot_password.php">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" class="login-btn">Send Reset Link</button>
        </form>

        <div class="signup-link">
            <p>Remembered your password? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
