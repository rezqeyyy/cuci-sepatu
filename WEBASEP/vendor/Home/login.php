<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cuci"; // Ganti dengan nama database Anda

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
            echo "Login berhasil! Selamat datang, " . htmlspecialchars($row['nama']);
            header("Location: UTS.php"); // Arahkan ke UTS.php setelah login
            exit();
        } else {
            $error_message = "Password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RANSHOES.ID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .login-card {
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-body {
        padding: 2rem;
    }

    .header-logo {
        height: 40px;
    }
    </style>
</head>

<body class="bg-light">
    <div class="container text-center py-5">
        <div class="d-flex align-items-center justify-content-center mb-4">
            <img src="\kerjaan\WEBPASEP\vendor\image\title.png" alt="Logo" class="header-logo me-3">
            <h1 class="h3 mb-0">WELCOME TO RANSHOES.ID</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card login-card">
                    <div class="card-body">
                        <div class="mb-4">
                            <a href="login_pw.php" class="btn btn-primary btn-lg w-100 mb-3">Login Admin</a>
                            <a href="home.html" class="btn btn-outline-primary btn-lg w-100">Login User</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>