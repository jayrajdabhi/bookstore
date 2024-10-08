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
    <h2 class="text-center">Contact Us</h2>

    <?php
    // Display success or error message
    if (!empty($error_message)) {
        echo '<p class="text-danger text-center">' . $error_message . '</p>';
    } elseif (!empty($success_message)) {
        echo '<p class="text-success text-center">' . $success_message . '</p>';
    }
    ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="" method="post" onsubmit="return validateForm();" class="border p-4 rounded bg-light">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Subject:</label>
                    <input type="text" id="subject" name="subject" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Message:</label>
                    <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
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