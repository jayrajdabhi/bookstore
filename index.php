<?php
include 'components/header.php';
include 'config/database.php';

// Fetch books from the database
$query = "SELECT id, name, author, price, image FROM books";
$result = $db->query($query);
$books = $result->fetch_all(MYSQLI_ASSOC);
?>

<main class="container my-5">
    <div class="book-container">
        <h2 id="book-h2">Available Books</h2>

        <!-- Book List Grid -->
        <div class="book-grid">
            <?php if (count($books) > 0): ?>
                <?php foreach ($books as $book): ?>
                    <div class="book-item">
                        <!-- Link to book details page -->
                        <a href="book_details.php?id=<?php echo $book['id']; ?>">
                            <!-- Display book image -->
                            <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['name']); ?>" class="book-image">

                            <!-- Display book details -->
                            <div class="book-name"><?php echo htmlspecialchars($book['name']); ?></div>
                            <div class="book-author">Author: <?php echo htmlspecialchars($book['author']); ?></div>
                            <div class="book-price">Price: $<?php echo number_format($book['price'], 2); ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No books available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>
