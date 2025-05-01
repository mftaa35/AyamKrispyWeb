<?php
$servername = "localhost";
$username = "root";
$password = ""; // kalau pakai XAMPP biasanya kosong
$dbname = "webayam"; // ganti dengan nama database kamu

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
