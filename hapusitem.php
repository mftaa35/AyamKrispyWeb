<?php
// hapus_item.php

include 'config.php'; // koneksi database

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // amankan input

    // Cek apakah koneksi berhasil
    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Gunakan prepared statement agar lebih aman
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Berhasil dihapus
        header('Location: cart.php');
        exit();
    } else {
        echo "Gagal menghapus item: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID item tidak ditemukan di URL.";
}
?>
