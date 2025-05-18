<?php
include 'config.php';

// Ambil data berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM users WHERE users_id = $id";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "Data tidak ditemukan.";
        exit;
    }

    $user = mysqli_fetch_assoc($result);
} else {
    echo "ID tidak ditemukan di URL.";
    exit;
}

// Proses update
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];

    $update_query = "UPDATE users SET 
        nama = ?, 
        no_telepon = ?, 
        alamat = ?, 
        email = ? 
        WHERE users_id = ?";

    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $no_telepon, $alamat, $email, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data berhasil diubah'); window.location='admin_pengguna.php';</script>";
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna</title>
    <!-- Font Awesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            font-family: 'Poppins', sans-serif;
            background-color: #ecf0f1;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #4CAF50;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-sizing: border-box;
            color: white;
        }

        .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .menu ul {
            list-style: none;
            padding: 0;
        }

        .menu ul li {
            margin: 15px 0;
        }

        .menu ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 8px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .menu ul li a i {
            margin-right: 10px;
        }

        .menu ul li a:hover {
            background-color: rgba(47, 92, 47, 0.8);
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
            text-align: left;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            transition: 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #4CAF50;
            background-color: #fff;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="profile">
        <h2>Administrator</h2>
        <p>Admin</p>
    </div>
    <div class="menu">
        <ul>
            <li><a href="admindashboard.php"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="admin_page.php"><i class="fas fa-utensils"></i> Menu</a></li>
            <li><a href="admin_pesanan.php"><i class="fas fa-shopping-cart"></i> Pesanan</a></li>
            <li><a href="admin_pengguna.php"><i class="fas fa-users"></i> Pengguna</a></li>
            <li><a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="card">
        <h2>Edit Data Pengguna</h2>
        <form method="POST">
            <label for="nama">Nama:</label>
            <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>

            <label for="no_telepon">No Telepon:</label>
            <input type="text" name="no_telepon" id="no_telepon" value="<?php echo htmlspecialchars($user['no_telepon']); ?>" required>

            <label for="alamat">Alamat:</label>
            <input type="text" name="alamat" id="alamat" value="<?php echo htmlspecialchars($user['alamat']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <input type="submit" name="update" value="Simpan Perubahan">
        </form>
        <a href="admin_pengguna.php" class="back-link">← Kembali ke Daftar Pengguna</a>
    </div>
</div>

</body>
</html>
