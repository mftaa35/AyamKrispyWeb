<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <title>Yasaka Fried Chicken - Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap"
    rel="stylesheet">
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
              <div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-phone2"></span>
              </div>
              <span class="text">0812345678910</span>
            </div>
            <div class="col-md pr-4 d-flex topper align-items-center">
              <div class="icon mr-2 d-flex justify-content-center align-items-center"><span
                  class="icon-paper-plane"></span></div>
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
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
        aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active"><a href="index.html" class="nav-link">Home</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">Shop</a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
              <a class="dropdown-item" href="shop.php">Menu Ayam</a>
              <a class="dropdown-item" href="product-single.php">Deskripsi Menu</a>
              <a class="dropdown-item" href="cart.php">Detail Keranjang</a>
              <a class="dropdown-item" href="checkout.php">Checkout</a>
            </div>
          </li>
          <li class="nav-item"><a href="about.html" class="nav-link">About Us</a></li>
          <li class="nav-item cta cta-colored"><a href="cart.php" class="nav-link"><span
                class="icon-shopping_cart"></span></a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              Halo, <?php echo $_SESSION['nama']; ?>
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

  <section id="home-section" class="hero">
    <div class="home-slider owl-carousel">
      <div class="slider-item" style="background-image: url(images/yasaka1.jpg);">
        <div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

            <div class="col-md-12 ftco-animate text-center">
              <h1 class="mb-2">Ayam Krispi Hangat, Pesan Sekarang!</h1>
              <h2 class="subheading mb-4">Dari Dapur ke Rumah, Siap Antar!</h2>
              <p><a href="#" class="btn btn-primary">Lihat Detail</a></p>
            </div>

          </div>
        </div>
      </div>

      <div class="slider-item" style="background-image: url(images/yasaka2.jpg);">
        <div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

            <div class="col-sm-12 ftco-animate text-center">
              <h1 class="mb-2">Ayam Krispi Hangat, Pesan Sekarang!</h1>
              <h2 class="subheading mb-4">Dari Dapur ke Rumah, Siap Antar!</h2>
              <p><a href="#" class="btn btn-primary">Lihat Detail</a></p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section">
    <div class="container">
      <div class="row no-gutters ftco-services">
        <!-- Antar ke Rumah -->
        <div class="col-lg-3 text-center d-flex align-self-stretch ftco-animate">
          <div class="media block-6 services mb-md-0 mb-4">
            <div class="icon bg-color-1 active d-flex justify-content-center align-items-center mb-2">
              <span class="flaticon-shipped"></span>
            </div>
            <div class="media-body">
              <h3 class="heading">Antar Kerumah</h3>
              <span>Pesanan dikirim dengan cepat & aman</span>
            </div>
          </div>
        </div>

        <!-- Ayam Selalu Segar -->
        <div class="col-lg-3 text-center d-flex align-self-stretch ftco-animate">
          <div class="media block-6 services mb-md-0 mb-4">
            <div class="icon bg-color-2 d-flex justify-content-center align-items-center mb-2">
              <span class="flaticon-diet"></span>
            </div>
            <div class="media-body">
              <h3 class="heading">Ayam Selalu Segar</h3>
              <span>Diolah langsung saat dipesan</span>
            </div>
          </div>
        </div>

        <!-- Kualitas Terbaik -->
        <div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
          <div class="media block-6 services mb-md-0 mb-4">
            <div class="icon bg-color-3 d-flex justify-content-center align-items-center mb-2">
              <span class="flaticon-award"></span>
            </div>
            <div class="media-body">
              <h3 class="heading">Kualitas Terbaik</h3>
              <span>Rasa gurih, renyah, dan mantap</span>
            </div>
          </div>
        </div>

        <!-- Layanan Pelanggan -->
        <div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
          <div class="media block-6 services mb-md-0 mb-4">
            <div class="icon bg-color-4 d-flex justify-content-center align-items-center mb-2">
              <span class="flaticon-customer-service"></span>
            </div>
            <div class="media-body">
              <h3 class="heading">Layanan Pelanggan</h3>
              <span>Siap bantu setiap saat</span>
            </div>
          </div>
        </div>
      </div>
  </section>


  <section class="ftco-section ftco-category ftco-no-pt">
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-6 order-md-last align-items-stretch d-flex">
              <div class="category-wrap-2 ftco-animate img align-self-stretch d-flex"
                style="background-image: url(images/ayamori.jpg);">
              </div>
            </div>
            <div class="col-md-6">
              <div class="category-wrap ftco-animate img mb-4 d-flex align-items-end"
                style="background-image: url(images/ayamori.jpg);">
                <div class="text px-3 py-1">
                  <h2 class="mb-0"><a href="#">Ayam Krispi Original</a></h2>
                </div>
              </div>
              <div class="category-wrap ftco-animate img d-flex align-items-end"
                style="background-image: url(images/ayamgeprek.jpg);">
                <div class="text px-3 py-1">
                  <h2 class="mb-0"><a href="#">Ayam Geprek</a></h2>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="category-wrap ftco-animate img mb-4 d-flex align-items-end"
            style="background-image: url(images/fotoayambakar.jpg);">
            <div class="text px-3 py-1">
              <h2 class="mb-0"><a href="#">ayam bakar crispy</a></h2>
            </div>
          </div>
          <div class="category-wrap ftco-animate img d-flex align-items-end"
            style="background-image: url(images/ayambakarmentai.jpg);">
            <div class="text px-3 py-1">
              <h2 class="mb-0"><a href="#">ayam bakar mentai</a></h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section text-center ftco-animate">
            <h2 class="mb-4">Menu Lainnya</h2>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="product">
              <a href="#" class="img-prod"><img class="img-fluid" src="images/ayamkeranjang.jpg" alt="Colorlib Template">
                <div class="overlay"></div>
              </a>
              <div class="text py-3 pb-4 px-3 text-center">
                <h3><a href="#">ayam keranjang</a></h3>
                <div class="d-flex">
                  <div class="pricing">
                    <p class="price"><span class="mr-2 price-dc">Rp. 33.000</span><span class="price-sale">Rp.
                        32.500</span></p>
                  </div>
                </div>
                <div class="bottom-area d-flex px-3">
                  <-- Tombol Add to Cart -->
                  <!-- <div class="bottom-area d-flex justify-content-center">
                    <button
                      style="border: 1px solid black; padding: 8px 20px; background: white; color: black; cursor: pointer; border-radius: 4px;">
                      Add to cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="product">
              <a href="#" class="img-prod"><img class="img-fluid" src="images/ayambig.jpeg" alt="Colorlib Template">
                <div class="overlay"></div>
              </a>
              <div class="text py-3 pb-4 px-3 text-center">
                <h3><a href="#">Ayam Big</a></h3>
                <div class="d-flex">
                  <div class="pricing">
                    <p class="price"><span>Rp. 65.000</span></p>
                  </div>
                </div>
                <div class="bottom-area d-flex px-3">
                  <-- Tombol Add to Cart -->
                  <!-- <div class="bottom-area d-flex justify-content-center">
                    <button
                      style="border: 1px solid black; padding: 8px 20px; background: white; color: black; cursor: pointer; border-radius: 4px;">
                      Add to cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="product">
              <a href="#" class="img-prod"><img class="img-fluid" src="images/fotoayambakar.jpg" alt="Colorlib Template">
                <div class="overlay"></div>
              </a>
              <div class="text py-3 pb-4 px-3 text-center">
                <h3><a href="#">ayam bakar kecil</a></h3>
                <div class="d-flex">
                  <div class="pricing">
                    <p class="price"><span>Rp. 15.000</span></p>
                  </div>
                </div>
                <div class="bottom-area d-flex px-3">
                  <-- Tombol Add to Cart -->
                  <!-- <div class="bottom-area d-flex justify-content-center">
                    <button
                      style="border: 1px solid black; padding: 8px 20px; background: white; color: black; cursor: pointer; border-radius: 4px;">
                      Add to cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
          <!-- <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="product">
              <a href="#" class="img-prod"><img class="img-fluid" src="images/fotoayambakar.jpg" alt="Colorlib Template">
                <div class="overlay"></div>
              </a>
              <div class="text py-3 pb-4 px-3 text-center">
                <h3><a href="#">ayam bakar besar</a></h3>
                <div class="d-flex">
                  <div class="pricing">
                    <p class="price"><span>Rp. 19.000</span></p>
                  </div>
                </div> -->
                <!-- <div class="bottom-area d-flex px-3"> -->
                  <!-- Tombol Add to Cart -->
                  <!-- <div class="bottom-area d-flex justify-content-center">
                    <button
                      style="border: 1px solid black; padding: 8px 20px; background: white; color: black; cursor: pointer; border-radius: 4px;">
                      Add to cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
  
  
          <!-- <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="product">
              <a href="#" class="img-prod"><img class="img-fluid" src="images/ayammadu.jpeg" alt="Colorlib Template">
                <span class="status">30%</span>
                <div class="overlay"></div>
              </a>
              <div class="text py-3 pb-4 px-3 text-center">
                <h3><a href="#">ayam bakar madu</a></h3>
                <div class="d-flex">
                  <div class="pricing">
                    <p class="price"><span class="mr-2 price-dc">Rp. 18.000</span><span class="price-sale">Rp.
                        15.000</span></p>
                  </div>
                </div>
                <div class="bottom-area d-flex px-3"> -->
                  <!-- Tombol Add to Cart -->
                  <!-- <div class="bottom-area d-flex justify-content-center">
                    <button
                      style="border: 1px solid black; padding: 8px 20px; background: white; color: black; cursor: pointer; border-radius: 4px;">
                      Add to cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="product">
              <a href="#" class="img-prod"><img class="img-fluid" src="images/ayamori.jpg" alt="Colorlib Template">
                <div class="overlay"></div>
              </a>
              <div class="text py-3 pb-4 px-3 text-center">
                <h3><a href="#">yummy 1</a></h3>
                <div class="d-flex">
                  <div class="pricing">
                    <p class="price"><span>Rp. 13.000</span></p>
                  </div>
                </div>
                <div class="bottom-area d-flex px-3"> -->
                  <!-- Tombol Add to Cart -->
                  <!-- <div class="bottom-area d-flex justify-content-center">
                    <button
                      style="border: 1px solid black; padding: 8px 20px; background: white; color: black; cursor: pointer; border-radius: 4px;">
                      Add to cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="product">
              <a href="#" class="img-prod"><img class="img-fluid" src="images/ayamori.jpg" alt="Colorlib Template">
                <div class="overlay"></div>
              </a>
              <div class="text py-3 pb-4 px-3 text-center">
                <h3><a href="#">yummy 2</a></h3>
                <div class="d-flex">
                  <div class="pricing">
                    <p class="price"><span>Rp. 16.000</span></p>
                  </div>
                </div>
                <div class="bottom-area d-flex px-3"> -->
                  <!-- Tombol Add to Cart -->
                  <!-- <div class="bottom-area d-flex justify-content-center">
                    <button
                      style="border: 1px solid black; padding: 8px 20px; background: white; color: black; cursor: pointer; border-radius: 4px;">
                      Add to cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="product">
              <a href="#" class="img-prod"><img class="img-fluid" src="images/ayamselimut.jpg" alt="Colorlib Template">
                <div class="overlay"></div>
              </a>
              <div class="text py-3 pb-4 px-3 text-center">
                <h3><a href="#">Ayam Selimut</a></h3>
                <div class="d-flex">
                  <div class="pricing">
                    <p class="price"><span>Rp. 20.000</span></p>
                  </div>
                </div>
                <div class="bottom-area d-flex px-3"> -->
                  <!-- Tombol Add to Cart -->
                  <!-- <div class="bottom-area d-flex justify-content-center">
                    <button
                      style="border: 1px solid black; padding: 8px 20px; background: white; color: black; cursor: pointer; border-radius: 4px;">
                      Add to cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> -->


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
              <li><span class="icon icon-map-marker"></span><span class="text">Jl. Kemuning No.06, Kec. Tarik, Kab.
                  Sidoarjo</span></li>
              <li><a href="#"><span class="icon icon-phone"></span><span class="text">0812345678910</span></a></li>
              <li><a href="#"><span class="icon icon-envelope"></span><span
                    class="text">yasakakemuning@gmail.com</span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 text-center">

        <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
          Copyright &copy;
          <script>document.write(new Date().getFullYear());</script> All rights reserved <i
            class="icon-heart color-danger" aria-hidden="true"></i> <a href="https://colorlib.com" target="_blank"></a>
          <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
        </p>
      </div>
    </div>
    </div>
  </footer>



  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
        stroke="#F96D00" />
    </svg></div>


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
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>

</body>

</html>