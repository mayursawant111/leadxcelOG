<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com'; // Use your Hostinger SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'contact@yourdomain.com'; // Your domain email
        $mail->Password = 'your-email-password'; // Your email password
        $mail->SMTPSecure = 'tls'; // or 'ssl'
        $mail->Port = 587; // 465 for SSL

        // Email Headers
        $mail->setFrom('contact@yourdomain.com', 'Your Website');
        $mail->addAddress('contact@yourdomain.com'); // Send to your domain email

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body = "<p><strong>Name:</strong> $name</p>
                       <p><strong>Email:</strong> $email</p>
                       <p><strong>Message:</strong><br>$message</p>";

        $mail->send();
        echo 'Message sent successfully!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
