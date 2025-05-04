<?php
include 'config.php';
session_start();

// Misalnya user login pakai user_id 1
$user_id = 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    // Ambil data dari form
    $menu_name = mysqli_real_escape_string($conn, $_POST['menu_name']);
    $menu_price = (int)$_POST['menu_price'];
    $menu_image = mysqli_real_escape_string($conn, $_POST['menu_image']);
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $note = isset($_POST['note']) ? mysqli_real_escape_string($conn, $_POST['note']) : '';

    // Cek apakah item sudah ada di keranjang
    $check_query = "SELECT * FROM keranjang WHERE user_id = '$user_id' AND menu_name = '$menu_name'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Jika sudah ada, update jumlah dan catatan
        $existing = mysqli_fetch_assoc($check_result);
        $new_quantity = $existing['quantity'] + $quantity;
        $new_note = trim($existing['note'] . "\n" . $note);

        $update_query = "UPDATE keranjang SET quantity = '$new_quantity', note = '$new_note'
                         WHERE id = '{$existing['id']}'";

        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Jumlah produk diperbarui di keranjang!'); window.location.href='cart.php';</script>";
        } else {
            echo "Gagal update keranjang: " . mysqli_error($conn);
        }
    } else {
        // Jika belum ada, tambahkan ke keranjang
        $query = "INSERT INTO keranjang (user_id, menu_name, menu_price, menu_image, quantity, note)
                  VALUES ('$user_id', '$menu_name', '$menu_price', '$menu_image', '$quantity', '$note')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Produk berhasil ditambahkan ke keranjang!'); window.location.href='cart.php';</script>";
        } else {
            echo "Gagal tambah ke keranjang: " . mysqli_error($conn);
        }
    }
}

// Hitung jumlah item di keranjang untuk tampilan badge
$count_query = "SELECT SUM(quantity) as total_items FROM keranjang WHERE user_id = '$user_id'";
$count_result = mysqli_query($conn, $count_query);
$count_data = mysqli_fetch_assoc($count_result);
$cart_count = $count_data['total_items'] ? $count_data['total_items'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
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
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="index.html">Yasaka Fried Chicken</a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item active"><a href="dashboard.php" class="nav-link">Home</a></li>
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
	          <li class="nav-item cta cta-colored"><a href="cart.php" class="nav-link"><span class="icon-shopping_cart"></span>[<?php echo $cart_count; ?>]</a></li>
	          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              Halo, <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Pengguna'; ?>
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
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.html"></a></span> <span></span></p>
            <h1 class="mb-0 bread">Keranjangku</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section ftco-cart">
  <div class="container">
    <div class="row">
      <div class="col-md-12 ftco-animate">
        <div class="cart-list">
          <table class="table">
            <thead class="thead-primary">
              <tr class="text-center">
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>Nama Menu</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT * FROM keranjang WHERE user_id = '$user_id'";
              $result = mysqli_query($conn, $query);
              $total_bayar = 0;

              if (mysqli_num_rows($result) > 0) {
                  while ($product = mysqli_fetch_assoc($result)) {
                      $jumlah = $product['quantity'];
                      $subtotal = $product['menu_price'] * $jumlah;
                      $total_bayar += $subtotal;
                      $note = !empty($product['note']) ? htmlspecialchars($product['note']) : '-';
                      
                      // Fix for image path display
                      $image_path = $product['menu_image'];
                      // If the image path doesn't start with http:// or https:// or a slash, add a leading slash
                      if (!preg_match("/^(http|https):\/\//", $image_path) && substr($image_path, 0, 1) !== '/') {
                          $image_path = '/' . $image_path;
                      }

                      echo "<tr class='text-center'>
                              <td class='product-remove'><a href='hapusitem.php?id={$product['id']}'><span class='icon-close'></span></a></td>
                              <td class='menu_image'><img src='{$image_path}' alt='' style='width: 70px; height: auto;'></td>
                              <td class='menu_name'>{$product['menu_name']}</td>
                              <td class='menu_price'>Rp " . number_format($product['menu_price']) . "</td>
                              <td class='quantity'>{$jumlah}</td>
                              <td class='note'>{$note}</td>
                              <td class='total'>Rp " . number_format($subtotal) . "</td>
                            </tr>";
                  }
              } else {
                  echo "<tr><td colspan='7' class='text-center'>Keranjang kosong.</td></tr>";
              }
              ?>
            </tbody>
          </table>

          <div class="row justify-content-end">
            <div class="col-lg-6 mt-5 cart-wrap ftco-animate">
              <div class="cart-total mb-3">
                <h3>Total Belanja</h3>
                <p class="d-flex">
                  <span>Total Harga</span>
                  <span>Rp <?php echo number_format($total_bayar); ?></span>
                </p>
                <hr>
                <a href="shop.php" class="btn btn-secondary py-3 px-4">Kembali ke Shop</a>

                <?php if ($total_bayar > 0): ?>
                <form action="checkout.php" method="POST">
                  <input type="hidden" name="total_bayar" value="<?php echo $total_bayar; ?>">
                  <button type="submit" name="checkout" class="btn btn-primary py-3 px-4">Lanjut ke Checkout</button>
                </form>
                <?php endif; ?>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
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

			<p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved <i class="icon-heart color-danger" aria-hidden="true"></i>
				<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
			</p>          </div>
        </div>
      </div>
    </footer>
    
  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

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
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>

  <script>
		$(document).ready(function(){

		var quantitiy=0;
		   $('.quantity-right-plus').click(function(e){
		        
		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt($('#quantity').val());
		        
		        // If is not undefined
		            
		            $('#quantity').val(quantity + 1);

		          
		            // Increment
		        
		    });

		     $('.quantity-left-minus').click(function(e){
		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt($('#quantity').val());
		        
		        // If is not undefined
		      
		            // Increment
		            if(quantity>0){
		            $('#quantity').val(quantity - 1);
		            }
		    });
		    
		});
	</script>
    
  </body>
</html>
