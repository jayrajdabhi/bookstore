<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';


function sendPasswordResetEmail($recipientEmail, $resetLink) {
    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'prathamvelawala@gmail.com'; // Your Gmail email address
        $mail->Password = 'nozyohsnkdruowxj'; // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email settings
        $mail->setFrom('prathamvelawala@gmail.com', 'Book store');
        $mail->addAddress($recipientEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "Click the following link to reset your password: <a href='$resetLink'>$resetLink</a>";

        $mail->send();
        return "A password reset link has been sent to your email.";
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
