<?php
include 'config/database.php'; // Include your database configuration file

// Check if the book ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = $_GET['id'];

    // Fetch book details from the database
    $query = "SELECT * FROM books WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a book was found
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "<p>Book not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid book ID.</p>";
    exit;
}
?>


<?php include 'components/header.php'; ?>

<main class="container my-5">
    <div class="book-detail-container">
        <h2><?php echo htmlspecialchars($book['name']); ?></h2>
        <div class="book-detail">
            <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['name']); ?>" class="book-image">
            <div class="book-info">
                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p><strong>Price:</strong> $<?php echo number_format($book['price'], 2); ?></p>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
                <p><strong>Publication Year:</strong> <?php echo htmlspecialchars($book['publication_year']); ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                <form action="#" method="post">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <input type="submit" value="Add to Cart" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>

</body>
</html>
