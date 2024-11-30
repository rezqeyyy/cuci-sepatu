<?php
// First, make sure to install PHPMailer via Composer or include it manually
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

// Database connection setup
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cuci';

// Create database connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    // Select the database after creating it
    $conn->select_db($database);
    
    // Create orders table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id VARCHAR(10) NOT NULL,
        customer_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address TEXT NOT NULL,
        delivery ENUM('Yes', 'No') NOT NULL,
        services VARCHAR(255) NOT NULL,
        total_price INT NOT NULL,
        status ENUM('Pending', 'In Progress', 'Completed') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$conn->query($sql)) {
        die("Error creating table: " . $conn->error);
    }
} else {
    die("Error creating database: " . $conn->error);
}

function sendOrderEmail($orderData) {
    $mail = new PHPMailer(true);

    try {
        // Enable SMTP debug mode
        $mail->SMTPDebug = 2;
        
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'abrarmanusia@gmail.com'; // Your Gmail address
        $mail->Password   = 'safs bhkj wnyu wdut';    // Your Gmail App Password (16 characters)
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Set timeout
        $mail->Timeout = 60;
        $mail->SMTPKeepAlive = true;

        // Recipients
        $mail->setFrom($mail->Username, 'Shoes Cleaning Service'); // Use same email as Username
        $mail->addAddress($orderData['email'], $orderData['customer_name']);

        // Content
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
        
        $result = $mail->send();
        error_log("Email sent successfully to: " . $orderData['email']);
        return $result;

    } catch (Exception $e) {
        error_log("SMTP Error: " . $mail->ErrorInfo);
        error_log("Detailed error: " . $e->getMessage());
        return false;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = 'ORD' . date('Ymd') . rand(100, 999);
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $delivery = $_POST['delivery'];
    $services = isset($_POST['colorRestoration']) ? 'Color Restoration' : '';
    $total_price = isset($_POST['colorRestoration']) ? 50 : 0;
    
    $sql = "INSERT INTO orders (order_id, customer_name, email, phone, address, delivery, services, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $order_id, $customer_name, $email, $phone, $address, $delivery, $services, $total_price);
    
    if ($stmt->execute()) {
        // Prepare order data for email
        $orderData = array(
            'order_id' => $order_id,
            'customer_name' => $customer_name,
            'email' => $email,
            'services' => $services,
            'delivery' => $delivery,
            'total_price' => $total_price
        );

        // Send email using PHPMailer
        if(sendOrderEmail($orderData)) {
            echo "<script>
                alert('Order berhasil dibuat dengan ID: $order_id\\nEmail konfirmasi telah dikirim ke $email');
                window.location.href = 'home.html';
            </script>";
        } else {
            echo "<script>
                alert('Order berhasil dibuat dengan ID: $order_id\\nNamun email konfirmasi gagal dikirim');
                window.location.href = 'home.html';
            </script>";
        }
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Shoes Cleaning</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="\kerjaan\WEBPASEP\vendor\image\title.png">
    <style>
    body {
        background-color: #f8f9fa;
        padding: 20px;
    }

    .order-card {
        margin: 30px auto;
        border-radius: 15px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .header-logo {
        width: 50px;
        height: auto;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="card order-card">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                    <img src="\kerjaan\WEBPASEP\vendor\image\title.png" alt="Logo" class="header-logo me-3">
                    <h1 class="h3 mb-0">Order Shoes Cleaning</h1>
                </div>

                <form method="POST" action="" id="orderForm">
                    <!-- Customer Information -->
                    <div class="mb-4">
                        <h2 class="h5 mb-3">Customer Information</h2>
                        <div class="mb-3">
                            <input type="text" name="customer_name" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="tel" name="phone" class="form-control" placeholder="Phone Number" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="address" class="form-control" placeholder="Address" rows="3"
                                required></textarea>
                        </div>
                    </div>

                    <!-- Delivery Section -->
                    <div class="delivery-option mb-4">
                        <h3 class="h5 mb-3">Ingin Di antar?</h3>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="delivery" value="Yes" id="delivery-yes"
                                required>
                            <label class="btn btn-outline-primary" for="delivery-yes">Iya</label>

                            <input type="radio" class="btn-check" name="delivery" value="No" id="delivery-no">
                            <label class="btn btn-outline-primary" for="delivery-no">Tidak</label>
                        </div>
                    </div>

                    <!-- Service Section -->
                    <div class="service-option mb-4">
                        <h3 class="h5 mb-3">Shoes Laundry</h3>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="colorRestoration"
                                id="colorRestoration" value="1">
                            <label class="form-check-label" for="colorRestoration">
                                Color Restoration (50 rb)
                            </label>
                        </div>
                    </div>

                    <!-- Total Section -->
                    <div class="total-section mb-4">
                        <h3 class="h5 text-end mb-0">
                            Total: <span id="total">0</span> rb
                        </h3>
                    </div>

                    <!-- Order Button -->
                    <button type="submit" class="btn btn-primary w-100 mb-3">Order Now</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('colorRestoration').addEventListener('change', function() {
        document.getElementById('total').textContent = this.checked ? '50' : '0';
    });
    </script>
</body>

</html>