<?php
session_start();
include 'config/database.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Start a transaction
    $db->begin_transaction();

    // Delete related data from existing tables
    $db->query("DELETE FROM wishlist WHERE user_id = $user_id");
    $db->query("DELETE FROM orders WHERE user_id = $user_id");
    $db->query("DELETE FROM user_profiles WHERE user_id = $user_id");
    $db->query("DELETE FROM users WHERE id = $user_id");

    // Commit transaction
    $db->commit();

    // Destroy the session to log the user out
    session_unset();  // Unset all session variables
    session_destroy();  // Destroy the session

    // Display success message with JavaScript alert
    echo "<script>
            alert('Your account has been deleted successfully.');
            window.location.href = 'index.php'; // Redirect to home page
          </script>";
    exit();

} catch (Exception $e) {
    // Rollback changes if there's an error
    $db->rollback();

    // Display error message with JavaScript alert
    echo "<script>
            alert('There was an error deleting your account. Please try again later.');
            window.location.href = 'profile.php'; // Redirect back to the profile page
          </script>";
    exit();
}
?>