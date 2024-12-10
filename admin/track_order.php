<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Assuming you have a role column in the users table or a way to check if the user is admin
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!isset($_SESSION['user_id'])) {
    echo "<p>You need to log in to view your orders.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bookstore";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    // Update order status
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $order_id);
    $update_stmt->execute();
    $update_stmt->close();
}

// Fetch orders
$sql = "
SELECT id, full_name, address, city, zip_code, 
       payment_method, total_amount, created_at, status 
FROM orders 
WHERE user_id = ? OR ? 
ORDER BY created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $isAdmin);
$stmt->execute();
$result = $stmt->get_result();
?>
<?php include 'header.php'; ?>
<div class="container mt-5 mb-5">
    <h1 class="text-center">Manage Orders</h1>
    <br>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Full Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>ZIP Code</th>
                    <th>Payment Method</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['city']); ?></td>
                        <td><?php echo htmlspecialchars($row['zip_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                        <td>
                            <?php if ($isAdmin): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="Pending" <?php echo $row['status'] === 'Pending' ? 'selected' : ''; ?>>
                                            Pending</option>
                                        <option value="Processing" <?php echo $row['status'] === 'Processing' ? 'selected' : ''; ?>>
                                            Processing</option>
                                        <option value="Shipped" <?php echo $row['status'] === 'Shipped' ? 'selected' : ''; ?>>
                                            Shipped</option>
                                        <option value="Delivered" <?php echo $row['status'] === 'Delivered' ? 'selected' : ''; ?>>
                                            Delivered</option>
                                        <option value="Cancelled" <?php echo $row['status'] === 'Cancelled' ? 'selected' : ''; ?>>
                                            Cancelled</option>
                                    </select>
                                </form>
                            <?php else: ?>
                                <?php echo htmlspecialchars($row['status']); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Admin-specific actions can go here -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
<?php
$stmt->close();
$conn->close();
?>