<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        // Ambil data user berdasarkan email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verifikasi password
                if (password_verify($password, $user['password'])) {
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = 'user';

                    // Redirect ke halaman shop setelah login sukses
                    header("Location: shop.php");
                    exit();
                } else {
                    // Password salah
                    echo "<script>alert('Password salah!'); window.location.href = 'login-signup.php';</script>";
                    exit;
                }
            } else {
                // Email tidak ditemukan
                echo "<script>alert('Email tidak ditemukan!'); window.location.href = 'login-signup.php';</script>";
                exit;
            }

            $stmt->close();
        } else {
            echo "<script>alert('Kesalahan sistem. Silakan coba lagi.'); window.location.href = 'login-signup.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Email dan Password harus diisi!'); window.location.href = 'login-signup.php';</script>";
        exit;
    }
} else {
    // Jika akses langsung ke file login.php tanpa POST
    header("Location: login-signup.php");
    exit;
}
?>
