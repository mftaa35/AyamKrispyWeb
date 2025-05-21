<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (nama, no_telepon, alamat, email, password) 
              VALUES ('$nama', '$no_telepon', '$alamat', '$email', '$password')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pengguna berhasil ditambahkan'); window.location='admin_pengguna.php';</script>";
    } else {
        echo "Gagal menambahkan pengguna: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
     <style>
    body {
            font-family: 'Poppins', sans-serif;
            background-color: #ecf0f1;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #4CAF50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .main-content {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
        }

        h2 {
            font-size: 22px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 12px 0 6px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: 0.3s;
            background: #f9f9f9;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
            background: #fff;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #4CAF50;
            font-weight: bold;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
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
        <li><a href="admin_pengguna.php"><i class="fas fa-users"></i><span>Pengguna</span></a></li>
        <li><a href="laporan.php"><i class="fas fa-file-alt"></i><span>Laporan</span></a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
      </ul>
    </div>
  </div>

<div class="main-content">
    <div class="form-container">
        <h2>Tambah Pengguna</h2>
        <form method="POST">
            <label>Nama:</label>
            <input type="text" name="nama" required>
            
            <label>No Telepon:</label>
            <input type="text" name="no_telepon" required>
            
            <label>Alamat:</label>
            <input type="text" name="alamat" required>
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <input type="submit" value="Simpan">
        </form>
        <a href="admin_pengguna.php" class="back-link">← Kembali ke daftar pengguna</a>
    </div>
</div>

</body>
</html>
