<?php
// Start session
session_start();

// Redirect to index.php if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Include database connection
$conn = new mysqli('localhost', 'root', '', 'bookstore');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle form submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validations
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }

    // If no errors, proceed with login
    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Check if user exists and password matches
        if ($user && password_verify($password, $user['password'])) {
            // Check if user is an admin
            if ($user['role'] == 'admin') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                header("Location: ./index.php");
                exit();
            } else {
                $errors[] = 'Invalid credentials for admin!';
            }
        } else {
            $errors[] = 'Invalid email or password!';
        }
    }
}
?>

<?php include 'header.php'; ?>
<main id="login_main">
    <div class="login-page">
        <div class="login-container">
            <h2>Admin Login</h2>

            <!-- Display errors if any -->
            <?php if (!empty($errors)): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="login.php">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <!-- Forgot Password Link -->
                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>

            <!-- Sign Up Link -->
            <div class="signup-link">
                <p>Don't have an account? <a href="../register.php">Sign Up</a></p>
            </div>
        </div>
    </div>
</main>
<?php include './footer.php'; ?>