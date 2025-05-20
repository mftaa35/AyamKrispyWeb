<?php
session_start();
include 'config.php';

// Ambil semua data pesanan
// Ambil semua data pesanan
$sql = "SELECT * FROM orders2 WHERE status IN ('pesanan selesai') ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Yasaka Fried Chicken - Riwayat Pesanan</title>
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
    <h2 class="mb-4">Riwayat Pesanan</h2>
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
                    <th>Metode Pembayaran</th>
                    <th>Status</th>
                    <th>Tanggal</th>
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
                                    $badgeClass = 'secondary';
                                    if ($status === 'Menunggu konfirmasi') $badgeClass = 'info';
                                    elseif ($status === 'Pembayaran selesai') $badgeClass = 'pay done';
                                    elseif ($status === 'Pesanan Disiapkan') $badgeClass = 'warning';
                                    elseif ($status === 'Pesanan Dikirim') $badgeClass = 'info';
                                    elseif ($status === 'Pesanan Selesai') $badgeClass = 'success';
                                ?>
                                <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="15" class="text-center">Tidak ada data pesanan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
