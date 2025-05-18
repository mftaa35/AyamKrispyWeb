<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (nama, no_telepon, alamat, email, password) 
              VALUES ('$nama', '$no_telepon', '$alamat', '$email', '$password')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pengguna berhasil ditambahkan'); window.location='admin_pengguna.php';</script>";
    } else {
        echo "Gagal menambahkan pengguna: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ecf0f1;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #4CAF50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .main-content {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input, label {
            display: block;
            width: 100%;
            margin-bottom: 15px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            border: none;
            padding: 10px;
            color: white;
            cursor: pointer;
            border-radius: 6px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-link {
            margin-top: 15px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="profile">
        <h2>Administrator</h2>
        <p>Admin</p>
    </div>
    <div class="menu">
        <ul>
            <li><a href="admindashboard.php"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="admin_page.php"><i class="fas fa-utensils"></i> Menu</a></li>
            <li><a href="admin_pesanan.php"><i class="fas fa-shopping-cart"></i> Pesanan</a></li>
            <li><a href="admin_pengguna.php"><i class="fas fa-users"></i> Pengguna</a></li>
            <li><a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
</div>

<div class="main-content">
    <div class="form-container">
        <h2>Tambah Pengguna</h2>
        <form method="POST">
            <label>Nama:</label>
            <input type="text" name="nama" required>
            <label>No Telepon:</label>
            <input type="text" name="no_telepon" required>
            <label>Alamat:</label>
            <input type="text" name="alamat" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Simpan">
        </form>
        <a href="admin_pengguna.php" class="back-link">← Kembali ke daftar pengguna</a>
    </div>
</div>

</body>
</html>
