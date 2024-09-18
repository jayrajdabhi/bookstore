<?php
// Start session
session_start();


$conn = new mysqli('localhost', 'root', '', 'bookstore');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}


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
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into the database
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Registration</title>
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
</head>

<body>
    <h2>Register</h2>

    <?php
    if (!empty($errors)) {
        echo '<ul>';
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo '</ul>';
    }
    ?>

    <form name="registrationForm" method="POST" action="register.php" onsubmit="return validateForm();">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>
        <input type="submit" value="Register">
    </form>
</body>

</html>
