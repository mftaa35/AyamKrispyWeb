<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login-signup.php");
    exit;
}

// Ambil data dari session
$nama = $_SESSION['nama'] ?? '';
$email = $_SESSION['email'] ?? '';
$no_telepon = $_SESSION['no_telepon'] ?? '';
$alamat = $_SESSION['alamat'] ?? '';

// Proses pengiriman form (jika ada)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kota = $_POST['kota'] ?? '';
    $kode_pos = $_POST['kode_pos'] ?? '';
    $petunjuk_arah = $_POST['petunjuk_arah'] ?? '';

    // Lanjutkan dengan proses simpan pemesanan, validasi, dsb...
    echo "<script>alert('Pemesanan berhasil disubmit!');</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Yasaka Fried Chicken</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: #82ae46;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-primary {
            background-color: #82ae46;
            border-color: #82ae46;
        }
        .btn-primary:hover {
            background-color: #6e9939;
            border-color: #6e9939;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Pemesanan</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="no_telepon" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= htmlspecialchars($no_telepon) ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" value="<?= htmlspecialchars($alamat) ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="kota" class="form-label">Kota</label>
            <input type="text" class="form-control" id="kota" name="kota" required>
        </div>

        <div class="mb-3">
            <label for="kode_pos" class="form-label">Kode Pos</label>
            <input type="text" class="form-control" id="kode_pos" name="kode_pos" required>
        </div>

        <div class="mb-3">
            <label for="petunjuk_arah" class="form-label">Petunjuk Arah Tambahan (Opsional)</label>
            <textarea class="form-control" id="petunjuk_arah" name="petunjuk_arah" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Kirim Pemesanan</button>
    </form>
</div>

</body>
</html>
