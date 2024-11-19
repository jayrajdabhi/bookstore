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
$sql = "SELECT u.username, u.email, up.first_name, up.last_name, up.phone_number, up.address, up.profile_picture 
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
    $profile_picture = $user['profile_picture'];

    // Validate required fields
    if (empty($first_name))
        $errors[] = 'First name is required.';
    if (empty($last_name))
        $errors[] = 'Last name is required.';

    // Handle profile picture upload
    if (!empty($_FILES['profile_picture']['tmp_name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir))
            mkdir($target_dir, 0777, true); // Create uploads directory if not exists
        $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['profile_picture']['type'], $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $target_file;
            } else {
                $errors[] = "Failed to upload profile picture.";
            }
        } else {
            $errors[] = "Only JPG, PNG, and GIF formats are allowed.";
        }
    }

    if (empty($errors)) {
        // Check if profile exists
        $sql = "SELECT * FROM user_profiles WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update profile data
            $sql = "UPDATE user_profiles 
                    SET first_name = ?, last_name = ?, phone_number = ?, address = ?, profile_picture = ? 
                    WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $first_name, $last_name, $phone_number, $address, $profile_picture, $user_id);
        } else {
            // Insert new profile data
            $sql = "INSERT INTO user_profiles (user_id, first_name, last_name, phone_number, address, profile_picture) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssss", $user_id, $first_name, $last_name, $phone_number, $address, $profile_picture);
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
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
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Profile Form -->
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" class="form-control"
                            value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control"
                            value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control"
                            value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control"
                            value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control"
                            value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address"
                            class="form-control"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="profile_picture">Profile Picture</label>
                        <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture"
                                class="img-thumbnail mt-2" style="max-width: 150px;">
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </main>
    <?php include 'components/footer.php'; ?>
</body>

</html>