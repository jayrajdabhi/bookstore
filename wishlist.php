<?php
session_start();

// Database connection
include 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch wishlist items from database
$query = "SELECT b.* FROM wishlist w JOIN books b ON w.book_id = b.id WHERE w.user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wishlist_items = $result->fetch_all(MYSQLI_ASSOC);

// Handle remove from wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
    $book_id = $_POST['book_id'];
    $delete_query = "DELETE FROM wishlist WHERE user_id = ? AND book_id = ?";
    $delete_stmt = $db->prepare($delete_query);
    $delete_stmt->bind_param("ii", $user_id, $book_id);
    $delete_stmt->execute();
    header("Location: wishlist.php");
    exit();
}
?>

<?php include 'components/header.php'; ?>

<main class="container my-5">
    <h2>Your Wishlist</h2>
    <?php if (empty($wishlist_items)): ?>
        <p>Your wishlist is empty.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($wishlist_items as $item): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="img/<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top"
                            alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="card-text">$<?php echo number_format($item['price'], 2); ?></p>
                            <form method="post" action="wishlist.php">
                                <input type="hidden" name="book_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include 'components/footer.php'; ?>