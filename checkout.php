<?php
session_start();

// Include database connection
include 'config/database.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Dynamically fetch logged-in user ID

$errors = [];
$total_amount = 0;

// Fetch cart data dynamically from session
$cart_items = [];
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
    $query = "SELECT * FROM books WHERE id IN ($placeholders)";
    $stmt = $db->prepare($query);
    $stmt->bind_param(str_repeat('i', count($cart_ids)), ...$cart_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($cart_items as $item) {
        $book_id = $item['id'];
        $quantity = $_SESSION['cart'][$book_id]['quantity'];
        $total_amount += $item['price'] * $quantity;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture and validate form data
    $full_name = trim($_POST['full_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';

    // Validation
    if (empty($full_name))
        $errors['full_name'] = 'Full name is required.';
    if (empty($address))
        $errors['address'] = 'Address is required.';
    if (empty($city))
        $errors['city'] = 'City is required.';
    if (empty($zip_code))
        $errors['zip_code'] = 'ZIP Code is required.';
    if (empty($payment_method))
        $errors['payment_method'] = 'Payment method is required.';

    // Insert data into database if no errors
    if (empty($errors)) {
        // Insert order details
        $stmt = $db->prepare("INSERT INTO orders (user_id, full_name, address, city, zip_code, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssd", $user_id, $full_name, $address, $city, $zip_code, $payment_method, $total_amount);

        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            // Insert individual cart items into order_items table
            $stmt_item = $db->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cart_items as $item) {
                $book_id = $item['id'];
                $quantity = $_SESSION['cart'][$book_id]['quantity'];
                $price = $item['price'];
                $stmt_item->bind_param("iiid", $order_id, $book_id, $quantity, $price);
                $stmt_item->execute();
            }

            // Clear the cart after successful order
            unset($_SESSION['cart']);

            echo "<script>alert('Order placed successfully!'); window.location.href = 'order_success.php';</script>";
        } else {
            echo "<script>alert('Error placing order: " . $stmt->error . "');</script>";
        }
    }
}
?>

<?php include 'components/header.php'; ?>

<main>
    <div class="container checkout-container">
        <h2 class="mt-5">Checkout</h2>
        <form method="POST" action="">
            <div class="cart-summary">
                <h3>Cart Summary</h3>
                <?php if (empty($cart_items)): ?>
                    <p>Your cart is empty.</p>
                <?php else: ?>
                    <?php foreach ($cart_items as $item): ?>
                        <p>
                            <?php echo htmlspecialchars($item['name']); ?> - $<?php echo number_format($item['price'], 2); ?> x
                            <?php echo $_SESSION['cart'][$item['id']]['quantity']; ?>
                        </p>
                    <?php endforeach; ?>
                    <p><strong>Total: $<?php echo number_format($total_amount, 2); ?></strong></p>
                <?php endif; ?>
            </div>

            <div class="shipping-info mt-4">
                <h3>Shipping Information</h3>
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" id="full_name" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                    <?php if (isset($errors['full_name'])): ?>
                        <p class="text-danger"><?php echo $errors['full_name']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                    <?php if (isset($errors['address'])): ?>
                        <p class="text-danger"><?php echo $errors['address']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" id="city" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                    <?php if (isset($errors['city'])): ?>
                        <p class="text-danger"><?php echo $errors['city']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="zip_code">ZIP Code</label>
                    <input type="text" name="zip_code" id="zip_code" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['zip_code'] ?? ''); ?>">
                    <?php if (isset($errors['zip_code'])): ?>
                        <p class="text-danger"><?php echo $errors['zip_code']; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="container payment-options mt-4">
                <h3>Payment Method</h3>
                <div class="form-group">
                    <label>
                        <input type="radio" name="payment_method" value="Credit/Debit Card" class="form-check-input"
                            <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'Credit/Debit Card') ? 'checked' : ''; ?>>
                        Credit/Debit Card
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input type="radio" name="payment_method" value="PayPal" class="form-check-input"
                            <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'PayPal') ? 'checked' : ''; ?>>
                        PayPal
                    </label>
                </div>
                <?php if (isset($errors['payment_method'])): ?>
                    <p class="text-danger"><?php echo $errors['payment_method']; ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Complete Order</button>
        </form>
    </div>
</main>

<?php include 'components/footer.php'; ?>
