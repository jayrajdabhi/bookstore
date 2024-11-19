<?php
include 'components/header.php';
include 'config/database.php';

// Initialize search query
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Fetch books from the database
$query = "SELECT id, name, author, price, image FROM books 
          WHERE name LIKE ? OR author LIKE ? OR genre LIKE ?";
$stmt = $db->prepare($query);
$searchTerm = '%' . $search . '%';
$stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
$books = $result->fetch_all(MYSQLI_ASSOC);
?>

<main class="container my-5">
    <!-- Promotional Carousel -->
    <div id="promoCarousel" class="carousel slide mb-4" data-ride="carousel">
        <!-- Carousel content -->
    </div>

    <div class="book-container">
        <h2 id="book-h2">Available Books</h2>

        <!-- Search Form -->
        <form action="index.php" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by name, author, or genre..."
                    value="<?php echo htmlspecialchars($search); ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>

        <!-- Book List Grid -->
        <div class="book-grid">
            <?php if (count($books) > 0): ?>
                <?php foreach ($books as $book): ?>
                    <div class="book-item">
                        <a href="book_details.php?id=<?php echo $book['id']; ?>">
                            <img src="<?php echo htmlspecialchars($book['image']); ?>"
                                alt="<?php echo htmlspecialchars($book['name']); ?>" class="book-image">
                            <div class="book-name"><?php echo htmlspecialchars($book['name']); ?></div>
                            <div class="book-author">Author: <?php echo htmlspecialchars($book['author']); ?></div>
                            <div class="book-price">Price: $<?php echo number_format($book['price'], 2); ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No books found for "<?php echo htmlspecialchars($search); ?>".</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>