<?php
session_start();
include 'config/database.php'; // Include your database configuration file

// Handle "Add to Cart"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    // Initialize cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the book is already in the cart
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity'] += 1; // Increment quantity
    } else {
        $_SESSION['cart'][$book_id] = [
            'id' => $book_id,
            'quantity' => 1,
        ];
    }
 
    // Set a success message
    $_SESSION['message'] = "Item added to cart successfully!";

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=$book_id");
    exit;
}

// Check if the book ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = $_GET['id'];

    // Fetch book details from the database with genre
    $query = "
        SELECT b.*, g.genre_name AS genre 
        FROM books b
        LEFT JOIN genres g ON b.genre_id = g.id
        WHERE b.id = ?
    ";
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
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo htmlspecialchars($book['image']); ?>"
                alt="<?php echo htmlspecialchars($book['name']); ?>" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($book['name']); ?></h2>
            <table class="table table-bordered mt-3">
                <tbody>
                    <tr>
                        <th scope="row">Author</th>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Price</th>
                        <td>$<?php echo number_format($book['price'], 2); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Genre</th>
                        <td><?php echo htmlspecialchars($book['genre'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Publication Year</th>
                        <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Description</th>
                        <td><?php echo nl2br(htmlspecialchars($book['description'])); ?></td>
                    </tr>
                </tbody>
            </table>

            <form action="#" method="post" class="mt-3">
                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </form>

            <!-- Display success message -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success mt-3">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']); // Clear the message
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>