<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        // Cek user berdasarkan email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Periksa password (pastikan password di DB sudah di-hash)
                if (password_verify($password, $user['password'])) {
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = 'user';
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Password salah.";
                }
            } else {
                $error = "Email tidak ditemukan.";
            }

            $stmt->close();
        } else {
            $error = "Gagal memproses permintaan (prepare statement gagal).";
        }
    } else {
        $error = "Email dan password harus diisi.";
    }
}

// Menampilkan pesan error jika ada
if (!empty($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>



<?php if (!empty($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<!DOCTYPE html>

<html lang="en">

<head>
  <title>Login/Signup - Yasaka Fried Chicken</title>
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

  <style>
    .login-wrap {
      padding: 50px 0;
      background: #f7f6f2;
    }

    .form-box {
      background: white;
      padding: 30px;
      border-radius: 5px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .tab-header {
      margin-bottom: 20px;
    }

    .tab-header button {
      background: none;
      border: none;
      font-size: 18px;
      font-weight: 500;
      padding: 10px 20px;
      color: #82ae46;
      opacity: 0.6;
      position: relative;
      cursor: pointer;
    }

    .tab-header button.active {
      opacity: 1;
    }

    .tab-header button.active:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 20px;
      right: 20px;
      height: 3px;
      background: #82ae46;
    }

    .form-group {
      margin-bottom: 25px;
    }

    .form-control {
      height: 50px;
      border-radius: 0;
      border: 1px solid #e6e6e6;
    }

    .form-control:focus {
      border-color: #82ae46;
      box-shadow: none;
    }

    .btn-primary {
      background: #82ae46;
      border-color: #82ae46;
      height: 50px;
      border-radius: 0;
    }

    .btn-primary:hover {
      background: #72a139;
      border-color: #72a139;
    }

    .forgot-link {
      color: #82ae46;
      text-decoration: none;
    }

    .forgot-link:hover {
      text-decoration: underline;
    }

    .divider {
      display: flex;
      align-items: center;
      margin: 20px 0;
    }

    .divider-line {
      flex-grow: 1;
      height: 1px;
      background: #e6e6e6;
    }

    .divider-text {
      padding: 0 15px;
      color: #999;
    }

    .social-login {
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .social-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: #f8f9fa;
      color: #333;
      font-size: 20px;
      transition: all 0.3s ease;
    }

    .social-btn:hover {
      background: #eaecef;
    }

    .signup-tab,
    .login-tab {
      display: none;
    }

    .active-tab {
      display: block;
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

  <section class="login-wrap">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="form-box">
            <div class="tab-header text-center">
              <button id="login-tab-btn" class="active">Login</button>
              <button id="signup-tab-btn">Sign Up</button>
            </div>

            <!-- Login Form -->
            <div id="login-tab" class="login-tab active-tab">
              <form action="login.php" method="post">
                <div class="form-group">
                  <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary">MASUK</button>
                </div>
              </form>
            </div>

            <!-- Signup Form -->
            <div id="signup-tab" class="signup-tab">
              <form action="signup.php" method="post">
                <div class="form-group">
                  <label for="nama">Nama Lengkap</label>
                  <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                  <label for="no_telepon">Nomor Telepon</label>
                  <input type="tel" class="form-control" id="no_telepon" name="no_telepon" required>
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <input type="text" class="form-control" id="alamat" name="alamat" required>
                </div>
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <!-- <div class="form-group">
                  <label for="confirm_password">Konfirmasi Password</label>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div> -->
                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-block">DAFTAR</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="ftco-footer ftco-section">
    <div class="container">
      <div class="row">
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
          <p>
            Copyright &copy;
            <script>document.write(new Date().getFullYear());</script> All rights reserved <i
              class="icon-heart color-danger" aria-hidden="true"></i>
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
  <script src="js/main.js"></script>

  <script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function () {
      const loginTabBtn = document.getElementById('login-tab-btn');
      const signupTabBtn = document.getElementById('signup-tab-btn');
      const loginTab = document.getElementById('login-tab');
      const signupTab = document.getElementById('signup-tab');

      loginTabBtn.addEventListener('click', function () {
        loginTabBtn.classList.add('active');
        signupTabBtn.classList.remove('active');
        loginTab.classList.add('active-tab');
        signupTab.classList.remove('active-tab');
      });

      signupTabBtn.addEventListener('click', function () {
        signupTabBtn.classList.add('active');
        loginTabBtn.classList.remove('active');
        signupTab.classList.add('active-tab');
        loginTab.classList.remove('active-tab');
      });
    });
  </script>

</body>

</html>