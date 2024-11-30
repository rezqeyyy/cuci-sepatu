<?php
// Database connection setup
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cuci';

// Create connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql)) {
    $conn->select_db($database);
} else {
    die("Error creating database: " . $conn->error);
}

// Create orders table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(14) NOT NULL UNIQUE,
    customer_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    delivery ENUM('Yes', 'No') NOT NULL,
    services VARCHAR(255) NOT NULL,
    total_price INT NOT NULL,
    status ENUM('Pending', 'In Progress', 'Completed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}

// Add new handler for delete all completed orders
if (isset($_GET['action']) && $_GET['action'] == 'delete_all_completed') {
    $sql = "DELETE FROM orders WHERE status = 'Completed'";
    if ($conn->query($sql)) {
        header("Location: admin.php?status=Completed&deleted=all");
    } else {
        header("Location: admin.php?status=Completed&error=delete_failed");
    }
    exit();
}

// Handle in progress status updates
if (isset($_GET['action']) && $_GET['action'] == 'progress' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE orders SET status = 'In Progress' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php?status=In Progress");
    exit();
}

// Handle status updates
if (isset($_GET['action']) && $_GET['action'] == 'complete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE orders SET status = 'Completed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php?status=Completed");
    exit();
}

// Handle deletions
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php");
    exit();
}

// Pagination settings
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_condition = '';
if ($search) {
    $search_condition = " AND (
        order_id LIKE '%$search%' OR 
        customer_name LIKE '%$search%' OR 
        phone LIKE '%$search%' OR 
        email LIKE '%$search%'
    )";
}

// Filter orders based on status
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Count total records for pagination
if ($status_filter) {
    $count_sql = "SELECT COUNT(*) as total FROM orders WHERE status = ?$search_condition";
    $stmt = $conn->prepare($count_sql);
    $stmt->bind_param("s", $status_filter);
} else {
    $count_sql = "SELECT COUNT(*) as total FROM orders WHERE status != 'Completed'$search_condition";
    $stmt = $conn->prepare($count_sql);
}

$stmt->execute();
$total_records = $stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $items_per_page);
$stmt->close();

// Get filtered records with pagination
if ($status_filter) {
    $sql = "SELECT * FROM orders WHERE status = ? $search_condition ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $status_filter, $items_per_page, $offset);
} else {
    $sql = "SELECT * FROM orders WHERE status != 'Completed' $search_condition ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $items_per_page, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Management</title>
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

    .search-box {
        max-width: 300px;
    }

    .pagination {
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="card order-card">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <div class="d-flex align-items-center">
                        <img src="\kerjaan\WEBPASEP\vendor\image\title.png" alt="Logo" class="header-logo me-3">
                        <h1 class="h3 mb-0">Order Management</h1>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="window.location.reload()">Refresh</button>
                        <?php if ($status_filter == 'Completed'): ?>
                        <button class="btn btn-danger" onclick="deleteAllCompleted()">Delete All Completed</button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Search Box -->
                <div class="mb-4">
                    <form class="d-flex gap-2" method="GET" action="">
                        <?php if ($status_filter): ?>
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status_filter); ?>">
                        <?php endif; ?>
                        <input type="text" name="search" class="form-control search-box" placeholder="Search orders..."
                            value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <?php if ($search): ?>
                        <a href="?<?php echo $status_filter ? 'status=' . urlencode($status_filter) : ''; ?>"
                            class="btn btn-outline-secondary">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Order Status Tabs -->
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link <?php echo !$status_filter ? 'active' : ''; ?>"
                            href="?<?php echo $search ? 'search=' . urlencode($search) : ''; ?>">All Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $status_filter == 'Pending' ? 'active' : ''; ?>"
                            href="?status=Pending<?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Pending</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $status_filter == 'In Progress' ? 'active' : ''; ?>"
                            href="?status=In Progress<?php echo $search ? '&search=' . urlencode($search) : ''; ?>">In
                            Progress</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $status_filter == 'Completed' ? 'active' : ''; ?>"
                            href="?status=Completed<?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Completed</a>
                    </li>
                </ul>

                <!-- Orders Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Service</th>
                                <th>Delivery</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['services']); ?></td>
                                <td>
                                    <span
                                        class="badge bg-<?php echo $row['delivery'] == 'Yes' ? 'success' : 'danger'; ?>">
                                        <?php echo $row['delivery']; ?>
                                    </span>
                                </td>
                                <td><?php echo number_format($row['total_price']); ?> rb</td>
                                <td>
                                    <span class="badge bg-<?php 
                                                echo $row['status'] == 'Pending' ? 'warning' : 
                                                    ($row['status'] == 'In Progress' ? 'info' : 'success'); 
                                            ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary"
                                            onclick="progressOrder(<?php echo $row['id']; ?>)">
                                            Progress
                                        </button>
                                        <button class="btn btn-success"
                                            onclick="completeOrder(<?php echo $row['id']; ?>)">
                                            Complete
                                        </button>
                                        <button class="btn btn-danger" onclick="deleteOrder(<?php echo $row['id']; ?>)">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No orders found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page-1; ?><?php 
                                    echo $status_filter ? '&status=' . urlencode($status_filter) : ''; 
                                    echo $search ? '&search=' . urlencode($search) : ''; 
                                ?>">Previous</a>
                        </li>

                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?><?php 
                                        echo $status_filter ? '&status=' . urlencode($status_filter) : ''; 
                                        echo $search ? '&search=' . urlencode($search) : ''; 
                                    ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page+1; ?><?php 
                                    echo $status_filter ? '&status=' . urlencode($status_filter) : ''; 
                                    echo $search ? '&search=' . urlencode($search) : ''; 
                                ?>">Next</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
    function progressOrder(id) {
        if (confirm('Are you sure you want to mark this order as in progress?')) {
            window.location.href = `admin.php?action=progress&id=${id}`;
        }
    }

    function completeOrder(id) {
        if (confirm('Are you sure you want to mark this order as completed?')) {
            window.location.href = `admin.php?action=complete&id=${id}`;
        }
    }

    function deleteOrder(id) {
        if (confirm('Are you sure you want to delete this order?')) {
            window.location.href = `admin.php?action=delete&id=${id}`;
        }
    }

    function progressOrder(id) {
        if (confirm('Are you sure you want to mark this order as in progress?')) {
            window.location.href = `admin.php?action=progress&id=${id}`;
        }
    }

    function completeOrder(id) {
        if (confirm('Are you sure you want to mark this order as completed?')) {
            window.location.href = `admin.php?action=complete&id=${id}`;
        }
    }

    function deleteOrder(id) {
        if (confirm('Are you sure you want to delete this order?')) {
            window.location.href = `admin.php?action=delete&id=${id}`;
        }
    }

    function deleteAllCompleted() {
        if (confirm('Are you sure you want to delete all completed orders? This action cannot be undone.')) {
            window.location.href = 'admin.php?action=delete_all_completed';
        }
    }

    // Add success/error message handling
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('deleted') === 'all') {
            alert('All completed orders have been successfully deleted.');
        } else if (urlParams.get('error') === 'delete_failed') {
            alert('Failed to delete completed orders. Please try again.');
        }
    }
    </script>
</body>

</html>