<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cuci"; // Changed to match registration page database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses login saat form disubmit
session_start();
$error_message = ""; // Variable untuk pesan kesalahan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mengambil data pengguna berdasarkan username
    $sql = "SELECT * FROM regis WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Memeriksa apakah pengguna ditemukan dan password sesuai
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Login berhasil
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama'] = $row['nama'];
            echo "<script>alert('Login berhasil! Selamat datang, " . htmlspecialchars($row['nama']) . "');</script>";
            header("Location: admin.php");
            exit();
        } else {
            $error_message = "Password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - RANSHOES.ID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .login-form {
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-body {
        padding: 2rem;
    }

    .header-logo {
        height: 40px;
    }

    .form-control {
        padding: 0.75rem 1rem;
        border-radius: 8px;
    }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <img src="\kerjaan\WEBPASEP\vendor\image\title.png" alt="Logo" class="header-logo me-3">
                    <h1 class="h3 mb-0">Welcome Back</h1>
                </div>
                <div class="card login-form">
                    <div class="card-body">
                        <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger text-center mb-3">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                        </form>
                        <div class="mt-3 text-center">
                            <p>Don't have an account? <a href="regis.php">Register here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>