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
                    // Simpan data penting ke sesi
                    $_SESSION['users_id']   = $user['users_id'];
                    $_SESSION['nama']       = $user['nama'];
                    $_SESSION['email']      = $user['email'];
                    $_SESSION['no_telepon'] = $user['no_telepon'];
                    $_SESSION['alamat']     = $user['alamat'];
                    $_SESSION['role']       = 'user';

                    // Redirect ke halaman shop
                    header("Location: shop.php");
                    exit();
                } else {
                    echo "<script>alert('Password salah!'); window.location.href = 'login-signup.php';</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Email tidak ditemukan!'); window.location.href = 'login-signup.php';</script>";
                exit;
            }

            $stmt->close();
        } else {
            echo "<script>alert('Kesalahan server saat login.'); window.location.href = 'login-signup.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Email dan Password harus diisi!'); window.location.href = 'login-signup.php';</script>";
        exit;
    }
} else {
    header("Location: login-signup.php");
    exit;
}
?>
