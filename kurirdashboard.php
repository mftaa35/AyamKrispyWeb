<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Kurir</title>
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
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 245px;
      background-color: #4CAF50;
      color: #fff;
      padding: 20px;
      flex-shrink: 0;
      transition: all 0.3s ease;
    }

    .sidebar.collapsed {
      width: 70px;
    }

    .sidebar .profile {
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar.collapsed .profile h2,
    .sidebar.collapsed .profile p,
    .sidebar.collapsed .menu span {
      display: none;
    }

    .sidebar .profile img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      border: 3px solid #fff;
    }

    .sidebar .profile h2 {
      margin: 10px 0 5px;
      font-size: 20px;
    }

    .sidebar .profile p {
      font-size: 14px;
      color: #dfe6e9;
    }

    .menu ul {
      list-style: none;
      padding: 0;
    }

    .menu li {
      margin: 15px 0;
    }

    .menu a {
      color: #fff;
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 10px;
      border-radius: 8px;
      transition: background 0.3s;
    }

    .menu a:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .menu a i {
      margin-right: 10px;
    }

    .main-content {
      flex-grow: 1;
      padding: 20px;
      transition: all 0.3s ease;
    }

    .topbar {
      background: #ffffff;
      padding: 15px 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 15px;
      margin-bottom: 20px;
    }

    .toggle-menu {
      font-size: 22px;
      cursor: pointer;
      color: #4CAF50;
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

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }

      .sidebar {
        position: fixed;
        height: 100%;
        z-index: 1000;
        transform: translateX(-100%);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
      }

      .topbar {
        position: sticky;
        top: 0;
        z-index: 999;
        background: #fff;
      }
    }
  </style>
</head>

<body>
  <div class="sidebar" id="sidebar">
    <div class="profile">
      <h2>Administrator</h2>
      <p>Kurir</p>
    </div>
    <div class="menu">
      <ul>
        <li><a href="kurirdashboard.php"><i class="fas fa-home"></i><span>Beranda</span></a></li>
        <li><a href="kurir_pesanan.php"><i class="fas fa-shopping-cart"></i><span>Pesanan</span></a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
      </ul>
    </div>
  </div>

  <div class="main-content">
    <div class="topbar">
      <span class="toggle-menu" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
      <h1>Dashboard Kurir</h1>
    </div>

    <div class="info-box">
      <h2>Yasaka Fried Chicken</h2>
      <p>Jl. Kemuning, Sidoarjo</p>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const isMobile = window.innerWidth <= 768;

      if (isMobile) {
        sidebar.classList.toggle('active');
      } else {
        sidebar.classList.toggle('collapsed');
      }
    }
  </script>
</body>
</html>
