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
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Shared styling */
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

        /* Desktop specific styling */
        .desktop-table {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .desktop-table thead {
            background-color: #82ae46; /* Match with the site's primary color */
            color: white;
        }
        
        .desktop-table th, .desktop-table td {
            padding: 15px;
            vertical-align: middle;
        }
        
        .desktop-table tbody tr:hover {
            background-color: rgba(130, 174, 70, 0.05);
        }
        
        .desktop-table tbody tr {
            border-bottom: 1px solid #f2f2f2;
        }
        
        /* Filter and button styling */
        .btn {
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-green {
            background-color: #82ae46; /* Match with the site's primary color */
            color: #fff;
        }

        .btn-green:hover {
            background-color: #71973c;
        }

        .status-dropdown {
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 6px;
            border: 1.5px solid #ddd;
            transition: border-color 0.3s;
        }
        
        .status-dropdown:focus {
            border-color: #82ae46;
            outline: none;
        }

        .filter-container {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Order details styling */
        .order-items {
            list-style: none;
            padding-left: 0;
        }
        
        .order-items li {
            padding: 3px 0;
            border-bottom: 1px dashed #eee;
        }
        
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
        <div class="row">
            <div class="col-md-8">
                <h2 class="mb-4">Daftar Pesanan</h2>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="shop.php" class="btn btn-green">
                    <i class="fa fa-arrow-left"></i> Kembali ke Menu
                </a>
            </div>
        </div>
        
        <!-- Filter Dropdown -->
        <div class="filter-container">
            <form method="GET" style="display: flex; align-items: center; gap: 12px;">
                <select name="status" class="status-dropdown" onchange="this.form.submit()">
                    <option value="all" <?= $status_filter == 'all' ? 'selected' : '' ?>>Semua Status</option>
                    <option value="Menunggu" <?= $status_filter == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="Diproses" <?= $status_filter == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option value="Selesai" <?= $status_filter == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>

                <button type="submit" class="btn btn-green">
                    <i class="fa fa-filter"></i> Terapkan Filter
                </button>
            </form>
        </div>
        
        <!-- Desktop Table View (Hidden on Mobile) -->
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table desktop-table">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Nama</th>
                            <th width="20%">Pesanan</th>
                            <th width="10%">Total</th>
                            <th width="15%">Pembayaran</th>
                            <th width="10%">Status</th>
                            <th width="15%">Tanggal</th>
                            <th width="10%">Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></td>
                                    <td>
                                        <ul class="order-items">
                                        <?php
                                            $items = json_decode($row['pesanan'], true);
                                            if (is_array($items)) {
                                                foreach ($items as $item) {
                                                    echo '<li>' . htmlspecialchars($item['menu_name']) . " (x" . $item['quantity'] . ")</li>";
                                                }
                                            } else {
                                                echo 'Format tidak valid';
                                            }
                                        ?>
                                        </ul>
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
                                    <td>
                                        <button class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="<?= htmlspecialchars($row['alamat'] . ', ' . $row['kota'] . ' ' . $row['kode_pos']) ?>">
                                            <i class="fa fa-map-marker-alt"></i> Lihat
                                        </button>
                                    </td>
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
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            
            // Auto-submit filter form when dropdown value changes
            $('.status-dropdown').on('change', function() {
                $(this).closest('form').submit();
            });
        });
    </script>
</body>
</html>
