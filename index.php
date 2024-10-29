<?php
include 'components/header.php';
include 'config/database.php';

// Fetch books from the database
$query = "SELECT id, name, author, price, image FROM books";
$result = $db->query($query);
$books = $result->fetch_all(MYSQLI_ASSOC);
?>

<main class="container my-5">
    <!-- Promotional Carousel -->
    <div id="promoCarousel" class="carousel slide mb-4" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/banner.jpg" class="d-block w-100" alt="Promotion 1">
            </div>
            <div class="carousel-item">
                <img src="img/banner.jpg" class="d-block w-100" alt="Promotion 2">
            </div>
            <div class="carousel-item">
                <img src="img/banner.jpg" class="d-block w-100" alt="Promotion 3">
            </div>
        </div>
        <a class="carousel-control-prev" href="#promoCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#promoCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

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

    <!-- Deals Modal -->
    <div class="modal fade" id="dealModal" tabindex="-1" role="dialog" aria-labelledby="dealModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dealModalLabel">Ongoing Deals & Discounts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Deal of the Day: 20% off on selected bestsellers!</h6>
                    <p>Check out our collection of bestsellers and enjoy 20% off until the end of the week.</p>
                    <h6>Buy 2, Get 1 Free:</h6>
                    <p>Purchase any two books and get the third one free! This offer is valid for all fiction titles.</p>
                    <h6>Student Discount:</h6>
                    <p>Students receive an additional 10% off with a valid student ID. Don’t miss out!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        // Show the modal on page load
        $('#dealModal').modal('show');
    });
</script>

<?php include 'components/footer.php'; ?>
