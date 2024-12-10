<?php include 'components/header.php'; ?>
<div class="container mt-5 mb-5">
    <h1 class="txt-center">Track Orders</h1>
    <br>
    <?php

    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<p>You need to log in to view your orders.</p>";
        exit;
    }

    $user_id = $_SESSION['user_id']; // Get the current user ID from the session
    
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "bookstore";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch order details with status for the current user
    $sql = "
    SELECT o.id AS order_id, o.full_name, o.address, o.city, o.zip_code, 
           o.payment_method, o.total_amount, o.created_at, o.status, o.created_at
    FROM orders o
  
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
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

    $stmt->close();
    $conn->close();
    ?>
</div>

<?php include 'components/footer.php'; ?>