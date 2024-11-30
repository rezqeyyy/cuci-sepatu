<?php
// First, make sure to install PHPMailer via Composer or include it manually
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // If using Composer
// Or include manually:
// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';

// ... (previous database connection code remains the same)

function sendOrderConfirmationEmail($orderData) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Change this to your SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com'; // Change to your email
        $mail->Password   = 'your-app-password'; // Change to your password/app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('noreply@shoescleaning.com', 'Shoes Cleaning Service');
        $mail->addAddress($orderData['email'], $orderData['customer_name']);

        // Content
        $mail->isHTML(true);
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
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Services:</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($orderData['services']) . "</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Delivery:</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($orderData['delivery']) . "</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Total Price:</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Rp " . number_format($orderData['total_price'] * 1000, 0, ',', '.') . "</td>
                </tr>
            </table>
            
            <p>We will process your order as soon as possible. You can track your order status using your Order ID.</p>
            
            <p>If you have any questions, please contact us at:</p>
            <p>Phone: +62 123456789<br>
            Email: support@shoescleaning.com</p>
            
            <p>Thank you for choosing our service!</p>
        </body>
        </html>
        ";

        $mail->Body = $message;

        return $mail->send();
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

// ... (rest of the code remains the same)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Email List</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
        padding: 20px;
    }

    .email-card {
        margin: 30px auto;
        border-radius: 15px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .copy-btn {
        cursor: pointer;
    }

    .copy-btn:hover {
        color: #0d6efd;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="card email-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Customer Email List</h3>
                <button class="btn btn-primary" onclick="copyAllEmails()">Copy All Emails</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                $i = 1;
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $i . "</td>";
                                    echo "<td>" . htmlspecialchars($row["customer_name"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                    echo "<td>
                                            <i class='copy-btn bi bi-clipboard' 
                                               onclick='copyEmail(\"" . htmlspecialchars($row["email"]) . "\")' 
                                               title='Copy email'>
                                               📋
                                            </i>
                                         </td>";
                                    echo "</tr>";
                                    $i++;
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No emails found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
    function copyEmail(email) {
        navigator.clipboard.writeText(email).then(() => {
            alert('Email copied to clipboard: ' + email);
        }).catch(err => {
            console.error('Failed to copy email: ', err);
        });
    }

    function copyAllEmails() {
        const rows = document.querySelectorAll('table tbody tr');
        let emails = [];

        rows.forEach(row => {
            const emailCell = row.querySelector('td:nth-child(3)');
            if (emailCell) {
                emails.push(emailCell.textContent.trim());
            }
        });

        if (emails.length > 0) {
            navigator.clipboard.writeText(emails.join('\n')).then(() => {
                alert('All emails copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy emails: ', err);
            });
        }
    }
    </script>
</body>

</html>