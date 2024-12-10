<?php
session_start();
include 'config/database.php';

$message = "";
$token = isset($_GET['token']) ? $_GET['token'] : null;

if ($token) {
    // Verify the reset token
    $sql = "SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, proceed with password reset form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if (empty($new_password) || empty($confirm_password)) {
                $message = "All fields are required.";
            } elseif ($new_password !== $confirm_password) {
                $message = "Passwords do not match.";
            } else {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password in the database and clear the reset token
                $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("ss", $hashed_password, $token);
                $stmt->execute();

                $message = "Password reset successful! You can now <a href='login.php'>login</a>.";
            }
        }
    } else {
        $message = "Invalid or expired token.";
    }
} else {
    $message = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body class="login-page">
    <div class="login-container">
        <h2>Reset Password</h2>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if (isset($token) && $result->num_rows > 0): ?>
            <form method="POST">
                <input type="password" name="password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" class="login-btn">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>