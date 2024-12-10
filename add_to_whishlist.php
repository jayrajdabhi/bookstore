<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bookstore');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_GET['book_id']); // Pass book_id as a GET parameter

// Add to wishlist
$query = "INSERT INTO wishlist (user_id, book_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $book_id);

if ($stmt->execute()) {
    echo "<script>alert('Book added to wishlist!'); window.location.href = 'wishlist.php';</script>";
} else {
    echo "<script>alert('Error adding to wishlist.'); window.location.href = 'index.php';</script>";
}
?>