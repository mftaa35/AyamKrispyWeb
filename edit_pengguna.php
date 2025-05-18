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

// Proses update jika form disubmit
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];

    $update_query = "UPDATE users SET 
        nama = '$nama', 
        no_telepon = '$no_telepon', 
        alamat = '$alamat', 
        email = '$email'
        WHERE users_id = $id";

    if (mysqli_query($conn, $update_query)) {
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
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f4f4f4;
        }

        .form-container {
            background-color: white;
            padding: 20px 30px;
            border-radius: 8px;
            width: 400px;
            margin: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="form-container">
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
    <a href="admin_pengguna.php" class="back-link">← Kembali ke daftar pengguna</a>
</div>

</body>
</html>
