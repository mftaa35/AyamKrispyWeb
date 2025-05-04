<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f7f9fb;
    }

    .sidebar {
      width: 245px;
      background-color: #4CAF50;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      color: #fff;
      padding: 20px;
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .sidebar .profile {
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar .profile h2 {
      margin: 10px 0 5px;
      font-size: 20px;
    }

    .sidebar .profile p {
      font-size: 14px;
      color: #dfe6e9;
    }

    .sidebar .menu ul {
      list-style: none;
    }

    .sidebar .menu li {
      margin: 15px 0;
    }

    .sidebar .menu a {
      color: #fff;
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 10px;
      border-radius: 8px;
      transition: background 0.3s;
    }

    .sidebar .menu a:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .sidebar .menu a i {
      margin-right: 10px;
      min-width: 20px;
      text-align: center;
    }

    .main-content {
      margin-left: 245px;
      padding: 20px;
      transition: all 0.3s ease;
    }

    .topbar {
      background: #ffffff;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 15px;
      margin-bottom: 20px;
    }

    .toggle-menu {
      font-size: 24px;
      cursor: pointer;
      color: #4CAF50;
      display: none;
    }

    .info-box {
      background: #ffffff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    .info-box h2 {
      color: #4CAF50;
      margin-bottom: 10px;
    }

    .info-box p {
      color: #555;
      line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        position: fixed;
        width: 70px;
        padding: 15px 10px;
      }

      .sidebar .profile h2,
      .sidebar .profile p,
      .sidebar .menu span {
        display: none;
      }

      .main-content {
        margin-left: 70px;
      }

      .toggle-menu {
        display: block;
      }
    }

    @media (max-width: 480px) {
      .topbar h1 {
        font-size: 18px;
      }

      .info-box h2 {
        font-size: 20px;
      }

      .info-box p {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="profile">
      <h2>Administrator</h2>
      <p>Admin</p>
    </div>
    <div class="menu">
      <ul>
        <li><a href="admindashboard.php"><i class="fas fa-home"></i><span>Beranda</span></a></li>
        <li><a href="admin_page.php"><i class="fas fa-utensils"></i><span>Menu</span></a></li>
        <li><a href="admin_pesanan.php"><i class="fas fa-shopping-cart"></i><span>Pesanan</span></a></li>
        <li><a href="admin_pengguna.php"><i class="fas fa-users"></i><span>Pelanggan</span></a></li>
        <li><a href="laporan.php"><i class="fas fa-file-alt"></i><span>Laporan</span></a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
      </ul>
    </div>
  </div>

  <div class="main-content" id="main-content">
    <div class="topbar">
      <span class="toggle-menu" id="toggleBtn"><i class="fas fa-bars"></i></span>
      <h1>Dashboard Admin</h1>
    </div>

    <div class="info-box">
      <h2>Yasaka Fried Chicken</h2>
      <p>Jl. Kemuning, Sidoarjo</p>
    </div>
  </div>

  <script>
    document.getElementById('toggleBtn').addEventListener('click', function() {
      const sidebar = document.getElementById('sidebar');
      const main = document.getElementById('main-content');

      if (sidebar.style.width === '70px') {
        sidebar.style.width = '245px';
        main.style.marginLeft = '245px';

        // Tampilkan teks
        sidebar.querySelectorAll('span, .profile h2, .profile p').forEach(e => e.style.display = '');
      } else {
        sidebar.style.width = '70px';
        main.style.marginLeft = '70px';

        // Sembunyikan teks
        sidebar.querySelectorAll('span, .profile h2, .profile p').forEach(e => e.style.display = 'none');
      }
    });
  </script>
</body>
</html>
