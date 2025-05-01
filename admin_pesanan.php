<?php
// Koneksi ke database
include 'config.php';

// Ambil semua pesanan
$sql = "SELECT * FROM orders2 ORDER BY created_at DESC";
$result = $conn->query($sql);


// Proses update status jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $id = (int) $_POST['id'];
        $newStatus = $_POST['status'];

        // Gunakan prepared statement untuk keamanan
        $stmt = $conn->prepare("UPDATE orders2 SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $id);
        
        if ($stmt->execute()) {
            header("Location: admin_pesanan.php");
            exit;
        } else {
            echo "Gagal memperbarui status: " . $conn->error;
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin - Kelola Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Kelola Pesanan (Admin)</h2>
    <div class="container">
        <div class="dashboard-header">
            <div class="admin-actions">
                <a href="admindashboard.php" class="btn btn-warning"><i class="fas fa-home"></i> Halaman Utama</a>
                <!-- <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a> -->
            </div>
        </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Depan</th>
                    <th>Nama Belakang</th>
                    <th>Alamat</th>
                    <th>Petunjuk Arah</th>
                    <th>Kota</th>
                    <th>Kode Pos</th>
                    <th>No Telepon</th>
                    <th>Pesanan</th>
                    <th>Subtotal</th>
                    <th>Ongkir</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['nama_depan']) ?></td>
                            <td><?= htmlspecialchars($row['nama_belakang']) ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['petunjuk_arah']) ?></td>
                            <td><?= htmlspecialchars($row['kota']) ?></td>
                            <td><?= htmlspecialchars($row['kode_pos']) ?></td>
                            <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                            <td>
                                <?php
                                    $items = json_decode($row['pesanan'], true);
                                    if (is_array($items)) {
                                        foreach ($items as $item) {
                                            echo htmlspecialchars($item['menu_name']) . " (x" . $item['quantity'] . ") - Rp " . number_format($item['menu_price']) . "<br>";
                                        }
                                    } else {
                                        echo 'Format tidak valid';
                                    }
                                ?>
                            </td>
                            <td>Rp <?= number_format($row['subtotal']) ?></td>
                            <td>Rp <?= number_format($row['ongkir']) ?></td>
                            <td>Rp <?= number_format($row['total']) ?></td>
                            <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                            <td>
                                <?php
                                    $status = $row['status'];
                                    switch ($status) {
                                        case 'Menunggu konfirmasi': $badge = 'info'; break;
                                        case 'Pembayaran selesai':
                                        case 'Pesanan Disiapkan': $badge = 'warning'; break;
                                        case 'Pesanan Dikirim': $badge = 'info'; break;
                                        case 'Pesanan Selesai': $badge = 'success'; break;
                                        default: $badge = 'secondary';
                                    }
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <form method="POST" class="d-flex">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <select name="status" class="form-select form-select-sm me-2">
                                        <option <?= $status == 'Menunggu konfirmasi' ? 'selected' : '' ?>>Menunggu konfirmasi</option>
                                        <option <?= $status == 'Pembayaran selesai' ? 'selected' : '' ?>>Pembayaran selesai</option>
                                        <option <?= $status == 'Pesanan Disiapkan' ? 'selected' : '' ?>>Pesanan Disiapkan</option>
                                        <option <?= $status == 'Pesanan Dikirim' ? 'selected' : '' ?>>Pesanan Dikirim</option>
                                        <option <?= $status == 'Pesanan Selesai' ? 'selected' : '' ?>>Pesanan Selesai</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary btn-sm">Ubah</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="16" class="text-center">Tidak ada pesanan ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
                