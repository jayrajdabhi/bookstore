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
include 'header.php';

// Fetch all books from the database
$query = "SELECT books.*, genres.genre_name AS genre_name 
          FROM books 
          LEFT JOIN genres ON books.genre_id = genres.id";
$result = $db->query($query);
?>

<div class="container mt-5 mb-5">
    <h2 class="text-center">Admin Dashboard</h2>

    <!-- Feedback Messages -->
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mt-4">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Genre</th>
                    <th>Publication Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($book = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $book['id']; ?></td>
                            <td><?php echo htmlspecialchars($book['name']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td>$<?php echo number_format($book['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($book['genre_name'] ?: 'Unknown'); ?></td>
                            <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                            <td>
                                <!-- Edit Book Link -->
                                <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <!-- Delete Book Link -->
                                <a href="delete_book.php?id=<?php echo $book['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this book?');"
                                    class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No books available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="text-center mt-4">
        <a href="add_book.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Add New Book
        </a>
    </div>
</div>

<?php include 'footer.php'; ?>