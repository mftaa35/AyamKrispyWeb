<?php
session_start();
include 'config.php'; // gunakan config.php untuk koneksi mysqli $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? '';

    if (!empty($email) && !empty($password) && !empty($role)) {

        if ($role === 'admin') {
            if ($email !== 'admin11@gmail.com') {
                echo "<script>alert('Hanya admin11@gmail.com yang bisa login sebagai admin'); window.location.href = 'login-signup.php';</script>";
                exit;
            }

            $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->bind_param("s", $email);

        } elseif ($role === 'kurir') {
            if (!str_ends_with($email, "@kurir.com")) {
                echo "<script>alert('Hanya email @kurir.com yang dapat login sebagai kurir'); window.location.href = 'login-signup.php';</script>";
                exit;
            }

            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $email);

        } elseif ($role === 'user') {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);

        } else {
            echo "<script>alert('Role tidak dikenali'); window.location.href = 'login-signup.php';</script>";
            exit;
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Untuk user biasa, gunakan password_verify
            if ($role === 'user') {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['users_id']   = $user['users_id'];
                    $_SESSION['nama']       = $user['nama'];
                    $_SESSION['email']      = $user['email'];
                    $_SESSION['no_telepon'] = $user['no_telepon'];
                    $_SESSION['alamat']     = $user['alamat'];
                    $_SESSION['role']       = 'user';

                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "<script>alert('Password salah!'); window.location.href = 'login-signup.php';</script>";
                    exit;
                }
            } else {
                // Admin & kurir: password plaintext (gantilah nanti jadi password_hash)
                if ($password === $user['password']) {
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $role;

                    if ($role === 'admin') {
                        header("Location: admindashboard.php");
                    } else {
                        header("Location: kurirdashboard.php");
                    }
                    exit();
                } else {
                    echo "<script>alert('Password salah!'); window.location.href = 'login-signup.php';</script>";
                    exit;
                }
            }
        } else {
            echo "<script>alert('Email tidak ditemukan!'); window.location.href = 'login-signup.php';</script>";
            exit;
        }

        $stmt->close();
    } else {
        echo "<script>alert('Email, password, dan role harus diisi!'); window.location.href = 'login-signup.php';</script>";
        exit;
    }
} else {
    header("Location: login-signup.php");
    exit;
}

// Fungsi fallback jika PHP < 8
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        return substr($haystack, -strlen($needle)) === $needle;
    }
}
?>
