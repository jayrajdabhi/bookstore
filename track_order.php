<?php
session_start(); // Start the session to access session variables like user_id
include 'config/database.php'; // Include database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>You need to log in to view your orders.</p>";
    exit;
}

$user_id = $_SESSION['user_id']; // Get the current user ID from the session

// SQL query to fetch order details with status for the current user
$sql = "
    SELECT o.id AS order_id, o.full_name, o.address, o.city, o.zip_code, 
           o.payment_method, o.total_amount, o.created_at, o.status, o.created_at
    FROM orders o
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
";

$stmt = $db->prepare($sql); // Use $db for the connection
$stmt->bind_param("i", $user_id); // Bind user_id as an integer parameter
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result of the query

?>
<?php include 'components/header.php'; ?>
<div class="container mt-5 mb-5">
    <h1 class="txt-center">Track Orders</h1>
    <br>

    <?php
    if ($result->num_rows > 0) {
        // Display the orders in a table
        echo "<table class='table table-striped'>";
        echo "<thead><tr>
            <th>Order ID</th>
            <th>Full Name</th>
            <th>Address</th>
            <th>City</th>
            <th>ZIP Code</th>
            <th>Payment Method</th>
            <th>Total Amount</th>
            <th>Created At</th>
            <th>Status</th>
          </tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['order_id']}</td>
                <td>{$row['full_name']}</td>
                <td>{$row['address']}</td>
                <td>{$row['city']}</td>
                <td>{$row['zip_code']}</td>
                <td>{$row['payment_method']}</td>
                <td>{$row['total_amount']}</td>
                <td>{$row['created_at']}</td>
                <td>{$row['status']}</td>
              </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No orders found.</p>";
    }

    $stmt->close(); // Close the prepared statement
    $db->close(); // Close the database connection
    ?>
</div>

<?php include 'components/footer.php'; ?>