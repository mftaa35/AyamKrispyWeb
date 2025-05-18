<?php
session_start();
include 'koneksi.php';

// Pastikan user login
if (!isset($_SESSION['users_id'])) {
    echo "Silakan login terlebih dahulu.";
    exit;
}

$users_id = $_SESSION['users_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE users_id = '$users_id'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .container {
            max-width: 500px;
            margin: auto;
        }
        h2 {
            text-align: center;
        }
        form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Detail Pemesanan</h2>
    <form action="proses_pesanan.php" method="POST">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" readonly>

        <label>No Telepon</label>
        <input type="text" name="no_telepon" value="<?= htmlspecialchars($user['no_telepon']); ?>" readonly>

        <label>Alamat</label>
        <textarea name="alamat" rows="3" readonly><?= htmlspecialchars($user['alamat']); ?></textarea>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" readonly>

        <label>Catatan Tambahan</label>
        <textarea name="catatan" rows="3" placeholder="Contoh: Tolong kirim sebelum jam 5 sore."></textarea>

        <input type="submit" value="Lanjutkan Pemesanan">
    </form>
</div>
</body>
</html>
