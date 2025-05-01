<?php
session_start();
include 'config.php';

// Proteksi: hanya admin@gmail.com yang boleh masuk
// if (!isset($_SESSION['pengguna_email']) || $_SESSION['pengguna_email'] != 'admin@gmail.com') {
//     header("Location: admindashboard.php"); // Redirect ke login admin jika bukan admin
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f7f9fb;
    }
  
    .sidebar {
      width: 245px;
      background-color: #4CAF50;
      position: fixed;
      top: 0; left: 0; bottom: 0;
      color: #fff;
      padding: 20px;
      transition: all 0.3s ease;
    }
  
    .sidebar .profile {
      text-align: center;
      margin-bottom: 30px;
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
  
    .sidebar .menu ul {
      list-style: none;
      padding-left: 0;
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
    }
  
    .main-content {
      margin-left: 245px;
      padding: 20px;
      transition: all 0.3s ease;
    }
  
    .topbar {
      background: #ffffff;
      padding: 30px 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 15px;
    }
  
    .toggle-menu {
      font-size: 20px;
      cursor: pointer;
      color: #4CAF50;
    }
  
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin: 20px 0;
    }
  
    .card {
      background: linear-gradient(135deg, #e0f7fa, #ffffff);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      transition: transform 0.2s;
    }
  
    .card:hover {
      transform: translateY(-5px);
    }
  
    .card h3 {
      font-size: 32px;
      color: #2c3e50;
    }
  
    .card p {
      font-size: 14px;
      color: #7f8c8d;
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
  </style>
  
</head>
<body>
  <div class="sidebar">
    <div class="profile">
      <!-- <img src="profile.jpg" alt="Profile"> -->
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
      <span class="toggle-menu"><i class="fas fa-bars"></i></span>
      <h1>Dashboard Kurir</h1>
    </div>

    <div class="info-box">
      <h2>Yasaka Fried Chicken</h2>
      <p>Jl. Kemuning, Sidoarjo</p>
    </div>
  </div>

  <script>
    document.querySelector('.toggle-menu').addEventListener('click', function() {
      const sidebar = document.querySelector('.sidebar');
      const main = document.querySelector('.main-content');

      if (sidebar.style.width === '70px') {
        sidebar.style.width = '245px';
        main.style.marginLeft = '245px';
      } else {
        sidebar.style.width = '70px';
        main.style.marginLeft = '70px';
      }
    });
  </script>
</body>
</html>
