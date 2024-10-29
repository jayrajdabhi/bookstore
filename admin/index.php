<?php
// Start session
session_start();

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not an admin
    header("Location: ./login.php");
    exit();
}
?>

<?php include 'header.php'; ?>
<div class="container mt-5 mb-5">
    <h2 class="text-center">Admin Dashboard</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover mt-4">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Product A</td>
                    <td>$10.00</td>
                    <td>Details about Product A</td>
                    <td>
                        <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Product B</td>
                    <td>$20.00</td>
                    <td>Details about Product B</td>
                    <td>
                        <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Product C</td>
                    <td>$30.00</td>
                    <td>Details about Product C</td>
                    <td>
                        <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="text-center mt-4">
        <button class="btn btn-success"><i class="fas fa-plus"></i> Add New Product</button>
    </div>
</div>

<?php include 'footer.php'; ?>
