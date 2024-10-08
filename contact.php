<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'bookstore';

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';
$success_message = '';

// Server-side validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $messageText = trim($_POST['message']);

    // Validations
    if (empty($name)) {
        $error_message = "Please enter your name.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } elseif (empty($subject)) {
        $error_message = "Please enter the subject.";
    } elseif (empty($messageText)) {
        $error_message = "Please enter your message.";
    } else {
        // Function to insert data into database
        $name = $conn->real_escape_string($name);
        $email = $conn->real_escape_string($email);
        $subject = $conn->real_escape_string($subject);
        $messageText = $conn->real_escape_string($messageText);

        $sql = "INSERT INTO contact_us (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$messageText')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Thank you for contacting us! We will get back to you shortly.";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>

<?php include 'components/header.php'; ?>
<main class="container my-5">
    <h2>Contact Us</h2>

    <?php
    // Display success or error message
    if (!empty($error_message)) {
        echo '<p style="color:red;">' . $error_message . '</p>';
    } elseif (!empty($success_message)) {
        echo '<p style="color:green;">' . $success_message . '</p>';
    }
    ?>

    <form action="" method="post" onsubmit="return validateForm();">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="subject">Subject:</label><br>
        <input type="text" id="subject" name="subject" required><br><br>

        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="4" required></textarea><br><br>

        <input type="submit" value="Submit">
    </form>
</main>
<script>
    // JavaScript validation (client-side)
    function validateForm() {
        var name = document.getElementById("name").value.trim();
        var email = document.getElementById("email").value.trim();
        var subject = document.getElementById("subject").value.trim();
        var message = document.getElementById("message").value.trim();

        if (name == "") {
            alert("Please enter your name.");
            return false;
        }
        if (email == "") {
            alert("Please enter your email.");
            return false;
        }
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            return false;
        }
        if (subject == "") {
            alert("Please enter the subject.");
            return false;
        }
        if (message == "") {
            alert("Please enter your message.");
            return false;
        }
        return true;
    }
</script>
<?php include 'components/footer.php'; ?>