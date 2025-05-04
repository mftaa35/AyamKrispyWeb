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
    <title>Kurir - Kelola Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .order-card {
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        .order-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .bg-pending { background-color: #cfe2ff; }
        .bg-paid { background-color: #d1e7dd; }
        .bg-prepared { background-color: #fff3cd; }
        .bg-shipped { background-color: #d1e7dd; }
        .bg-completed { background-color: #d1e7dd; }
        
        .order-info {
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .desktop-view {
                display: none;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-view {
                display: none;
            }
        }
        
        .status-badge {
            font-size: 0.9rem;
            padding: 0.35rem 0.65rem;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="dashboard-header">
        <h2 class="mb-0">Kurir - Kelola Pesanan</h2>
        <div class="admin-actions">
            <a href="kurirdashboard.php" class="btn btn-warning">
                <i class="fas fa-home"></i>
                <span class="d-none d-md-inline-block ms-1">Halaman Utama</span>
            </a>
        </div>
    </div>
    
    <!-- Desktop View (Table) -->
    <div class="desktop-view table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
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
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></td>
                            <td>
                                <?= htmlspecialchars($row['alamat']) ?><br>
                                <?= htmlspecialchars($row['kota'] . ', ' . $row['kode_pos']) ?>
                                <?php if (!empty($row['petunjuk_arah'])): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($row['petunjuk_arah']) ?></small>
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
                            <td>Rp <?= number_format($row['total']) ?></td>
                            <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                            <td>
                                <?php
                                    $status = $row['status'];
                                    switch ($status) {
                                        case 'Menunggu konfirmasi': $badge = 'info'; break;
                                        case 'Pembayaran selesai': $badge = 'primary'; break;
                                        case 'Pesanan Disiapkan': $badge = 'warning'; break;
                                        case 'Pesanan Dikirim': $badge = 'info'; break;
                                        case 'Pesanan Selesai': $badge = 'success'; break;
                                        default: $badge = 'secondary';
                                    }
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
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
                    <tr><td colspan="10" class="text-center">Tidak ada pesanan ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Mobile View (Cards) -->
    <div class="mobile-view">
        <?php 
        // Reset pointer to the beginning
        if ($result) {
            $result->data_seek(0);
        }
        ?>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): 
                // Get class based on status
                $status = $row['status'];
                switch ($status) {
                    case 'Menunggu konfirmasi': $cardClass = 'bg-pending'; $badge = 'info'; break;
                    case 'Pembayaran selesai': $cardClass = 'bg-paid'; $badge = 'primary'; break;
                    case 'Pesanan Disiapkan': $cardClass = 'bg-prepared'; $badge = 'warning'; break;
                    case 'Pesanan Dikirim': $cardClass = 'bg-shipped'; $badge = 'info'; break;
                    case 'Pesanan Selesai': $cardClass = 'bg-completed'; $badge = 'success'; break;
                    default: $cardClass = ''; $badge = 'secondary';
                }
            ?>
                <div class="card order-card">
                    <div class="card-header d-flex justify-content-between align-items-center <?= $cardClass ?>">
                        <div>
                            <strong>Pesanan #<?= htmlspecialchars($row['id']) ?></strong>
                        </div>
                        <span class="badge bg-<?= $badge ?> status-badge"><?= htmlspecialchars($status) ?></span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <h5 class="card-title"><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></h5>
                                <div class="order-info">
                                    <i class="fas fa-map-marker-alt text-danger"></i> 
                                    <?= htmlspecialchars($row['alamat']) ?>, <?= htmlspecialchars($row['kota']) ?>, <?= htmlspecialchars($row['kode_pos']) ?>
                                </div>
                                <?php if (!empty($row['petunjuk_arah'])): ?>
                                    <div class="order-info small text-muted">
                                        <i class="fas fa-directions"></i> <?= htmlspecialchars($row['petunjuk_arah']) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="order-info">
                                    <i class="fas fa-phone text-primary"></i> <?= htmlspecialchars($row['no_telepon']) ?>
                                </div>
                                <div class="order-info">
                                    <i class="fas fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?>
                                </div>
                                <div class="order-info">
                                    <i class="fas fa-credit-card"></i> <?= htmlspecialchars($row['metode_pembayaran']) ?>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="mb-2">Pesanan:</h6>
                        <ul class="list-group mb-3">
                            <?php
                                $items = json_decode($row['pesanan'], true);
                                if (is_array($items)) {
                                    foreach ($items as $item) {
                                        echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                                            ' . htmlspecialchars($item['menu_name']) . '
                                            <span class="badge bg-primary rounded-pill">x' . $item['quantity'] . '</span>
                                        </li>';
                                    }
                                } else {
                                    echo '<li class="list-group-item">Format tidak valid</li>';
                                }
                            ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Total</strong>
                                <strong>Rp <?= number_format($row['total']) ?></strong>
                            </li>
                        </ul>
                        
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <select name="status" class="form-select me-2">
                                <option <?= $status == 'Menunggu konfirmasi' ? 'selected' : '' ?>>Menunggu konfirmasi</option>
                                <option <?= $status == 'Pembayaran selesai' ? 'selected' : '' ?>>Pembayaran selesai</option>
                                <option <?= $status == 'Pesanan Disiapkan' ? 'selected' : '' ?>>Pesanan Disiapkan</option>
                                <option <?= $status == 'Pesanan Dikirim' ? 'selected' : '' ?>>Pesanan Dikirim</option>
                                <option <?= $status == 'Pesanan Selesai' ? 'selected' : '' ?>>Pesanan Selesai</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-primary">Ubah</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">Tidak ada pesanan ditemukan.</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
