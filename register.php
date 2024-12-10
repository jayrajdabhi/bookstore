<?php
// Start session
session_start();

include 'config/database.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'customer'; // Default role

    // Validations
    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        // Check if username or email already exists
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = 'Username or email already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert user into the database
            $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
        }
    }
}
?>

<?php include 'components/header.php'; ?>
<main id="login_main">
    <div class="login-page">
        <div class="login-container mt-5 mb-5">
            <h2>Register</h2>

            <!-- Display errors if any -->
            <?php if (!empty($errors)): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <!-- Registration Form -->
            <form name="registrationForm" method="POST" action="register.php" onsubmit="return validateForm();">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                <button type="submit" class="login-btn">Register</button>
            </form>

            <!-- Login Link -->
            <div class="signup-link">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</main>
<?php include 'components/footer.php'; ?>

<script>
    function validateForm() {
        let password = document.forms["registrationForm"]["password"].value;
        let confirm_password = document.forms["registrationForm"]["confirm_password"].value;

        if (password.length < 6) {
            alert("Password must be at least 6 characters long.");
            return false;
        }
        if (password !== confirm_password) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
    }
</script>