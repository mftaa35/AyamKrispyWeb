<?php
include 'config.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan user login
if (!isset($_SESSION['users_id'])) {
    echo "Silakan login terlebih dahulu.";
    exit;
}

$users_id = $_SESSION['users_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE users_id = '$users_id'");
$user = mysqli_fetch_assoc($query);

// Tangani form submit
if (isset($_POST['orders2'])) {
    // Validasi input form
    $nama_depan = isset($_POST['nama_depan']) ? mysqli_real_escape_string($conn, $_POST['nama_depan']) : '';
    $nama_belakang = isset($_POST['nama_belakang']) ? mysqli_real_escape_string($conn, $_POST['nama_belakang']) : '';
    $alamat = isset($_POST['alamat']) ? mysqli_real_escape_string($conn, $_POST['alamat']) : '';
    $petunjuk_arah = isset($_POST['petunjuk_arah']) ? mysqli_real_escape_string($conn, $_POST['petunjuk_arah']) : '';
    $kota = isset($_POST['kota']) ? mysqli_real_escape_string($conn, $_POST['kota']) : '';
    $kode_pos = isset($_POST['kode_pos']) ? mysqli_real_escape_string($conn, $_POST['kode_pos']) : '';
    $telepon = isset($_POST['telepon']) ? mysqli_real_escape_string($conn, $_POST['telepon']) : '';
    $metode = isset($_POST['metode_pembayaran']) ? mysqli_real_escape_string($conn, $_POST['metode_pembayaran']) : '';

    // Periksa apakah semua data sudah diisi
    if (empty($nama_depan) || empty($nama_belakang) || empty($alamat) || empty($telepon) || empty($metode)) {
        $_SESSION['checkout_error'] = "Semua kolom wajib diisi.";
        header("Location: checkout.php");
        exit;
    }

    // Ambil data dari keranjang
    $query_keranjang1 = mysqli_query($conn, "SELECT * FROM keranjang1 WHERE users_id = '$users_id'");
    if (!$query_keranjang1 || mysqli_num_rows($query_keranjang1) == 0) {
        $_SESSION['checkout_error'] = "Keranjang Anda kosong.";
        header("Location: checkout.php");
        exit;
    }

    $items = [];
    $subtotal = 0;

    // Loop data item dari keranjang
    while ($item = mysqli_fetch_assoc($query_keranjang1)) {
        $items[] = [
            'menu_name' => $item['menu_name'],
            'menu_price' => $item['menu_price'],
            'quantity' => $item['quantity'],
        ];
        $subtotal += ($item['menu_price'] * $item['quantity']);
    }

    // Encode data pesanan
    $pesanan = json_encode($items);
    $ongkir = 10000; // Ongkos kirim
    $total = $subtotal + $ongkir;

    // Prepared statement untuk menghindari SQL Injection
    $query_order = $conn->prepare("INSERT INTO orders2 (
        nama_depan, nama_belakang, alamat, petunjuk_arah, kota, kode_pos, no_telepon,
        pesanan, subtotal, ongkir, total, metode_pembayaran
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameter
    $query_order->bind_param('ssssssssiiis', $nama_depan, $nama_belakang, $alamat, $petunjuk_arah, $kota, $kode_pos, $telepon, $pesanan, $subtotal, $ongkir, $total, $metode);

    if ($query_order->execute()) {
        // Hapus isi keranjang setelah order
        mysqli_query($conn, "DELETE FROM keranjang1 WHERE users_id = '$users_id'");

        // Redirect berdasarkan metode pembayaran
        if ($metode === 'QRIS') {
            header('Location: qris.php');
        } else {
            header('Location: pesanan.php');
        }
        exit;
    } else {
        $_SESSION['checkout_error'] = "Gagal membuat pesanan: " . $query_order->error;
        header("Location: checkout.php");
        exit;
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
            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">Shop</a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
              <a class="dropdown-item" href="shop.php">Menu Ayam</a>
              <a class="dropdown-item" href="product-single.html">Deskripsi Menu</a>
              <a class="dropdown-item" href="cart.html">Detail Keranjang</a>
              <a class="dropdown-item" href="checkout.html">Checkout</a>
            </div>
          </li>
          <li class="nav-item"><a href="about.html" class="nav-link">About Us</a></li>
          <li class="nav-item cta cta-colored"><a href="cart.php" class="nav-link"><span
                class="icon-shopping_cart"></span></a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
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

    <!-- Hero -->
    <div class="hero-wrap hero-bread" style="background-image: url('images/yasaka1.jpg');">
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <h1 class="mb-0 bread">Checkout</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Section -->
    <section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="checkout-form-wrap bg-white p-md-5 p-4 rounded shadow-sm">
                    <form action="" method="POST" id="checkoutForm" class="billing-form">
                        <!-- Personal Information Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h3 class="billing-heading mb-4 text-primary">Detail Pemesanan</h3>
                                <div class="divider mb-4"></div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_depan" class="font-weight-bold">Nama Depan</label>
                                    <input type="text" name="nama_depan" id="nama_depan" class="form-control" required value="<?= isset($user['nama']) ? htmlspecialchars(explode(' ', $user['nama'])[0]) : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_belakang" class="font-weight-bold">Nama Belakang</label>
                                    <input type="text" name="nama_belakang" id="nama_belakang" class="form-control" required value="<?= isset($user['nama']) ? htmlspecialchars(explode(' ', $user['nama'])[1] ?? '') : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="alamat" class="font-weight-bold">Alamat</label>
                                    <input type="text" name="alamat" id="alamat" class="form-control" required value="<?= isset($user['alamat']) ? htmlspecialchars($user['alamat']) : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="petunjuk_arah" class="font-weight-bold">Petunjuk Arah</label>
                                    <input type="text" name="petunjuk_arah" id="petunjuk_arah" class="form-control rounded-pill" placeholder="Opsional">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kota" class="font-weight-bold">Kota</label>
                                    <input type="text" name="kota" id="kota" class="form-control rounded-pill" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kode_pos" class="font-weight-bold">Kode Pos</label>
                                    <input type="text" name="kode_pos" id="kode_pos" class="form-control rounded-pill" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telepon" class="font-weight-bold">No Telepon</label>
                                    <input type="text" name="telepon" id="telepon" class="form-control" required value="<?= isset($user['no_telepon']) ? htmlspecialchars($user['no_telepon']) : '' ?>">
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
                                                $query_items = "SELECT * FROM keranjang1 WHERE users_id = '$users_id'";
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
                                                       <button type="submit" name="orders2">Buat Pesanan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add this to the head section of your HTML -->
<style>
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
    
    .thead-primary {
        background: #82ae46;
        color: #fff;
    }
    
    .product-name h4 {
        font-size: 16px;
        font-weight: 500;
    }
    
    .cart-total {
        border: 1px solid #e6e6e6;
    }
    
    .btn-primary {
        background: #82ae46;
        border-color: #82ae46;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: #6f9a3a;
        border-color: #6f9a3a;
    }
    
    .payment-options label {
        cursor: pointer;
        font-weight: 500;
    }
</style>

<script>
function ubahTombol() {
    const metode = document.getElementById('metode_pembayaran').value;
    const btnText = document.getElementById('btnText');

    if (metode === 'QRIS') {
        btnText.textContent = 'Bayar Sekarang';
    } else {
        btnText.textContent = 'Buat Pesanan';
    }
}
</script>

<!-- Loader -->
<div id="ftco-loader" class="show fullscreen">
    <svg class="circular" width="48px" height="48px">
        <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
        <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#F96D00"/>
    </svg>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
</body>
</html>
