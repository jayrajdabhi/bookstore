<?php
session_start();
include 'config/database.php'; // Include your database configuration file

// Validate database connection
if (!$db) {
    die("<p>Database connection error. Please try again later.</p>");
}

// Handle "Add to Cart" or "Add to Wishlist"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $book_id = filter_var($_POST['book_id'], FILTER_VALIDATE_INT);

    if (!$book_id) {
        $_SESSION['message'] = "Invalid book ID.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Initialize cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Assuming user login is required to add to the wishlist
    $user_id = $_SESSION['user_id'] ?? null; // Store the logged-in user ID in the session

    if ($action === 'add_to_cart') {
        // Add to cart logic
        if (isset($_SESSION['cart'][$book_id])) {
            $_SESSION['cart'][$book_id]['quantity'] += 1; // Increment quantity
        } else {
            $_SESSION['cart'][$book_id] = [
                'id' => $book_id,
                'quantity' => 1,
            ];
        }
        $_SESSION['message'] = "Item added to cart successfully!";
    }

    if ($action === 'add_to_wishlist') {
        if (!$user_id) {
            $_SESSION['message'] = "Please log in to add items to your wishlist.";
            header("Location: login.php");
            exit;
        }

        // Check if the book is already in the wishlist for this user
        $query = "SELECT * FROM wishlist WHERE user_id = ? AND book_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['message'] = "Item is already in your wishlist!";
        } else {
            // Insert into the wishlist table
            $insert_query = "INSERT INTO wishlist (user_id, book_id) VALUES (?, ?)";
            $insert_stmt = $db->prepare($insert_query);
            $insert_stmt->bind_param("ii", $user_id, $book_id);

            if ($insert_stmt->execute()) {
                $_SESSION['message'] = "Item added to wishlist successfully!";
            } else {
                $_SESSION['message'] = "Failed to add item to wishlist.";
            }
        }
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=$book_id");
    exit;
}

// Fetch and validate book details
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = intval($_GET['id']);

    // Fetch book details along with genre
    $query = "
        SELECT b.*, g.genre_name AS genre 
        FROM books b
        LEFT JOIN genres g ON b.genre_id = g.id
        WHERE b.id = ?
    ";
    $stmt = $db->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $book = $result->fetch_assoc();
        } else {
            echo "<p>Book not found.</p>";
            exit;
        }
    } else {
        echo "<p>Query error. Please try again later.</p>";
        exit;
    }

    // Fetch related books based on genre
    $genre = $book['genre']; // Get the genre of the current book
    $relatedBooksQuery = "
        SELECT b.id, b.name, b.author, b.price, b.image
        FROM books b
        LEFT JOIN genres g ON b.genre_id = g.id
        WHERE g.genre_name = ? AND b.id != ?
        LIMIT 4
    ";
    $relatedBooksStmt = $db->prepare($relatedBooksQuery);
    $relatedBooksStmt->bind_param("si", $genre, $book_id);
    $relatedBooksStmt->execute();
    $relatedBooksResult = $relatedBooksStmt->get_result();
    $relatedBooks = $relatedBooksResult->fetch_all(MYSQLI_ASSOC);
} else {
    echo "<p>Invalid book ID.</p>";
    exit;
}
?>

<?php include 'components/header.php'; ?>

<main class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <img src="img/<?php echo htmlspecialchars($book['image']); ?>"
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

            <!-- Add to Cart Form -->
            <form action="#" method="post" class="mt-3">
                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                <input type="hidden" name="action" value="add_to_cart">
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </form>

            <!-- Add to Wishlist Form -->
            <form action="#" method="post" class="mt-3">
                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                <input type="hidden" name="action" value="add_to_wishlist">
                <button type="submit" class="btn btn-secondary">Add to Wishlist</button>
            </form>

            <!-- Display success message -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success mt-3">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recommended Books Section -->
    <div class="mt-5">
        <h3>Recommended Books</h3>
        <div class="row">
            <?php foreach ($relatedBooks as $relatedBook): ?>
                <div class="col-md-3">
                    <div class="card">
                        <img src="img/<?php echo htmlspecialchars($relatedBook['image']); ?>" class="card-img-top"
                            alt="<?php echo htmlspecialchars($relatedBook['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($relatedBook['name']); ?></h5>
                            <p class="card-text">Author: <?php echo htmlspecialchars($relatedBook['author']); ?></p>
                            <p class="card-text">Price: $<?php echo number_format($relatedBook['price'], 2); ?></p>
                            <a href="book_details.php?id=<?php echo $relatedBook['id']; ?>" class="btn btn-primary">View
                                Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>