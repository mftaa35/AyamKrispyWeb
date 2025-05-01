<?php
session_start();
include 'config.php';

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Menambahkan Produk Baru
if (isset($_POST['tambah_produk'])) {
    $menu_name = $_POST['menu_name'];
    $menu_price = $_POST['menu_price'];
    $deskripsi = $_POST['deskripsi'];
    $menu_image = '';

    // Menangani upload gambar jika ada
    if (isset($_FILES['menu_image']) && $_FILES['menu_image']['error'] === UPLOAD_ERR_OK) {
        $menu_image = $_FILES['menu_image']['name'];
        $image_tmp = $_FILES['menu_image']['tmp_name'];
        
        // Pastikan direktori upload ada
        $upload_dir = 'images/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Pindahkan file yang diupload
        if (move_uploaded_file($image_tmp, $upload_dir . $menu_image)) {
            // File berhasil diupload
        } else {
            $message[] = 'Gagal mengupload gambar!';
        }
    } else {
        $message[] = 'Upload file gagal atau tidak ada file yang dipilih.';
    }

    if (empty($menu_name) || empty($menu_price) || empty($menu_image)) {
        $message[] = 'Silakan isi semua kolom!';
    } else {
        $menu_name = mysqli_real_escape_string($conn, $menu_name);
        $menu_price = mysqli_real_escape_string($conn, $menu_price);
        $deskripsi = mysqli_real_escape_string($conn, $deskripsi);
        $menu_image = mysqli_real_escape_string($conn, $menu_image);

        $insert = "INSERT INTO menu(menu_name, menu_price, deskripsi, menu_image) 
                VALUES('$menu_name', '$menu_price', '$deskripsi', '$menu_image')";
        $upload = mysqli_query($conn, $insert);

        if ($upload) {
            $message[] = 'Produk baru berhasil ditambahkan!';
        } else {
            $message[] = 'Gagal menambahkan produk: ' . mysqli_error($conn);
        }
    }
}

// Hapus Produk
if (isset($_GET['delete'])) {
    $id_produk = $_GET['delete'];

    // Ambil nama file gambar sebelum menghapus data
    $query = "SELECT menu_image FROM menu WHERE id_produk = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $image_path = 'images/' . $row['menu_image'];
        
        // Hapus file gambar jika ada
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    // Hapus data dari database
    $stmt = $conn->prepare("DELETE FROM menu WHERE id_produk = ?");
    $stmt->bind_param("i", $id_produk);

    if ($stmt->execute()) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $message[] = 'Gagal menghapus produk: ' . $stmt->error;
    }

    $stmt->close();
}

// Edit Produk
if (isset($_POST['edit_produk'])) {
    $id_produk = $_POST['id_produk'];
    $menu_name = mysqli_real_escape_string($conn, $_POST['menu_name']);
    $menu_price = mysqli_real_escape_string($conn, $_POST['menu_price']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Periksa apakah ada upload gambar baru
    $update_image = "";
    if (isset($_FILES['menu_image']) && $_FILES['menu_image']['error'] === UPLOAD_ERR_OK) {
        // Dapatkan gambar lama untuk dihapus
        $get_old_image = mysqli_query($conn, "SELECT menu_image FROM menu WHERE id_produk = $id_produk");
        if ($old_image = mysqli_fetch_assoc($get_old_image)) {
            $old_path = 'images/' . $old_image['menu_image'];
            if (file_exists($old_path)) {
                unlink($old_path);
            }
        }
        
        // Upload gambar baru
        $new_image = $_FILES['menu_image']['name'];
        $image_tmp = $_FILES['menu_image']['tmp_name'];
        
        $upload_dir = 'images/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        if (move_uploaded_file($image_tmp, $upload_dir . $new_image)) {
            $new_image = mysqli_real_escape_string($conn, $new_image);
            $update_image = ", menu_image = '$new_image'";
        } else {
            $message[] = 'Gagal mengupload gambar baru!';
        }
    }
    
    // Update data produk
    $update_query = "UPDATE menu SET menu_name = '$menu_name', menu_price = '$menu_price', 
                     deskripsi = '$deskripsi' $update_image 
                     WHERE id_produk = $id_produk";
    
    if (mysqli_query($conn, $update_query)) {
        $message[] = 'Produk berhasil diperbarui!';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $message[] = 'Gagal memperbarui produk: ' . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Yasaka Fried Chicken</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #2c9c4c;
            --primary-dark: #23763a;
            --secondary: #f8c40c;
            --dark: #333333;
            --light: #f9f9f9;
            --danger: #dc3545;
            --success: #28a745;
            --border: #e0e0e0;
            --shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }

        .dashboard-title {
            color: var(--primary);
            font-size: 28px;
            font-weight: 600;
        }

        .admin-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #bd2130;
        }

        .btn-warning {
            background-color: var(--secondary);
            color: var(--dark);
        }

        .btn-warning:hover {
            background-color: #e0b000;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: 500;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: var(--danger);
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: var(--success);
            border: 1px solid #c3e6cb;
        }

        .dashboard-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-header {
            padding: 20px;
            background-color: var(--primary);
            color: white;
            font-size: 18px;
            font-weight: 500;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid var(--border);
        }

        th {
            background-color: #f4f4f4;
            font-weight: 600;
            color: var(--dark);
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .actions-cell {
            display: flex;
            gap: 10px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .price-cell {
            font-weight: 500;
            color: var(--primary);
        }

        .empty-state {
            text-align: center;
            padding: 40px 0;
            color: #6c757d;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            padding: 20px 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            width: 90%;
            max-width: 600px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .modal-title {
            color: var(--primary);
            font-size: 22px;
            font-weight: 600;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: var(--dark);
        }

        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .admin-actions {
                width: 100%;
            }

            .btn {
                padding: 8px 16px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <h1 class="dashboard-title"><i class="fas fa-drumstick-bite"></i> Yasaka Fried Chicken Admin</h1>
            <div class="admin-actions">
                <a href="admindashboard.php" class="btn btn-warning"><i class="fas fa-home"></i> Halaman Utama</a>
                <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                if (strpos($msg, 'berhasil') !== false) {
                    echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' . $msg . '</div>';
                } else {
                    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> ' . $msg . '</div>';
                }
            }
        }
        ?>

        <div class="dashboard-content">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-plus-circle"></i> Tambah Produk Baru
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="menu_image" class="form-label">Gambar Produk</label>
                            <input type="file" id="menu_image" name="menu_image" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="menu_name" class="form-label">Nama Produk</label>
                            <input type="text" id="menu_name" name="menu_name" placeholder="Masukkan nama produk" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="menu_price" class="form-label">Harga Produk (Rp)</label>
                            <input type="number" id="menu_price" name="menu_price" placeholder="Masukkan harga produk" class="form-control" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                            <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi produk" class="form-control"></textarea>
                        </div>
                        <button type="submit" name="tambah_produk" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i> Daftar Menu
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Gambar</th>
                                    <th width="20%">Nama Menu</th>
                                    <th width="15%">Harga</th>
                                    <th width="30%">Deskripsi</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $select = mysqli_query($conn, "SELECT * FROM menu");
                                if ($select->num_rows > 0) {
                                    while ($row = mysqli_fetch_assoc($select)) {
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <img src="images/<?php echo $row['menu_image']; ?>" alt="Gambar Produk" style="width: 80px; height: auto; border-radius: 8px;">
                                    </td>
                                    <td><?= htmlspecialchars($row['menu_name']) ?></td>
                                    <td class="price-cell">Rp<?= number_format($row['menu_price'], 0, ',', '.') ?></td>
                                    <td><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></td>
                                    <td class="actions-cell">
                                        <button class="btn btn-sm btn-info edit-btn" 
                                                onclick="openEditModal(<?= $row['id_produk'] ?>, 
                                                '<?= addslashes($row['menu_name']) ?>', 
                                                <?= $row['menu_price'] ?>, 
                                                '<?= addslashes($row['deskripsi']) ?>', 
                                                '<?= $row['menu_image'] ?>')">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="<?= $_SERVER['PHP_SELF'] ?>?delete=<?= $row['id_produk'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <i class="fas fa-info-circle"></i> Belum ada produk yang ditambahkan
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <p>&copy; <?= date('Y') ?> Yasaka Fried Chicken. Semua Hak Dilindungi.</p>
        </footer>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><i class="fas fa-edit"></i> Edit Produk</h2>
                <span class="close">&times;</span>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" id="edit_id_produk" name="id_produk">
                
                <div class="form-group">
                    <label for="edit_menu_image" class="form-label">Gambar Produk</label>
                    <div id="current_image_container" style="margin-bottom: 10px;">
                        <img id="current_image" src="" alt="Gambar Saat Ini" style="width: 100px; border-radius: 5px;">
                        <p style="font-size: 12px; color: #666;">Gambar saat ini</p>
                    </div>
                    <input type="file" id="edit_menu_image" name="menu_image" class="form-control">
                    <small style="color: #666;">Biarkan kosong jika tidak ingin mengubah gambar</small>
                </div>

                <div class="form-group">
                    <label for="edit_menu_name" class="form-label">Nama Produk</label>
                    <input type="text" id="edit_menu_name" name="menu_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="edit_menu_price" class="form-label">Harga Produk (Rp)</label>
                    <input type="number" id="edit_menu_price" name="menu_price" class="form-control" min="0" required>
                </div>

                <div class="form-group">
                    <label for="edit_deskripsi" class="form-label">Deskripsi Produk</label>
                    <textarea id="edit_deskripsi" name="deskripsi" class="form-control"></textarea>
                </div>

                <button type="submit" name="edit_produk" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("editModal");
        
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        
        // Function to open edit modal and fill with product data
        function openEditModal(id, name, price, desc, img) {
            document.getElementById("edit_id_produk").value = id;
            document.getElementById("edit_menu_name").value = name;
            document.getElementById("edit_menu_price").value = price;
            document.getElementById("edit_deskripsi").value = desc;
            document.getElementById("current_image").src = "images/" + img;
            
            modal.style.display = "block";
        }
    </script>
</body>
</html>