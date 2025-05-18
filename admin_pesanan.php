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

        $stmt = $conn->prepare("UPDATE orders2 SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Gagal memperbarui status: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admin - Kelola Pesanan</h2>
        <a href="admindashboard.php" class="btn btn-warning">
            <i class="fas fa-home"></i> <span class="ms-1">Halaman Utama</span>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-nowrap">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Pesanan</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></td>
                        <td>
                            <?= htmlspecialchars($row['alamat']) ?><br>
                            <?= htmlspecialchars($row['kota'] . ', ' . $row['kode_pos']) ?>
                            <?php if (!empty($row['petunjuk_arah'])): ?>
                                <br><small class="text-muted">(<?= htmlspecialchars($row['petunjuk_arah']) ?>)</small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                        <td>
                            <?php
                                $items = json_decode($row['pesanan'], true);
                                if (is_array($items)) {
                                    foreach ($items as $item) {
                                        echo htmlspecialchars($item['menu_name']) . " (x" . $item['quantity'] . ")<br>";
                                    }
                                } else {
                                    echo 'Format tidak valid';
                                }
                            ?>
                        </td>
                        <td class="text-end">Rp <?= number_format($row['total']) ?></td>
                        <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                        <td class="text-center">
                            <?php
                                $status = $row['status'];
                                $badge = match($status) {
                                    'Menunggu konfirmasi' => 'info',
                                    'Pembayaran selesai' => 'primary',
                                    'Pesanan Disiapkan' => 'warning',
                                    'Pesanan Dikirim' => 'info',
                                    'Pesanan Selesai' => 'success',
                                    default => 'secondary'
                                };
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <select name="status" class="form-select form-select-sm me-2">
                                    <?php
                                    $options = [
                                        'Menunggu konfirmasi',
                                        'Pembayaran selesai',
                                        'Pesanan Disiapkan',
                                        'Pesanan Dikirim',
                                        'Pesanan Selesai'
                                    ];
                                    foreach ($options as $opt) {
                                        echo '<option value="' . $opt . '"' . ($status == $opt ? ' selected' : '') . '>' . $opt . '</option>';
                                    }
                                    ?>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary btn-sm">Ubah</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">Tidak ada pesanan ditemukan.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
