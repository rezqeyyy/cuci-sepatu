<?php
// Add these at the top of your email.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // You'll need to install PHPMailer via Composer

function sendCompletionEmail($to_email, $customer_name, $order_id, $services) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Use Gmail SMTP or any other provider
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Your Gmail address
        $mail->Password = 'your-app-specific-password'; // Your Gmail app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Your Shoe Cleaning Service');
        $mail->addAddress($to_email, $customer_name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Your Order #$order_id is Complete!";
        $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { padding: 20px; }
                .header { background-color: #f8f9fa; padding: 20px; }
                .content { padding: 20px; }
                .footer { text-align: center; padding: 20px; background-color: #f8f9fa; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Order Completion Notification</h2>
                </div>
                <div class='content'>
                    <p>Dear $customer_name,</p>
                    <p>We're happy to inform you that your order #$order_id has been completed!</p>
                    <p><strong>Order Details:</strong></p>
                    <ul>
                        <li>Order ID: $order_id</li>
                        <li>Service: $services</li>
                    </ul>
                    <p>You can pick up your shoes at our store during business hours.</p>
                    <p>Thank you for choosing our service!</p>
                </div>
                <div class='footer'>
                    <p>If you have any questions, please contact us.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}