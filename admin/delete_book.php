<?php
// Start session
session_start();

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not an admin
    header("Location: ./login.php");
    exit();
}

// Include database configuration
include '../config/database.php';

// Check if the `id` parameter is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = $_GET['id'];

    // Prepare the delete statement
    $query = "DELETE FROM books WHERE id = ?";
    $stmt = $db->prepare($query);

    // Bind the parameter and execute the statement
    if ($stmt) {
        $stmt->bind_param("i", $book_id);
        if ($stmt->execute()) {
            // Redirect to the admin dashboard with a success message
            $_SESSION['success'] = "Book deleted successfully!";
        } else {
            // Redirect to the admin dashboard with an error message
            $_SESSION['error'] = "Failed to delete the book. Please try again.";
        }
        $stmt->close();
    } else {
        // Redirect to the admin dashboard with an error message
        $_SESSION['error'] = "Failed to prepare the delete statement.";
    }
} else {
    // Redirect to the admin dashboard with an error message
    $_SESSION['error'] = "Invalid book ID.";
}

// Redirect back to the admin dashboard
header("Location: index.php");
exit();
?>