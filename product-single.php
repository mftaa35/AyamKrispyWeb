<?php session_start(); 
include "config.php";

// Mengambil semua menu dari database
$result = $conn->query("SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Yasaka Fried Chicken - Detail Menu</title>
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
  
  <style>
    .product-section {
      padding: 2rem 0;
    }
    .product-img {
      max-height: 250px;
      object-fit: cover;
    }
    .product-card {
      margin-bottom: 2rem;
      border: 1px solid #f0f0f0;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .product-info {
      padding: 1rem;
    }
    .product-title {
      font-size: 1.3rem;
      margin-bottom: 0.5rem;
    }
    .product-price {
      color: #82ae46;
      font-weight: bold;
      margin-bottom: 0.5rem;
    }
    .product-desc {
      font-size: 0.9rem;
      margin-bottom: 1rem;
      max-height: 100px;
      overflow: hidden;
    }
    .product-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .select-wrap {
      width: 120px;
    }
    .quantity-control {
      width: 120px;
    }
    .add-to-cart {
      margin-top: 1rem;
      width: 100%;
    }
  </style>
</head>
<body class="goto-here">
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
  
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="index.html">Yasaka Fried Chicken</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active"><a href="index.html" class="nav-link">Home</a></li>
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
              Halo, <?php echo $_SESSION['nama']; ?>
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

  <div class="hero-wrap hero-bread" style="background-image: url('images/yasaka1.jpg'); height: 300px;">
    <div class="container">
      <div class="row no-gutters slider-text align-items-center justify-content-center">
        <div class="col-md-9 ftco-animate text-center">
          <h1 class="mb-0 bread">Deskripsi Menu</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center mb-4">
      <div class="col-12 text-center">
        <h4 class="fw-bold">Daftar Menu</h4>
      </div>
    </div>

    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card mb-4 shadow-sm">
          <div class="row g-0 align-items-center">
            <!-- Gambar -->
            <div class="col-12 col-md-2 text-center p-2">
              <img src="images/<?php echo htmlspecialchars($row['menu_image']); ?>" 
                   alt="<?php echo htmlspecialchars($row['menu_name']); ?>" 
                   class="img-fluid rounded" style="max-height: 100px; object-fit: cover;">
            </div>

            <!-- Nama & Deskripsi -->
            <div class="col-12 col-md-5 p-3">
              <h5 class="mb-1 fw-bold"><?php echo htmlspecialchars($row['menu_name']); ?></h5>
              <p class="mb-0 text-muted small"><?php echo htmlspecialchars($row['deskripsi'] ?? ''); ?></p>
              <?php if (strpos(strtolower($row['menu_name']), 'best seller') !== false || strpos(strtolower($row['deskripsi']), 'best seller') !== false): ?>
                <span class="badge bg-danger mt-1">Best Seller</span>
              <?php endif; ?>
            </div>

            <!-- Form Tambah ke Keranjang -->
            <div class="col-12 col-md-5 p-3">
              <form action="cart.php" method="post">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                  <div class="w-100">
                    <strong class="text-primary">Rp. <?php echo number_format($row['menu_price'], 0, ',', '.'); ?></strong>
                    <input type="hidden" name="menu_name" value="<?php echo htmlspecialchars($row['menu_name']); ?>">
                    <input type="hidden" name="menu_price" value="<?php echo $row['menu_price']; ?>">
                    <input type="hidden" name="menu_image" value="<?php echo htmlspecialchars($row['menu_image']); ?>">
                  </div>
                  <input type="number" name="quantity" value="1" min="1" class="form-control w-100" placeholder="Qty" required>
                </div>
                <textarea name="note" class="form-control mt-2" rows="2" placeholder="Catatan (opsional)"></textarea>
                <button type="submit" name="add_to_cart" class="btn btn-sm btn-primary mt-2 w-100">Tambah ke Keranjang</button>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center">
        <p class="text-muted">Belum ada menu tersedia.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

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
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>

  <script>
    $(document).ready(function(){
      // Quantity control for each product
      $('.quantity-right-plus').click(function(e){
        e.preventDefault();
        var fieldName = $(this).data('field');
        var quantity = parseInt($('input[name="'+fieldName+'"]').val());
        $('input[name="'+fieldName+'"]').val(quantity + 1);
      });

      $('.quantity-left-minus').click(function(e){
        e.preventDefault();
        var fieldName = $(this).data('field');
        var quantity = parseInt($('input[name="'+fieldName+'"]').val());
        if(quantity > 1){
          $('input[name="'+fieldName+'"]').val(quantity - 1);
        }
      });
    });
  </script>
</body>
</html>
