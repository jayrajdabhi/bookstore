<?php
// Start session
session_start();

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not an admin
    header("Location: ../login.php");
    exit();
}
?>

<?php include 'header.php'; ?>
<div class="container mt-5 mb-5">
    <h1>Welcome to admin dashboard</h1>
</div>

<?php include 'footer.php'; ?>
