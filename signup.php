<?php
include 'config.php';
session_start();

$nama       = $_POST['nama'];
$no_telepon = $_POST['no_telepon'];
$alamat     = $_POST['alamat'];
$email      = $_POST['email'];
$password   = password_hash($_POST['password'], PASSWORD_DEFAULT); // Amankan password

$sql = "INSERT INTO users (nama, no_telepon, alamat, email, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nama, $no_telepon, $alamat, $email, $password);

if ($stmt->execute()) {
    // Dapatkan ID pengguna yang baru dibuat
    $id_pengguna = $conn->insert_id;
    
    // Buat sesi dengan nama variabel yang sama dengan login.php
    $_SESSION['pengguna_id'] = $id_pengguna;
    $_SESSION['pengguna_nama'] = $nama;
    $_SESSION['pengguna_email'] = $email;
    
    echo "<script>alert('Pendaftaran berhasil!'); window.location.href='login.html';</script>";
    exit();
} else {
    echo "Gagal daftar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>