<?php
include 'config.php';
session_start();

$user_id = 1;

// Tangani form submit
if (isset($_POST['order'])) {
    // Validasi input form
    $nama_depan = isset($_POST['nama_depan']) ? mysqli_real_escape_string($conn, $_POST['nama_depan']) : '';
    $nama_belakang = isset($_POST['nama_belakang']) ? mysqli_real_escape_string($conn, $_POST['nama_belakang']) : '';
    $alamat = isset($_POST['alamat']) ? mysqli_real_escape_string($conn, $_POST['alamat']) : '';
    $petunjuk_arah = isset($_POST['petunjuk_arah']) ? mysqli_real_escape_string($conn, $_POST['petunjuk_arah']) : '';
    $kota = isset($_POST['kota']) ? mysqli_real_escape_string($conn, $_POST['kota']) : '';
    $kode_pos = isset($_POST['kode_pos']) ? mysqli_real_escape_string($conn, $_POST['kode_pos']) : '';
    $telepon = isset($_POST['telepon']) ? mysqli_real_escape_string($conn, $_POST['telepon']) : '';
    $metode = isset($_POST['metode_pembayaran']) ? mysqli_real_escape_string($conn, $_POST['metode_pembayaran']) : '';

    // Periksa apakah semua data sudah ada
    if (empty($nama_depan) || empty($nama_belakang) || empty($alamat) || empty($telepon) || empty($metode)) {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
        exit;
    }

    // Ambil data dari keranjang
    $query_keranjang = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id = '$user_id'");
    if (mysqli_num_rows($query_keranjang) == 0) {
        echo "<script>alert('Keranjang Anda kosong!');</script>";
        exit;
    }

    $items = [];
    $subtotal = 0;

    // Loop untuk mengambil data item dari keranjang
    while ($item = mysqli_fetch_assoc($query_keranjang)) {
        $items[] = [
            'menu_name' => $item['menu_name'],
            'menu_price' => $item['menu_price'],
            'quantity' => $item['quantity'],
        ];
        $subtotal += ($item['menu_price'] * $item['quantity']);
    }

    // Encode data pesanan menjadi JSON
    $pesanan = json_encode($items);
    $ongkir = 10000; // Ongkos kirim
    $total = $subtotal + $ongkir;

    // Gunakan prepared statement untuk menghindari SQL Injection
    $query_order = $conn->prepare("INSERT INTO orders2 (
        nama_depan, nama_belakang, alamat, petunjuk_arah, kota, kode_pos, no_telepon,
        pesanan, subtotal, ongkir, total, metode_pembayaran
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameter
    $query_order->bind_param('ssssssssiiis', $nama_depan, $nama_belakang, $alamat, $petunjuk_arah, $kota, $kode_pos, $telepon, $pesanan, $subtotal, $ongkir, $total, $metode);

    if ($query_order->execute()) {
        // Hapus item dari keranjang setelah order berhasil
        mysqli_query($conn, "DELETE FROM keranjang WHERE user_id = '$user_id'");
        
        // Redirect berdasarkan metode pembayaran
        if ($metode === 'QRIS') {
            header('Location: qris.php');
        } else {
            header('Location: pesanan.php');
        }
        exit;
    } else {
        echo "<script>alert('Gagal membuat pesanan: " . $query_order->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Yasaka Fried Chicken - Checkout</title>
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
    
    <!-- Enhanced Mobile Responsive Styles -->
    <style>
        /* General responsive improvements */
        @media (max-width: 768px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            h1.bread {
                font-size: 28px !important;
            }
            
            .checkout-form-wrap {
                padding: 15px !important;
            }
            
            .form-control {
                font-size: 14px !important;
            }
            
            .table th, .table td {
                padding: 0.5rem;
                font-size: 14px;
            }
            
            .product-name h4 {
                font-size: 14px;
            }
            
            .btn {
                font-size: 14px !important;
            }
        }
        
        /* Specific mobile optimizations */
        @media (max-width: 576px) {
            .billing-heading {
                font-size: 20px;
            }
            
            .table-responsive {
                margin-bottom: 15px;
                border: 0;
            }
            
            /* Mobile cards styling */
            .mobile-cards {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            
            .order-card {
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 2px 15px rgba(0,0,0,0.08);
                border: 1px solid rgba(0,0,0,0.05);
            }
            
            .order-card-header {
                background: #f8f9fa;
                padding: 12px 15px;
                border-bottom: 1px solid #eee;
            }
            
            .menu-name {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
                color: #333;
            }
            
            .order-card-body {
                padding: 15px;
            }
            
            .order-detail {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 12px;
                font-size: 14px;
            }
            
            .order-detail span:first-child {
                color: #666;
                font-weight: 500;
            }
            
            .price, .total-price {
                font-weight: 600;
                color: #333;
            }
            
            .total-price {
                color: #82ae46;
                font-size: 16px;
            }
            
            .quantity-controls {
                display: flex;
                align-items: center;
            }
            
            .quantity-input-mobile {
                max-width: 70px;
                height: 40px !important;
                text-align: center;
                border-radius: 8px !important;
                border: 1px solid #ddd !important;
            }
            
            .note-input-mobile {
                font-size: 13px !important;
                height: 40px !important;
                border-radius: 8px !important;
                background: #f9f9f9 !important;
            }
            
            .note-input-wrapper {
                width: 170px;
            }
            
            .order-card-footer {
                background: #f8f9fa;
                padding: 12px 15px;
                border-top: 1px solid #eee;
            }
            
            .order-card-footer .order-detail {
                margin-bottom: 0;
            }
            
            /* Sticky checkout summary for mobile */
            .cart-total-sticky {
                position: sticky;
                bottom: 0;
                background: white;
                z-index: 99;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
                margin-left: -15px;
                margin-right: -15px;
                padding: 15px;
                border-top: 1px solid #eee;
            }
        }
        
        /* General improvements */
        .billing-heading {
            position: relative;
            font-weight: 600;
        }
        
        .divider {
            height: 2px;
            background: #f0f0f0;
            width: 100%;
        }
        
        .checkout-form-wrap {
            margin-bottom: 30px;
            border-radius: 15px;
            border: 1px solid #f0f0f0;
        }
        
        .form-control {
            height: 50px;
            background: #f8f9fa;
            border: 1px solid #e6e6e6;
            font-size: 16px;
        }
        
        .form-control:focus {
            box-shadow: none;
            border-color: #82ae46;
        }
        
        /* Enhanced Table Styling */
        .custom-table {
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-top: -8px;
        }
        
        .custom-table thead th {
            background: #82ae46;
            color: #fff;
            padding: 15px;
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
        }
        
        .custom-table thead th:first-child {
            border-radius: 10px 0 0 10px;
        }
        
        .custom-table thead th:last-child {
            border-radius: 0 10px 10px 0;
        }
        
        .custom-table tbody tr {
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-radius: 10px;
            background: white;
            transition: all 0.2s ease;
        }
        
        .custom-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .custom-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border: none;
            border-top: 1px solid #f1f1f1;
            border-bottom: 1px solid #f1f1f1;
        }
        
        .custom-table tbody td:first-child {
            border-left: 1px solid #f1f1f1;
            border-radius: 10px 0 0 10px;
        }
        
        .custom-table tbody td:last-child {
            border-right: 1px solid #f1f1f1;
            border-radius: 0 10px 10px 0;
        }
        
        .quantity-input {
            max-width: 100px;
            margin: 0 auto;
        }
        
        .note-input {
            background: #f9f9f9;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .price-display {
            font-weight: 600;
            color: #82ae46;
        }
        
        .product-name h4 {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 0;
        }
        
        .cart-total {
            border: 1px solid #f0f0f0;
            border-radius: 15px;
            padding: 25px;
            background: white;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }
        
        .btn-primary {
            background: #82ae46;
            border-color: #82ae46;
            transition: all 0.3s ease;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .btn-primary:hover {
            background: #6f9a3a;
            border-color: #6f9a3a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(130, 174, 70, 0.3);
        }
        
        .payment-options label {
            cursor: pointer;
            font-weight: 500;
        }
        
        /* Fix for navbar on mobile */
        @media (max-width: 991.98px) {
            .ftco-navbar-light {
                background: #343a40 !important;
                position: relative;
                top: 0;
            }
            
            .ftco-navbar-light .navbar-brand {
                color: #fff;
            }
            
            .navbar-nav {
                padding-bottom: 10px;
            }
            
            .navbar-nav .nav-item > .nav-link {
                padding-left: 0;
                padding-right: 0;
                padding-bottom: 1rem;
                padding-top: 0;
            }
        }
        
        /* Improved form focus for mobile */
        input:focus, select:focus, textarea:focus {
            font-size: 16px !important;
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
                    <li class="nav-item active"><a href="dashboard.php" class="nav-link">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shop</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="shop.php">Menu Ayam</a>
                            <a class="dropdown-item" href="product-single.html">Deskripsi Menu</a>
                            <a class="dropdown-item" href="cart.html">Detail Keranjang</a>
                            <a class="dropdown-item" href="checkout.html">Checkout</a>
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
                            <a class="dropdown-item" href="riwayatpesanan.php">Riwayat Pesanan</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- END nav -->

    <!-- Hero - Responsive Background -->
    <div class="hero-wrap hero-bread" style="background-image: url('images/yasaka1.jpg');">
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <h1 class="mb-0 bread">Checkout</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Section - Improved for Mobile -->
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="checkout-form-wrap bg-white p-3 p-md-5 rounded shadow-sm">
                        <form action="" method="POST" id="checkoutForm" class="billing-form">
                            <!-- Personal Information Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h3 class="billing-heading mb-3 text-primary">Detail Pemesanan</h3>
                                    <div class="divider mb-4"></div>
                                </div>
                                
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="nama_depan" class="font-weight-bold">Nama Depan</label>
                                        <input type="text" name="nama_depan" id="nama_depan" class="form-control rounded-pill" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="nama_belakang" class="font-weight-bold">Nama Belakang</label>
                                        <input type="text" name="nama_belakang" id="nama_belakang" class="form-control rounded-pill" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="alamat" class="font-weight-bold">Alamat</label>
                                        <input type="text" name="alamat" id="alamat" class="form-control rounded-pill" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="petunjuk_arah" class="font-weight-bold">Petunjuk Arah</label>
                                        <input type="text" name="petunjuk_arah" id="petunjuk_arah" class="form-control rounded-pill" placeholder="Opsional">
                                    </div>
                                </div>
                                <div class="col-md-4 col-6 mb-3">
                                    <div class="form-group">
                                        <label for="kota" class="font-weight-bold">Kota</label>
                                        <input type="text" name="kota" id="kota" class="form-control rounded-pill" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6 mb-3">
                                    <div class="form-group">
                                        <label for="kode_pos" class="font-weight-bold">Kode Pos</label>
                                        <input type="text" name="kode_pos" id="kode_pos" class="form-control rounded-pill" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="telepon" class="font-weight-bold">No Telepon</label>
                                        <input type="tel" name="telepon" id="telepon" class="form-control rounded-pill" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Details Section - Modified for Better Mobile View -->
                            <div class="row mt-4 mb-4">
                                <div class="col-md-12">
                                    <h3 class="billing-heading mb-3 text-primary">Daftar Pesanan Anda</h3>
                                    <div class="divider mb-4"></div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <!-- Standard table for desktop -->
                                        <table class="table custom-table d-none d-md-table">
                                            <thead class="thead-primary">
                                                <tr class="text-center">
                                                    <th>Menu</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Catatan</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query_items = "SELECT * FROM keranjang WHERE user_id = '$user_id'";
                                                $result_items = mysqli_query($conn, $query_items);
                                                $grand_total = 0;
                                                while ($item = mysqli_fetch_assoc($result_items)) {
                                                    $sub_total = $item['menu_price'] * $item['quantity'];
                                                    $grand_total += $sub_total;
                                                ?>
                                                <tr class="text-center">
                                                    <td class="product-name">
                                                        <h4><?php echo htmlspecialchars($item['menu_name']); ?></h4>
                                                    </td>
                                                    <td>Rp <?php echo number_format($item['menu_price']); ?></td>
                                                    <td>
                                                        <div class="input-group quantity-input">
                                                            <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" readonly class="form-control text-center">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="note[<?php echo $item['id']; ?>]" value="<?php echo htmlspecialchars($item['note']); ?>" class="form-control note-input">
                                                    </td>
                                                    <td class="price-display">Rp <?php echo number_format($sub_total); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        
                                        <!-- Mobile optimized cards -->
                                        <div class="mobile-cards d-md-none">
                                            <?php
                                            // Reset the data pointer
                                            mysqli_data_seek($result_items, 0);
                                            while ($item = mysqli_fetch_assoc($result_items)) {
                                                $sub_total = $item['menu_price'] * $item['quantity'];
                                            ?>
                                            <div class="order-card">
                                                <div class="order-card-header">
                                                    <h4 class="menu-name"><?php echo htmlspecialchars($item['menu_name']); ?></h4>
                                                </div>
                                                <div class="order-card-body">
                                                    <div class="order-detail">
                                                        <span>Harga</span>
                                                        <span class="price">Rp <?php echo number_format($item['menu_price']); ?></span>
                                                    </div>
                                                    <div class="order-detail">
                                                        <span>Jumlah</span>
                                                        <div class="quantity-controls">
                                                            <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" readonly class="form-control text-center quantity-input-mobile">
                                                        </div>
                                                    </div>
                                                    <div class="order-detail">
                                                        <span>Catatan</span>
                                                        <div class="note-input-wrapper">
                                                            <input type="text" name="note[<?php echo $item['id']; ?>]" value="<?php echo htmlspecialchars($item['note']); ?>" class="form-control note-input-mobile" placeholder="Tambahkan catatan">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="order-card-footer">
                                                    <div class="order-detail">
                                                        <span>Total</span>
                                                        <span class="total-price">Rp <?php echo number_format($sub_total); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
 
                                        <!-- Cart Total & Payment Section - Enhanced and Optimized for Mobile -->
                                        <div class="row mt-5">
                                            <div class="col-lg-6 col-md-8 ml-auto">
                                                <div class="cart-total">
                                                    <h3 class="text-primary mb-4">Total Pesanan</h3>
                                                    <?php 
                                                    $ongkir = 10000; // Tambah ongkir Rp 10.000
                                                    $total_bayar = $grand_total + $ongkir;
                                                    ?>
                                                    <div class="summary-row">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <span class="summary-label">Subtotal</span>
                                                            <span class="summary-value">Rp <?php echo number_format($grand_total); ?></span>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <span class="summary-label">
                                                                <div class="d-flex align-items-center">
                                                                    <span>Ongkir</span>
                                                                    <span class="ml-1 badge badge-light" title="Biaya pengiriman tetap">
                                                                        <i class="icon-info"></i>
                                                                    </span>
                                                                </div>
                                                            </span>
                                                            <span class="summary-value">Rp <?php echo number_format($ongkir); ?></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="total-divider my-3"></div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <span class="total-label">Total</span>
                                                        <span class="total-value">Rp <?php echo number_format($total_bayar); ?></span>
                                                    </div>
       
                                                    <div class="payment-section">
                                                        <label for="metode_pembayaran" class="payment-label">Metode Pembayaran</label>
                                                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-control payment-select" required onchange="ubahTombol()">
                                                            <option value="">-- Pilih Metode Pembayaran --</option>
                                                            <?php
                                                            $query = mysqli_query($conn, "SHOW COLUMNS FROM orders2 LIKE 'metode_pembayaran'");
                                                            $row = mysqli_fetch_assoc($query);
                                                            $type = $row['Type'];

                                                            if (preg_match("/^enum\('(.*)'\)$/", $type, $matches)) {
                                                                $enumValues = explode("','", $matches[1]);
                                                                foreach ($enumValues as $value) {
                                                                    $value = trim($value);
                                                                    if ($value !== '') {
                                                                        echo '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($value) . '</option>';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                        <input type="hidden" name="order" value="true">
                                                        <button type="submit" id="btnSubmit" class="btn btn-primary py-3 px-4 w-100 rounded-pill mt-4 checkout-btn">
                                                            <i class="icon-shopping-cart mr-2"></i><span id="btnText">Buat Pesanan</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <style>
                                            .summary-row {
                                                background: #f9f9f9;
                                                padding: 15px;
                                                border-radius: 10px;
                                            }
                                            
                                            .summary-label {
                                                color: #666;
                                                font-weight: 500;
                                            }
                                            
                                            .summary-value {
                                                font-weight: 600;
                                            }
                                            
                                            .total-divider {
                                                height: 1px;
                                                background: #e2e2e2;
                                                position: relative;
                                            }
                                            
                                            .total-label {
                                                font-weight: 700;
                                                font-size: 18px;
                                                color: #333;
                                            }
                                            
                                            .total-value {
                                                font-weight: 700;
                                                font-size: 24px;
                                                color: #82ae46;
                                            }
                                            
                                            .payment-label {
                                                font-weight: 600;
                                                color: #333;
                                                margin-bottom: 8px;
                                                display: block;
                                            }
                                            
                                            .payment-select {
                                                border-radius: 10px;
                                                border: 1px solid #ddd;
                                                background: #f5f5f5;
                                                font-weight: 500;
                                            }
                                            
                                            .checkout-btn {
                                                font-size: 16px;
                                                font-weight: 600;
                                                text-transform: uppercase;
                                                letter-spacing: 1px;
                                            }
                                            
                                            @media (max-width: 576px) {
                                                .cart-total {
                                                    padding: 20px 15px;
                                                }
                                                
                                                .total-value {
                                                    font-size: 20px;
                                                }
                                            }
                                        </style>
                                        
                                        <!-- Enhanced Mobile sticky checkout button (visible on small screens) -->
                                        <div class="d-md-none cart-total-sticky mt-3">
                                            <div class="d-flex justify-content-between align
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#F96D00"/>
        </svg>
    </div>

    <!-- JavaScript -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/scrollax.min.js"></script>
    <script src="js/main.js"></script>
    
    <script>
    function ubahTombol() {
        const metode = document.getElementById('metode_pembayaran').value;
        const btnText = document.getElementById('btnText');
        const btnTextMobile = document.getElementById('btnTextMobile');
        
        if (metode === 'QRIS') {
            btnText.textContent = 'Bayar Sekarang';
            if (btnTextMobile) {
                btnTextMobile.textContent = 'Bayar Sekarang';
            }
        } else {
            btnText.textContent = 'Buat Pesanan';
            if (btnTextMobile) {
                btnTextMobile.textContent = 'Buat Pesanan';
            }
        }
    }
    
    // Prevent zoom on input focus (for iOS)
    document.addEventListener('touchstart', function(event) {
        if (event.touches.length > 1) {
            event.preventDefault();
        }
    }, { passive: false });
    
    let lastTouchEnd = 0;
    document.addEventListener('touchend', function(event) {
        const now = Date.now();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);
    </script>
</body>
</html>
