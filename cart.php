<?php
session_start();
include 'config/database.php';

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Update quantity
        if ($action === 'update_quantity' && isset($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $book_id => $quantity) {
                if (isset($_SESSION['cart'][$book_id])) {
                    $_SESSION['cart'][$book_id]['quantity'] = max(1, intval($quantity));
                }
            }
        }

        // Delete a book
        if ($action === 'delete' && isset($_POST['book_id'])) {
            unset($_SESSION['cart'][$_POST['book_id']]);
        }

        // Clear the cart
        if ($action === 'clear_cart') {
            unset($_SESSION['cart']);
        }

        // Redirect to avoid form resubmission
        header("Location: cart.php");
        exit;
    }
}

// Fetch cart items from the database
$books = [];
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
    $query = "SELECT * FROM books WHERE id IN ($placeholders)";
    $stmt = $db->prepare($query);
    $stmt->bind_param(str_repeat('i', count($cart_ids)), ...$cart_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<?php include 'components/header.php'; ?>

<main class="container my-5">
    <h2>Your Cart</h2>

    <?php if (empty($books)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <form method="post" action="cart.php">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Author</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($books as $book) {
                        $book_id = $book['id'];
                        $quantity = $_SESSION['cart'][$book_id]['quantity'];
                        $subtotal = $quantity * $book['price'];
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['name']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td>
                                <input type="number" name="quantities[<?php echo $book_id; ?>]" value="<?php echo $quantity; ?>"
                                    min="1" class="form-control w-50">
                            </td>
                            <td>$<?php echo number_format($book['price'], 2); ?></td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <form method="post" action="cart.php" class="d-inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total:</strong></td>
                        <td colspan="2">$<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
            <div class="d-flex justify-content-between">
                <button type="submit" name="action" value="update_quantity" class="btn btn-primary">Update
                    Quantities</button>
                <a href="checkout.php" class="btn btn-success">Checkout</a>
                <form method="post" action="cart.php" class="d-inline">
                    <input type="hidden" name="action" value="clear_cart">
                    <button type="submit" class="btn btn-danger">Clear Cart</button>
                </form>
            </div>
        </form>
    <?php endif; ?>
</main>
 
<?php include 'components/footer.php'; ?>