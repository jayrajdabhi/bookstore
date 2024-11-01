<?php
// Start session
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bookstore');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Retrieve user and profile data
$user_id = $_SESSION['user_id'];
$sql = "SELECT u.username, u.email, up.first_name, up.last_name, up.phone_number, up.address 
        FROM users u 
        LEFT JOIN user_profiles up ON u.id = up.user_id 
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);

    // Validate required fields
    if (empty($first_name)) {
        $errors[] = 'First name is required.';
    }
    if (empty($last_name)) {
        $errors[] = 'Last name is required.';
    }

    if (empty($errors)) {
        // Check if profile data exists
        $sql = "SELECT * FROM user_profiles WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update profile data if it exists
            $sql = "UPDATE user_profiles SET first_name = ?, last_name = ?, phone_number = ?, address = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $first_name, $last_name, $phone_number, $address, $user_id);
        } else {
            // Insert new profile data if none exists
            $sql = "INSERT INTO user_profiles (user_id, first_name, last_name, phone_number, address) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $user_id, $first_name, $last_name, $phone_number, $address);
        }

        if ($stmt->execute()) {
            $success = "Profile information saved successfully!";
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
}
?>

<?php include 'components/header.php'; ?>
<main class="container my-5">
    <div class="profile-page">
        <div class="card shadow-sm p-4">
            <h2 class="mb-4">Profile</h2>

            <!-- Display success or error messages -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Profile Form -->
            <form method="POST" action="profile.php">
                <div class="form-group mb-3">
                    <label for="username">Username</label>
                    <input type="text" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                </div>
                
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                </div>

                <div class="form-group mb-3">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</main>
<?php include 'components/footer.php'; ?>
