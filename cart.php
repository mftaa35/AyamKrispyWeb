<?php
include 'config.php';
session_start();

// Misalnya user login pakai user_id 1
$users_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    // Ambil data dari form
    $menu_name = mysqli_real_escape_string($conn, $_POST['menu_name']);
    $menu_price = (int)$_POST['menu_price'];
    // $menu_image = mysqli_real_escape_string($conn, $_POST['menu_image']);
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $note = isset($_POST['note']) ? mysqli_real_escape_string($conn, $_POST['note']) : '';

    // Cek apakah item sudah ada di keranjang
    // FIX: Added table name "keranjang1" after FROM
    $check_query = "SELECT * FROM keranjang1 WHERE users_id = '$users_id' AND menu_name = '$menu_name'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Jika sudah ada, update jumlah dan catatan
        $existing = mysqli_fetch_assoc($check_result);
        $new_quantity = $existing['quantity'] + $quantity;
        $new_note = trim($existing['note'] . "\n" . $note);

        $update_query = "UPDATE keranjang1 SET quantity = '$new_quantity', note = '$new_note'
                         WHERE id = '{$existing['id']}'";

        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Jumlah produk diperbarui di keranjang!'); window.location.href='cart.php';</script>";
        } else {
            echo "Gagal update keranjang: " . mysqli_error($conn);
        }
    } else {
        // Jika belum ada, tambahkan ke keranjang
        $query = "INSERT INTO keranjang1 (users_id, menu_name, menu_price, menu_image, quantity, note)
                  VALUES ('$users_id', '$menu_name', '$menu_price', '$menu_image', '$quantity', '$note')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Produk berhasil ditambahkan ke keranjang!'); window.location.href='cart.php';</script>";
        } else {
            echo "Gagal tambah ke keranjang: " . mysqli_error($conn);
        }
    }
}

// Hitung jumlah item di keranjang untuk tampilan badge
// FIX: Added table name "keranjang1" after FROM
$count_query = "SELECT SUM(quantity) as total_items FROM keranjang1 WHERE users_id = '$users_id'";
$count_result = mysqli_query($conn, $count_query);
$count_data = mysqli_fetch_assoc($count_result);
$cart_count = $count_data['total_items'] ? $count_data['total_items'] : 0;

// Ambil data keranjang
// FIX: Added table name "keranjang1" after FROM
$query = "SELECT * FROM keranjang1 WHERE users_id = '$users_id'";
$result = mysqli_query($conn, $query);
$total_bayar = 0;
$cart_items = [];

if (mysqli_num_rows($result) > 0) {
    while ($product = mysqli_fetch_assoc($result)) {
        $jumlah = $product['quantity'];
        $subtotal = $product['menu_price'] * $jumlah;
        $total_bayar += $subtotal;
        
        // $product['image_path'] = $image_path;
        $product['subtotal'] = $subtotal;
        $cart_items[] = $product;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Yasaka Fried Chicken - Keranjang</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <style>
        /* Custom responsive styles */
        .cart-mobile-item {
            border-bottom: 1px solid #e6e6e6;
            padding: 15px 0;
        }
        
        .cart-mobile-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .cart-mobile-details {
            padding-left: 15px;
        }
        
        .cart-mobile-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .cart-mobile-price {
            color: #82ae46;
            margin-bottom: 5px;
        }
        
        .cart-mobile-quantity {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .cart-mobile-total {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .cart-mobile-note {
            font-size: 13px;
            color: #777;
            margin-bottom: 5px;
            font-style: italic;
        }
        
        .cart-mobile-remove {
            color: #dc3545;
            font-size: 20px;
        }
        
        .cart-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        
        .sticky-bottom {
            position: sticky;
            bottom: 0;
            background: white;
            box-shadow: 0 -3px 10px rgba(0,0,0,0.1);
            padding: 15px;
            z-index: 999;
        }
        
        @media (max-width: 767px) {
            .desktop-cart {
                display: none;
            }
            .hero-wrap {
                min-height: 160px;
                height: auto;
            }
            .cart-total {
                margin-bottom: 80px;
            }
        }
        
        @media (min-width: 768px) {
            .mobile-cart {
                display: none;
            }
            .sticky-bottom {
                display: none;
            }
        }
        
        .note-badge {
            display: inline-block;
            background: #f1f1f1;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            margin-left: 5px;
            cursor: pointer;
        }
        
        .quantity-input {
            max-width: 80px;
            text-align: center;
        }
        
        .quantity-buttons {
            display: flex;
            align-items: center;
        }
        
        .quantity-btn {
            border: 1px solid #ddd;
            padding: 2px 8px;
            background: #f8f9fa;
            cursor: pointer;
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
                            <span class="text">0812345678910</span>
                        </div>
                        <div class="col-md pr-4 d-flex topper align-items-center">
                            <div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div>
                            <span class="text">yasakakemuning@gmail.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.html">Yasaka Fried Chicken</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <li class="nav-item active cta cta-colored"><a href="cart.php" class="nav-link"><span class="icon-shopping_cart"></span>[<?php echo $cart_count; ?>]</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Halo, <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Pengguna'; ?>
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
                    <h1 class="mb-0 bread">Keranjang Belanja</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Section -->
    <section class="ftco-section ftco-cart">
        <div class="container">
            <!-- Desktop Cart View -->
            <div class="row desktop-cart">
                <div class="col-md-12 ftco-animate">
                    <div class="cart-list">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr class="text-center">
                                    <th>&nbsp;</th>
                                    <th>Nama Menu</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Catatan</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($cart_items)): ?>
                                    <?php foreach ($cart_items as $product): ?>
                                        <tr class="text-center">
                                            <td class="product-remove">
                                                <a href="hapusitem.php?id=<?php echo $product['id']; ?>" class="text-danger">
                                                    <span class="icon-close"></span>
                                                </a>
                                            </td>
                                            <!-- <td class="menu_image">
                                                <img src="<?php echo $product['image_path']; ?>" alt="<?php echo htmlspecialchars($product['menu_name']); ?>" style="width: 70px; height: auto;">
                                            </td> -->
                                            <td class="menu_name"><?php echo htmlspecialchars($product['menu_name']); ?></td>
                                            <td class="menu_price">Rp <?php echo number_format($product['menu_price']); ?></td>
                                            <td class="quantity">
                                                <div class="quantity-buttons">
                                                    <a href="update_cart.php?action=decrease&id=<?php echo $product['id']; ?>" class="quantity-btn">-</a>
                                                    <input type="text" class="form-control quantity-input mx-2" value="<?php echo $product['quantity']; ?>" readonly>
                                                    <a href="update_cart.php?action=increase&id=<?php echo $product['id']; ?>" class="quantity-btn">+</a>
                                                </div>
                                            </td>
                                            <td class="note">
                                                <?php echo !empty($product['note']) ? htmlspecialchars($product['note']) : '-'; ?>
                                            </td>
                                            <td class="total">Rp <?php echo number_format($product['subtotal']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-cart-message">
                                                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                                                <h4>Keranjang anda masih kosong</h4>
                                                <p class="text-muted">Jelajahi menu kami dan tambahkan item ke keranjang</p>
                                                <a href="shop.php" class="btn btn-primary mt-3">Lihat Menu</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Mobile Cart View -->
            <div class="mobile-cart">
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-4">Item Keranjang (<?php echo $cart_count; ?>)</h4>
                        
                        <?php if (!empty($cart_items)): ?>
                            <?php foreach ($cart_items as $product): ?>
                                <div class="cart-mobile-item">
                                    <div class="d-flex">
                                        <div>
                                            <!-- <img src="<?php echo $product['image_path']; ?>" alt="<?php echo htmlspecialchars($product['menu_name']); ?>" class="cart-mobile-image"> -->
                                        </div>
                                        <div class="cart-mobile-details flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <div class="cart-mobile-title"><?php echo htmlspecialchars($product['menu_name']); ?></div>
                                                <a href="hapusitem.php?id=<?php echo $product['id']; ?>" class="cart-mobile-remove">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                            <div class="cart-mobile-price">Rp <?php echo number_format($product['menu_price']); ?></div>
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="quantity-buttons">
                                                    <a href="update_cart.php?action=decrease&id=<?php echo $product['id']; ?>" class="quantity-btn">-</a>
                                                    <input type="text" class="form-control quantity-input mx-2" value="<?php echo $product['quantity']; ?>" readonly style="width:40px;">
                                                    <a href="update_cart.php?action=increase&id=<?php echo $product['id']; ?>" class="quantity-btn">+</a>
                                                </div>
                                            </div>
                                            <?php if (!empty($product['note'])): ?>
                                                <div class="cart-mobile-note">
                                                    <small><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($product['note']); ?></small>
                                                </div>
                                            <?php endif; ?>
                                            <div class="cart-mobile-total">Rp <?php echo number_format($product['subtotal']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                                <h4>Keranjang anda masih kosong</h4>
                                <p class="text-muted">Jelajahi menu kami dan tambahkan item ke keranjang</p>
                                <a href="shop.php" class="btn btn-primary mt-3">Lihat Menu</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Cart Summary for both views -->
            <?php if (!empty($cart_items)): ?>
                <div class="row justify-content-end">
                    <div class="col-lg-6 mt-5 cart-wrap ftco-animate">
                        <div class="cart-total mb-3 cart-summary">
                            <h3>Ringkasan Belanja</h3>
                            <p class="d-flex">
                                <span>Total Item</span>
                                <span><?php echo $cart_count; ?> item</span>
                            </p>
                            <p class="d-flex">
                                <span>Total Harga</span>
                                <span>Rp <?php echo number_format($total_bayar); ?></span>
                            </p>
                            <hr>
                            <!-- Sticky bottom navigation for mobile -->
                <div class="sticky-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="font-weight-bold">Total: Rp <?php echo number_format($total_bayar); ?></div>
                        <div class="small text-muted"><?php echo $cart_count; ?> item</div>
                    </div>
                    <form action="checkout.php" method="POST">
                        <input type="hidden" name="total_bayar" value="<?php echo $total_bayar; ?>">
                    </form>
                </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="shop.php" class="btn btn-secondary py-3 px-4">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Shop
                                </a>
                                <form action="checkout.php" method="POST" class="checkout-form">
                                    <input type="hidden" name="total_bayar" value="<?php echo $total_bayar; ?>">
                                    <button type="submit" name="checkout" class="btn btn-primary py-3 px-4">Checkout <i class="fas fa-arrow-right ml-2"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="ftco-footer ftco-section">
        <div class="container">
            <div class="row">
                <div class="mouse">
                    <a href="#" class="mouse-icon">
                        <div class="mouse-wheel"><span class="ion-ios-arrow-up"></span></div>
                    </a>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">Have a Questions?</h2>
                        <div class="block-23 mb-3">
                            <ul>
                                <li><span class="icon icon-map-marker"></span><span class="text">Jl. Kemuning No.06, Kec. Tarik, Kab. Sidoarjo</span></li>
                                <li><a href="#"><span class="icon icon-phone"></span><span class="text">0812345678910</span></a></li>
                                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">yasakakemuning@gmail.com</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved <i class="icon-heart color-danger" aria-hidden="true"></i>
                    </p>
                </div>
            </div>
        </div>
    </footer>
  
    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

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
        // Script to toggle note display
        $(document).ready(function() {
            $('.note-toggle').click(function() {
                $(this).next('.note-content').toggle();
            });
        });
    </script>
</body>
</html>
