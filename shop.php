<?php
session_start();
include "config.php";

// Mengambil semua menu dari database
$result = $conn->query("SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Yasaka Fried Chicken - Menu</title>
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
      /* Tambahan style untuk kartu produk */
      .product .img-prod {
        height: 240px;
        position: relative;
        overflow: hidden;
      }
      .product .img-prod img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      .product .text {
        min-height: 200px;
        display: flex;
        flex-direction: column;
      }
      .note-input {
        width: 100%;
        margin-bottom: 5px;
        font-size: 12px;
        padding: 5px;
      }
      .quantity-input {
        width: 60px;
        text-align: center;
        margin-bottom: 5px;
      }
      .add-to-cart-btn {
        width: 100%;
        background-color: #82ae46;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
      }
      .add-to-cart-btn:hover {
        background-color: #6b9237;
      }
      .bottom-area {
        margin-top: auto;
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
	          <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
	          <li class="nav-item active dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shop</a>
              <div class="dropdown-menu" aria-labelledby="dropdown04">
				<a class="dropdown-item" href="shop.php">Menu Ayam</a>
                <a class="dropdown-item" href="product-single.php">Deskripsi Menu</a>
                <a class="dropdown-item" href="cart.php">Detail Keranjang</a>
                <a class="dropdown-item" href="checkout.php">Checkout</a>
              </div>
            </li>
	          <li class="nav-item"><a href="about.html" class="nav-link">About</a></li>
			  <li class="nav-item cta cta-colored"><a href="cart.php" class="nav-link"><span class="icon-shopping_cart"></span>[0]</a></li>
	          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              Halo, <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Pengunjung'; ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
			<a class="dropdown-item" href="logout.php">Logout</a>
			<a class="dropdown-item" href="pesanan.php">Riwayat Pesanan</a>
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
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.html"></a></span>
            <h1 class="mb-0 bread">Menu Yasaka</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
    	<div class="container">
    		<div class="row justify-content-center">
    			<div class="col-md-10 mb-5 text-center">
    				<ul class="product-category">
    					<li><a href="#" class="active">Semua Menu</a></li>
    				</ul>
    			</div>
    		</div>
    		<div class="row">
          <!-- Mulai loop menu dari database -->
          <?php
          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
          ?>
    			<div class="col-md-6 col-lg-3 ftco-animate">
    				<div class="product">
    					<a href="#" class="img-prod">
              <img class="img-fluid" src="images/<?php echo htmlspecialchars($row['menu_image']); ?>" alt="<?php echo htmlspecialchars($row['menu_name']); ?>">
              <?php if (strpos(strtolower($row['menu_name']), 'best seller') !== false || strpos(strtolower($row['deskripsi']), 'best seller') !== false): ?>
              <span class="status">best seller</span>
              <?php endif; ?>
    						<div class="overlay"></div>
    					</a>
    					<div class="text py-3 pb-4 px-3 text-center">
    						<h3><a href="#"><?php echo htmlspecialchars($row['menu_name']); ?></a></h3>
    						<div class="d-flex">
    							<div class="pricing">
		    						<p class="price"><span>Rp. <?php echo number_format($row['menu_price'], 0, ',', '.'); ?></span></p>
		    					</div>
	    					</div>
                <div class="bottom-area d-flex px-3">
                  <div class="m-auto d-flex w-100 justify-content-center">
                    <form action="cart.php" method="post" class="w-100">
                      <input type="hidden" name="menu_name" value="<?php echo htmlspecialchars($row['menu_name']); ?>">
                      <input type="hidden" name="menu_price" value="<?php echo $row['menu_price']; ?>">
                      <input type="hidden" name="menu_image" value="<?php echo htmlspecialchars($row['menu_image']); ?>">
                      <input type="number" name="quantity" value="1" min="1" class="quantity-input" required>
                      <textarea name="note" placeholder="Catatan (opsional)" class="note-input"></textarea>
                      <button type="submit" name="add_to_cart" class="add-to-cart-btn">Tambah ke Keranjang</button>
                    </form>
                  </div>
                </div>
    					</div>
    				</div>
    			</div>
          <?php
            }
          } else {
            echo '<div class="col-12 text-center"><p>Belum ada menu tersedia.</p></div>';
          }
          ?>
          <!-- Akhir loop menu dari database -->
    		</div>

    		<div class="row mt-5">
          <div class="col text-center">
            <div class="block-27">
              <ul>
                <li><a href="#">&lt;</a></li>
                <li class="active"><span>1</span></li>
                <li><a href="#">&gt;</a></li>
              </ul>
            </div>
          </div>
        </div>
    	</div>
    </section>

		<section class="ftco-section ftco-no-pt ftco-no-pb py-5 bg-light">
      <!-- Konten tambahan bisa ditambahkan di sini -->
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
  
			  <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
							Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved <i class="icon-heart color-danger" aria-hidden="true"></i>
							<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
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
    
  </body>
</html>