<?php
session_start();
include 'config.php'; // gunakan config.php untuk koneksi mysqli $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (!empty($email) && !empty($password)) {
        // Cek jika email adalah admin
        if ($email === 'admin11@gmail.com') {
            if ($password === '1234567') {
                // Login berhasil sebagai admin
                $_SESSION['username'] = $email;
                $_SESSION['role'] = 'admin';
                header("Location: admindashboard.php");
                exit();
            } else {
                echo "<script>alert('Password admin salah!'); window.location.href = 'login.php';</script>";
                exit;
            }
        } 
        // Cek jika email adalah kurir (domain @kurir.com)
        elseif (str_ends_with($email, "@kurir.com")) {
            // Cek di database apakah kurir ada
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if ($password === $user['password']) {
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = 'kurir';
                    header("Location: kurirdashboard.php");
                    exit();
                } else {
                    echo "<script>alert('Password salah!'); window.location.href = 'login.php';</script>";
                    exit;
                }
            } else {
                // Jika kurir belum ada di database tapi memiliki email @kurir.com, izinkan login 
                // dengan password 1234567
                if ($password === '1234567') {
                    $_SESSION['username'] = $email;
                    $_SESSION['role'] = 'kurir';
                    
                    // Simpan informasi kurir ke database agar login berikutnya bisa dari database
                    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                    $stmt->bind_param("ss", $email, $password);
                    $stmt->execute();
                    $stmt->close();
                    
                    header("Location: kurirdashboard.php");
                    exit();
                } else {
                    echo "<script>alert('Password untuk kurir baru adalah 1234567'); window.location.href = 'login.php';</script>";
                    exit;
                }
            }
            $stmt->close();
        } 
        // Jika bukan admin atau kurir, anggap sebagai user biasa
        else {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                // Untuk user biasa, gunakan password_verify
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
                echo "<script>alert('Email tidak ditemukan!'); window.location.href = 'login-signup.php';</script>";
                exit;
            }
            $stmt->close();
        }
    } else {
        echo "<script>alert('Email dan password harus diisi!'); window.location.href = 'login.php';</script>";
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}

// Fungsi fallback jika PHP < 8
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        return substr($haystack, -strlen($needle)) === $needle;
    }
}
?>
