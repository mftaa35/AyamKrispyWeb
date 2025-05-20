<?php
session_start();
include 'config.php';

// Set timezone to Asia/Jakarta (or your local timezone)
date_default_timezone_set('Asia/Jakarta');

// Default filter jika tidak ada yang dipilih
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Query SQL dengan filter
if ($status_filter == 'all') {
        $sql = "SELECT * FROM orders2 ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM orders2 WHERE status = '$status_filter'";
}

$result = $conn->query($sql);

// Function to format datetime from database
function formatDateTime($datetime) {
    // Assuming the datetime from database is in UTC or server timezone
    // Convert to desired format with proper timezone
    $date = new DateTime($datetime);
    return $date->format('d-m-Y H:i:s');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Yasaka Fried Chicken - Status Pesanan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- CSS Links -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Mobile responsive styling */
        @media (max-width: 767px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .mobile-table {
                font-size: 0.85rem;
            }
            
            .mobile-card {
                margin-bottom: 1rem;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .mobile-card .card-header {
                font-weight: bold;
                background-color: #f8f9fa;
            }
            
            .filter-section {
                margin-bottom: 20px;
            }
            
            .badge {
                display: inline-block;
                padding: 0.35em 0.65em;
                font-size: 0.75em;
                font-weight: 700;
                line-height: 1;
                color: #fff;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                border-radius: 0.25em;
            }
            
            .bg-info {
                background-color: #17a2b8!important;
            }
            
            .bg-warning {
                background-color: #ffc107!important;
                color: #212529!important;
            }
            
            .bg-success {
                background-color: #28a745!important;
            }
            
            .bg-secondary {
                background-color: #6c757d!important;
            }
            
            .bg-pay-done {
                background-color: #6610f2!important;
            }

            body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .pesanan-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
       .button-bar {
    display: flex;
    justify-content: space-between; /* Menyebarkan elemen secara merata */
    align-items: center;
    flex-wrap: wrap; /* Agar tetap responsif di berbagai ukuran */
    gap: 10px;
    margin-bottom: 20px;
}

.filter-btn, .back-btn {
    background-color: #28a745;
    color: white;
    padding: 10px 16px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
    min-width: 120px; /* Menjaga ukuran minimum agar tidak terlalu kecil */
}

.filter-btn:hover, .back-btn:hover {
    background-color: #218838;
}

.filter-btn:active, .back-btn:active {
    background-color: #1e7e34;
}

/* Responsif untuk layar kecil */
@media (max-width: 767px) {
    .button-bar {
        flex-direction: column;
        align-items: center;
    }

    .filter-btn, .back-btn {
        width: 100%;
        padding: 12px;
    }
}
    </style>
</head>

<body class="goto-here">
    <!-- Top Bar -->
    <div class="py-1 bg-primary">
        <div class="container">
            <div class="row no-gutters d-flex align-items-start align-items-center px-md-0">
                <div class="col-lg-12 d-block">
                    <div class="row d-flex">
                        <div class="col-md pr-4 d-flex topper align-items-center">
                            <div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-phone2"></span></div>
                            <span class="text">0895411124567</span>
                        </div>
                        <div class="col-md pr-4 d-flex topper align-items-center">
                            <div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div>
                            <span class="text">yasakkakemuning@email.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.html">Yasaka Fried Chicken</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav">
                <span class="oi oi-menu"></span> Menu
            </button>

            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="dashboard.php" class="nav-link">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shop</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="shop.php">Menu Ayam</a>
                            <a class="dropdown-item" href="product-single.php">Deskripsi Menu</a>
                            <a class="dropdown-item" href="cart.php">Detail Keranjang</a>
                            <a class="dropdown-item" href="checkout.php">Checkout</a>
                        </div>
                    </li>
                    <li class="nav-item"><a href="about.html" class="nav-link">About Us</a></li>
                    <li class="nav-item cta cta-colored"><a href="cart.php" class="nav-link"><span class="icon-shopping_cart"></span></a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Halo, <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Pengunjung'; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="logout.php">Logout</a>
                            <a class="dropdown-item active" href="pesanan.php">Riwayat Pesanan</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- END nav -->

    <div class="hero-wrap hero-bread" style="background-image: url('images/yasaka1.jpg');">
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <h1 class="mb-0 bread">Status Pesanan</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Daftar Pesanan</h2>
        
        <!-- Filter Dropdown -->
        <div class="row filter-section">
            <div class="col-md-6 col-sm-12 mb-3">
                <form action="pesanan.php" method="GET" class="d-flex">
                    <select name="status" class="form-control mr-2">
                        <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>Semua Status</option>
                        <option value="Menunggu konfirmasi" <?php echo $status_filter == 'Menunggu konfirmasi' ? 'selected' : ''; ?>>Menunggu Konfirmasi</option>
                        <option value="Pembayaran selesai" <?php echo $status_filter == 'Pembayaran selesai' ? 'selected' : ''; ?>>Pembayaran Selesai</option>
                        <option value="Pesanan Disiapkan" <?php echo $status_filter == 'Pesanan Disiapkan' ? 'selected' : ''; ?>>Pesanan Disiapkan</option>
                        <option value="Pesanan Dikirim" <?php echo $status_filter == 'Pesanan Dikirim' ? 'selected' : ''; ?>>Pesanan Dikirim</option>
                        <option value="Pesanan Selesai" <?php echo $status_filter == 'Pesanan Selesai' ? 'selected' : ''; ?>>Pesanan Selesai</option>
                    </select>
                    <div class="button-bar">
                            <button class="filter-btn">Filter</button>
                            <a href="shop.php" class="back-btn">🔙 Kembali ke Menu</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Desktop Table View (Hidden on Mobile) -->
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Pesanan</th>
                            <th>Total</th>
                            <th>Metode Pembayaran</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></td>
                                    <td><?= htmlspecialchars($row['alamat'] . ', ' . $row['kota'] . ' ' . $row['kode_pos']) ?></td>
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
                                            $badgeClass = 'secondary';
                                            if ($status === 'Menunggu konfirmasi') $badgeClass = 'info';
                                            elseif ($status === 'Pembayaran selesai') $badgeClass = 'pay-done';
                                            elseif ($status === 'Pesanan Disiapkan') $badgeClass = 'warning';
                                            elseif ($status === 'Pesanan Dikirim') $badgeClass = 'info';
                                            elseif ($status === 'Pesanan Selesai') $badgeClass = 'success';
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                                    </td>
                                    <td><?= formatDateTime($row['created_at']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center">Tidak ada data pesanan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Mobile Card View (Visible only on Mobile) -->
        <div class="d-md-none">
            <?php 
            if ($result && $result->num_rows > 0): 
                // Reset the result pointer
                $result->data_seek(0);
                while($row = $result->fetch_assoc()): 
                    $status = $row['status'];
                    $badgeClass = 'secondary';
                    if ($status === 'Menunggu konfirmasi') $badgeClass = 'info';
                    elseif ($status === 'Pembayaran selesai') $badgeClass = 'pay-done';
                    elseif ($status === 'Pesanan Disiapkan') $badgeClass = 'warning';
                    elseif ($status === 'Pesanan Dikirim') $badgeClass = 'info';
                    elseif ($status === 'Pesanan Selesai') $badgeClass = 'success';
            ?>
                <div class="card mobile-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Pesanan #<?= htmlspecialchars($row['id']) ?></span>
                        <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></h5>
                        <p class="card-text">
                            <strong>Alamat:</strong> <?= htmlspecialchars($row['alamat'] . ', ' . $row['kota'] . ' ' . $row['kode_pos']) ?><br>
                            <strong>Telepon:</strong> <?= htmlspecialchars($row['no_telepon']) ?><br>
                            <strong>Total:</strong> Rp <?= number_format($row['total']) ?><br>
                            <strong>Pembayaran:</strong> <?= htmlspecialchars($row['metode_pembayaran']) ?><br>
                            <strong>Tanggal:</strong> <?= formatDateTime($row['created_at']) ?>
                        </p>
                        <p><strong>Items:</strong></p>
                        <ul>
                            <?php
                                $items = json_decode($row['pesanan'], true);
                                if (is_array($items)) {
                                    foreach ($items as $item) {
                                        echo '<li>' . htmlspecialchars($item['menu_name']) . " (x" . $item['quantity'] . ") - Rp " . number_format($item['menu_price']) . '</li>';
                                    }
                                } else {
                                    echo '<li>Format tidak valid</li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            <?php 
                endwhile; 
            else: 
            ?>
                <div class="alert alert-info">Tidak ada data pesanan.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="ftco-footer ftco-section bg-light mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Yasaka Fried Chicken
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
