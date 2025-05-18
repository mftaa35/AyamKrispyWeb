<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login-signup.php");
    exit;
}

// Ambil data dari session untuk pengisian otomatis
$nama       = $_SESSION['nama'] ?? '';
$email      = $_SESSION['email'] ?? '';
$no_telepon = $_SESSION['no_telepon'] ?? '';
$alamat     = $_SESSION['alamat'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Yasaka Fried Chicken</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .checkout-container {
      max-width: 700px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
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
      background-color: #6d983c;
      border-color: #6d983c;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="checkout-container">
    <h2>Detail Pemesanan</h2>
    <form action="proses_checkout.php" method="POST">
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>" required>
      </div>

      <div class="mb-3">
        <label for="no_telepon" class="form-label">Nomor Telepon</label>
        <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= htmlspecialchars($no_telepon) ?>" required>
      </div>

      <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <input type="text" class="form-control" id="alamat" name="alamat" value="<?= htmlspecialchars($alamat) ?>" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Alamat Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
      </div>

      <div class="mb-3">
        <label for="kota" class="form-label">Kota</label>
        <input type="text" class="form-control" id="kota" name="kota" placeholder="Contoh: Sidoarjo" required>
      </div>

      <div class="mb-3">
        <label for="kode_pos" class="form-label">Kode Pos</label>
        <input type="text" class="form-control" id="kode_pos" name="kode_pos" placeholder="Contoh: 61261" required>
      </div>

      <div class="mb-3">
        <label for="petunjuk_arah" class="form-label">Petunjuk Arah Tambahan (Opsional)</label>
        <textarea class="form-control" id="petunjuk_arah" name="petunjuk_arah" rows="3"></textarea>
      </div>

      <button type="submit" class="btn btn-primary w-100">Kirim Pemesanan</button>
    </form>
  </div>
</div>

</body>
</html>
