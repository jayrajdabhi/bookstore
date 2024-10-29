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
            <form id="contactForm" action="" method="post" onsubmit="return validateForm();" class="border p-4 rounded bg-light shadow-lg hover-shadow">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                    <div class="invalid-feedback">Please enter your name.</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Subject:</label>
                    <input type="text" id="subject" name="subject" class="form-control" required>
                    <div class="invalid-feedback">Please enter the subject.</div>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Message:</label>
                    <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
                    <div class="invalid-feedback">Please enter your message.</div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Brantford Map Section -->
    <div class="row my-5">
        <div class="col-md-12">
            <h3 class="text-center">Find Us Here</h3>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2850.0470460163503!2d-80.26722758450003!3d43.20005227913987!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x882b48a701ed7b91%3A0x1f0a4bbf53a1fdf8!2sBrantford%2C%20ON%2C%20Canada!5e0!3m2!1sen!2sus!4v1600000000000!5m2!1sen!2sus"
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>

    <!-- Feedback Section -->
    <div class="row my-5">
        <div class="col-md-12">
            <h3 class="text-center">Feedback</h3>
            <form action="" method="post" class="border p-4 rounded bg-light shadow-lg hover-shadow">
                <div class="mb-3">
                    <label for="feedback" class="form-label">Your Feedback:</label>
                    <textarea id="feedback" name="feedback" rows="4" class="form-control" required></textarea>
                </div>
                <div class="text-center"> <!-- Centering the button -->
                    <button type="submit" class="btn btn-primary">Send Feedback</button>
                </div>
            </form>
        </div>
    </div>

</main>
<script>
    // JavaScript validation (client-side)
    function validateForm() {
        // Clear previous validation states
        var form = document.getElementById('contactForm');
        var isValid = true;

        // Validate Name
        var name = document.getElementById("name");
        if (name.value.trim() === "") {
            name.classList.add("is-invalid");
            isValid = false;
        } else {
            name.classList.remove("is-invalid");
        }

        // Validate Email
        var email = document.getElementById("email");
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (email.value.trim() === "" || !emailPattern.test(email.value.trim())) {
            email.classList.add("is-invalid");
            isValid = false;
        } else {
            email.classList.remove("is-invalid");
        }

        // Validate Subject
        var subject = document.getElementById("subject");
        if (subject.value.trim() === "") {
            subject.classList.add("is-invalid");
            isValid = false;
        } else {
            subject.classList.remove("is-invalid");
        }

        // Validate Message
        var message = document.getElementById("message");
        if (message.value.trim() === "") {
            message.classList.add("is-invalid");
            isValid = false;
        } else {
            message.classList.remove("is-invalid");
        }

        return isValid; // Return false to prevent form submission if invalid
    }
</script>
<style>
    .hover-shadow {
        transition: box-shadow 0.3s;
    }

    .hover-shadow:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }
</style>
<?php include 'components/footer.php'; ?>
