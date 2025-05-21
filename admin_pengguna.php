<?php
include 'config.php';

// Proses hapus pengguna jika ada parameter hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Query untuk menghapus data pengguna
    $delete_query = "DELETE FROM users WHERE users_id = '$id'";
    
    if (mysqli_query($conn, $delete_query)) {
        // Redirect ke halaman yang sama setelah penghapusan berhasil
        echo "<script>alert('Data pengguna berhasil dihapus!');</script>";
        echo "<script>window.location.href='admin_pengguna.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($conn) . "');</script>";
    }
}

// Ambil data pengguna
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengguna</title>
    <!-- Font Awesome buat icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
        width: 250px;
        height: 100vh;
        background-color:#4CAF50;
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

        .profile-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 10px;
        }

        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
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
            background-color:rgb(47, 92, 47);
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
            background-color: #ecf0f1;
            min-height: 100vh;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card h3 {
            margin-bottom: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color:rgb(46, 125, 62);
            color: white;
        }

        .btn-action {
            margin-right: 5px;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-warning {
    background-color: #f39c12;
    color: white;
    padding: 3px 8px;         /* lebih kecil dari sebelumnya */
    font-size: 12px;          /* font lebih kecil */
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
}

.btn-danger {
    background-color: #e74c3c;
    color: white;
    padding: 3px 8px;
    font-size: 12px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
}
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="profile">
      <!-- <img src="Profile-PNG-Photo.png" alt="Profile"> -->
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

<!-- Main Content -->
<div class="main-content">
    <div class="card">
        <h3>Daftar Pengguna</h3>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
                            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="edit_pengguna.php?id=<?php echo $row['users_id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i> Ubah
                                    </a>
                                    <a href="admin_pengguna.php?hapus=<?php echo $row['users_id']; ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <br>
                    <a href="tambah_pengguna.php" class="btn-warning btn-action" style="padding: 8px 15px; display: inline-block;">
                        <i class="fas fa-plus-circle"></i> Tambah Pengguna
                    </a>
            </div>
        <?php else: ?>
            <div class="alert">
                Belum ada data pelanggan. Silakan tambahkan data baru.
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
