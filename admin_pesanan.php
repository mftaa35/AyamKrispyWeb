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
    <title>Admin - Kelola Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3f51b5;
            --secondary-color: #f5f5f5;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
        }
        
        .dashboard-container {
            background-color: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
        }
        
        .order-card {
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            border: none;
            transition: var(--transition);
        }
        
        .order-card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .dashboard-header h2 {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        /* Status colors */
        .status-menunggu-konfirmasi { background-color: #cfe2ff; color: #0a58ca; }
        .status-pembayaran-selesai { background-color: #d1e7dd; color: #146c43; }
        .status-pesanan-disiapkan { background-color: #fff3cd; color: #997404; }
        .status-pesanan-dikirim { background-color: #d7f0ff; color: #055160; }
        .status-pesanan-selesai { background-color: #d1e7dd; color: #0f5132; }
        
        .table-pesanan {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }
        
        .table-pesanan thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.9rem;
            border: none;
            padding: 1rem 0.75rem;
        }
        
        .table-pesanan tbody tr {
            transition: var(--transition);
        }
        
        .table-pesanan tbody tr:nth-child(even) {
            background-color: var(--secondary-color);
        }
        
        .table-pesanan tbody tr:hover {
            background-color: rgba(63, 81, 181, 0.05);
        }
        
        .table-pesanan td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #e0e0e0;
        }
        
        .status-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
            border-radius: 50px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .action-btn {
            border-radius: 50px;
            font-weight: 500;
            padding: 0.375rem 1rem;
            transition: var(--transition);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #303f9f;
            border-color: #303f9f;
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background-color: #ff9800;
            border-color: #ff9800;
        }
        
        .btn-warning:hover {
            background-color: #e68900;
            border-color: #e68900;
            transform: translateY(-2px);
        }
        
        .form-select {
            border-radius: 50px;
            padding: 0.375rem 2rem 0.375rem 1rem;
        }
        
        .alamat-info {
            max-width: 250px;
        }
        
        .pesanan-item {
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .pesanan-detail {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .pesanan-detail.show {
            max-height: 500px;
        }
        
        .pesanan-toggle {
            cursor: pointer;
            color: var(--primary-color);
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .pesanan-toggle i {
            transition: transform 0.3s ease;
            margin-left: 0.5rem;
        }
        
        .pesanan-toggle.active i {
            transform: rotate(180deg);
        }
        
        /* Mobile card view */
        .card-header-custom {
            padding: 1rem;
            border-bottom: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-info {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
        }
        
        .order-info i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
            margin-top: 4px;
        }
        
        .mobile-update-form {
            display: flex;
            margin-top: 1rem;
            border-top: 1px solid #e0e0e0;
            padding-top: 1rem;
        }
        
        @media (max-width: 768px) {
            .desktop-view {
                display: none;
            }
            .container-fluid {
                padding: 1rem 0.5rem;
            }
            .dashboard-container {
                padding: 1rem;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-view {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2 class="mb-0">Admin - Kelola Pesanan</h2>
            <div class="admin-actions">
                <a href="admindashboard.php" class="btn btn-warning action-btn">
                    <i class="fas fa-home"></i>
                    <span class="d-none d-md-inline-block ms-1">Halaman Utama</span>
                </a>
            </div>
        </div>
        
        <!-- Desktop View (Table) -->
        <div class="desktop-view">
            <table class="table table-pesanan">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="12%">Nama</th>
                        <th width="16%">Alamat</th>
                        <th width="8%">Kontak</th>
                        <th width="20%">Pesanan</th>
                        <th width="8%">Total</th>
                        <th width="8%">Pembayaran</th>
                        <th width="8%">Status</th>
                        <th width="7%">Tanggal</th>
                        <th width="8%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): 
                            // Get status classes
                            $status = $row['status'];
                            $statusSlug = strtolower(str_replace(' ', '-', $status));
                            $statusClass = 'status-' . $statusSlug;
                            
                            switch ($status) {
                                case 'Menunggu konfirmasi': $badge = 'info'; break;
                                case 'Pembayaran selesai': $badge = 'primary'; break;
                                case 'Pesanan Disiapkan': $badge = 'warning'; break;
                                case 'Pesanan Dikirim': $badge = 'info'; break;
                                case 'Pesanan Selesai': $badge = 'success'; break;
                                default: $badge = 'secondary';
                            }
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></td>
                                <td class="alamat-info">
                                    <?= htmlspecialchars($row['alamat']) ?><br>
                                    <?= htmlspecialchars($row['kota'] . ', ' . $row['kode_pos']) ?>
                                    <?php if (!empty($row['petunjuk_arah'])): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($row['petunjuk_arah']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                                <td>
                                    <div class="pesanan-toggle" data-id="<?= $row['id'] ?>">
                                        Lihat detail <i class="fas fa-chevron-down"></i>
                                    </div>
                                    <div class="pesanan-detail" id="detail-<?= $row['id'] ?>">
                                        <?php
                                            $items = json_decode($row['pesanan'], true);
                                            if (is_array($items)) {
                                                foreach ($items as $item) {
                                                    echo '<span class="pesanan-item">' . htmlspecialchars($item['menu_name']) . " (x" . $item['quantity'] . ")</span>";
                                                }
                                            } else {
                                                echo 'Format tidak valid';
                                            }
                                        ?>
                                    </div>
                                </td>
                                <td><strong>Rp <?= number_format($row['total']) ?></strong></td>
                                <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                                <td>
                                    <span class="badge status-badge <?= $statusClass ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <form method="POST" class="d-flex">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <select name="status" class="form-select form-select-sm">
                                            <option <?= $status == 'Menunggu konfirmasi' ? 'selected' : '' ?>>Menunggu konfirmasi</option>
                                            <option <?= $status == 'Pembayaran selesai' ? 'selected' : '' ?>>Pembayaran selesai</option>
                                            <option <?= $status == 'Pesanan Disiapkan' ? 'selected' : '' ?>>Pesanan Disiapkan</option>
                                            <option <?= $status == 'Pesanan Dikirim' ? 'selected' : '' ?>>Pesanan Dikirim</option>
                                            <option <?= $status == 'Pesanan Selesai' ? 'selected' : '' ?>>Pesanan Selesai</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm ms-2">Ubah</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="10" class="text-center py-4">Tidak ada pesanan ditemukan.</td></tr>
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
                    // Get status classes
                    $status = $row['status'];
                    $statusSlug = strtolower(str_replace(' ', '-', $status));
                    $statusClass = 'status-' . $statusSlug;
                    
                    switch ($status) {
                        case 'Menunggu konfirmasi': $badge = 'info'; break;
                        case 'Pembayaran selesai': $badge = 'primary'; break;
                        case 'Pesanan Disiapkan': $badge = 'warning'; break;
                        case 'Pesanan Dikirim': $badge = 'info'; break;
                        case 'Pesanan Selesai': $badge = 'success'; break;
                        default: $badge = 'secondary';
                    }
                ?>
                    <div class="card order-card">
                        <div class="card-header-custom <?= $statusClass ?>">
                            <div>
                                <strong>Pesanan #<?= htmlspecialchars($row['id']) ?></strong>
                            </div>
                            <span class="badge status-badge <?= $statusClass ?>">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5 class="card-title mb-3"><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></h5>
                                    <div class="order-info">
                                        <i class="fas fa-map-marker-alt text-danger"></i> 
                                        <span><?= htmlspecialchars($row['alamat']) ?>, <?= htmlspecialchars($row['kota']) ?>, <?= htmlspecialchars($row['kode_pos']) ?></span>
                                    </div>
                                    <?php if (!empty($row['petunjuk_arah'])): ?>
                                        <div class="order-info small text-muted">
                                            <i class="fas fa-directions"></i> 
                                            <span><?= htmlspecialchars($row['petunjuk_arah']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="order-info">
                                        <i class="fas fa-phone text-primary"></i> 
                                        <span><?= htmlspecialchars($row['no_telepon']) ?></span>
                                    </div>
                                    <div class="order-info">
                                        <i class="fas fa-calendar"></i> 
                                        <span><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></span>
                                    </div>
                                    <div class="order-info">
                                        <i class="fas fa-credit-card"></i> 
                                        <span><?= htmlspecialchars($row['metode_pembayaran']) ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <h6 class="mb-2 fw-bold">Pesanan:</h6>
                            <ul class="list-group mb-3">
                                <?php
                                    $items = json_decode($row['pesanan'], true);
                                    if (is_array($items)) {
                                        foreach ($items as $item) {
                                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>' . htmlspecialchars($item['menu_name']) . '</span>
                                                <span class="badge bg-primary rounded-pill">x' . $item['quantity'] . '</span>
                                            </li>';
                                        }
                                    } else {
                                        echo '<li class="list-group-item">Format tidak valid</li>';
                                    }
                                ?>
                                <li class="list-group-item d-flex justify-content-between fw-bold">
                                    <span>Total</span>
                                    <span>Rp <?= number_format($row['total']) ?></span>
                                </li>
                            </ul>
                            
                            <form method="POST" class="mobile-update-form">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <select name="status" class="form-select me-2">
                                    <option <?= $status == 'Menunggu konfirmasi' ? 'selected' : '' ?>>Menunggu konfirmasi</option>
                                    <option <?= $status == 'Pembayaran selesai' ? 'selected' : '' ?>>Pembayaran selesai</option>
                                    <option <?= $status == 'Pesanan Disiapkan' ? 'selected' : '' ?>>Pesanan Disiapkan</option>
                                    <option <?= $status == 'Pesanan Dikirim' ? 'selected' : '' ?>>Pesanan Dikirim</option>
                                    <option <?= $status == 'Pesanan Selesai' ? 'selected' : '' ?>>Pesanan Selesai</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary action-btn">Ubah</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">Tidak ada pesanan ditemukan.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Collapsible pesanan detail
    document.addEventListener('DOMContentLoaded', function() {
        const toggles = document.querySelectorAll('.pesanan-toggle');
        
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const detail = document.getElementById('detail-' + id);
                
                if (detail.classList.contains('show')) {
                    detail.classList.remove('show');
                    this.classList.remove('active');
                } else {
                    detail.classList.add('show');
                    this.classList.add('active');
                }
            });
        });
    });
</script>
</body>
</html>
