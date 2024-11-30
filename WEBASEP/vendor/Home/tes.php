<?php
// Load PHPMailer via Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

function sendOrderEmail($orderData) {
    $mail = new PHPMailer(true);

    try {
        // Debugging mode
        $mail->SMTPDebug = 0; // Set to 2 for full debug output during development
        
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com'; // Replace with your Gmail address
        $mail->Password   = 'your-app-password';   // Replace with your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Timeout
        $mail->Timeout = 60;

        // Sender and recipient
        $mail->setFrom($mail->Username, 'Shoes Cleaning Service');
        $mail->addAddress($orderData['email'], $orderData['customer_name']); // Replace with dynamic data
        
        // Email content
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "Order Confirmation - " . $orderData['order_id'];
        
        $message = "
        <html>
        <head>
            <title>Order Confirmation</title>
        </head>
        <body>
            <h2>Thank you for your order, " . htmlspecialchars($orderData['customer_name']) . "!</h2>
            <p>Your order has been successfully received. Here are your order details:</p>
            <table style='border-collapse: collapse; width: 100%; max-width: 600px;'>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Order ID:</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($orderData['order_id']) . "</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Total Price:</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$" . number_format($orderData['total_price'], 2) . "</td>
                </tr>
            </table>
        </body>
        </html>";
        
        $mail->Body = $message;

        // Send email
        $mail->send();
        return "Email has been sent successfully!";
    } catch (Exception $e) {
        return "Email could not be sent. Error: {$mail->ErrorInfo}";
    }
}

// Example data for testing
$orderData = [
    'email' => 'customer@example.com',
    'customer_name' => 'John Doe',
    'order_id' => '12345',
    'total_price' => 100.50
];

// Call the function
echo sendOrderEmail($orderData);
?>