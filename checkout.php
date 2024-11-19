<?php
// Include database connection
$conn = new mysqli('localhost', 'root', '', 'bookstore'); // Adjust credentials as needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = 1; // Assuming a logged-in user with user_id = 1
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture and validate form data
    $full_name = trim($_POST['full_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
    $total_amount = 30.00; // Static for demonstration

    // Simple validation
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
        $stmt = $conn->prepare("INSERT INTO orders (user_id, full_name, address, city, zip_code, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $full_name, $address, $city, $zip_code, $payment_method, $total_amount);

        if ($stmt->execute()) {
            echo "<script>alert('Order placed successfully!'); window.location.href = 'order_success.php';</script>";
        } else {
            echo "<script>alert('Error placing order: " . $stmt->error . "');</script>";
        }
    }
}
?>

<?php include 'components/header.php'; ?>
<style>
    .checkout-page {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(120deg, #f0f4ff, #dfe9f3);
        padding: 20px;
        /* Space for outer page */
    }

    .checkout-container {
        background-color: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        /* Soft shadow for container */
        max-width: 900px;
        /* Adjusted for larger container */
        margin: 20px auto;
        /* Center the container with vertical spacing */
        width: 90%;
        /* Make it responsive and stretch near screen edges */
    }

    .checkout-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
        font-size: 26px;
    }

    .cart-summary,
    .shipping-info,
    .payment-options,
    .order-review {
        margin-bottom: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    .cart-summary h3,
    .shipping-info h3,
    .payment-options h3,
    .order-review h3 {
        color: #007bff;
        /* Accent color for headings */
        margin-bottom: 10px;
        font-size: 18px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        color: #333;
        margin-bottom: 5px;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="number"],
    .form-group input[type="radio"],
    .form-group input[type="checkbox"],
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }

    .checkout-btn {
        display: block;
        width: 100%;
        padding: 15px;
        background-color: #007bff;
        /* Matches button colors in login and register pages */
        color: white;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 20px;
    }

    .checkout-btn:hover {
        background-color: #0056b3;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .checkout-container {
            padding: 20px;
        }

        .cart-summary,
        .shipping-info,
        .payment-options,
        .order-review {
            padding: 15px;
        }

        .checkout-container h2 {
            font-size: 22px;
        }

        .checkout-btn {
            font-size: 14px;
        }
    }

    /* Error message styling */
    .error-message {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }
</style>
</head>

<main>
    <div class="checkout-container">
        <h2>Checkout</h2>
        <form method="POST" action="">
            <div class="cart-summary">
                <h3>Cart Summary</h3>
                <p>Item 1 - $10.00</p>
                <p>Item 2 - $20.00</p>
                <p><strong>Total: $30.00</strong></p>
            </div>

            <div class="shipping-info">
                <h3>Shipping Information</h3>
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" id="full_name"
                        value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                    <?php if (isset($errors['full_name'])): ?>
                        <p class="error-message"><?php echo $errors['full_name']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address"
                        value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                    <?php if (isset($errors['address'])): ?>
                        <p class="error-message"><?php echo $errors['address']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" id="city"
                        value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                    <?php if (isset($errors['city'])): ?>
                        <p class="error-message"><?php echo $errors['city']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="zip_code">ZIP Code</label>
                    <input type="text" name="zip_code" id="zip_code"
                        value="<?php echo htmlspecialchars($_POST['zip_code'] ?? ''); ?>">
                    <?php if (isset($errors['zip_code'])): ?>
                        <p class="error-message"><?php echo $errors['zip_code']; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="payment-options">
                <h3>Payment Method</h3>
                <div class="form-group">
                    <label>
                        <input type="radio" name="payment_method" value="Credit/Debit Card" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'Credit/Debit Card') ? 'checked' : ''; ?>>
                        Credit/Debit Card
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input type="radio" name="payment_method" value="PayPal" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'PayPal') ? 'checked' : ''; ?>>
                        PayPal
                    </label>
                </div>
                <?php if (isset($errors['payment_method'])): ?>
                    <p class="error-message"><?php echo $errors['payment_method']; ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="checkout-btn">Complete Order</button>
        </form>
    </div>
</main>
<?php include 'components/footer.php'; ?>