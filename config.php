<?php
$servername = "mysql.railway.internal";
$username = "root";
$password = "wBnJxdmcyPKXCunHMemfcAkpjwgcnSET"; // kalau pakai XAMPP biasanya kosong
$dbname = "railway"; // ganti dengan nama database kamu

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
