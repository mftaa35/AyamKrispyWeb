<?php
// hapus_item.php

include 'config.php'; // pastikan koneksi database tersedia

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // pastikan id berbentuk angka untuk keamanan

    // Query untuk menghapus item dari tabel keranjang
    $query = "DELETE FROM keranjang1 WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        // Berhasil dihapus, kembali ke cart.php
        header('Location: cart.php');
        exit();
    } else {
        echo "Gagal menghapus item: " . mysqli_error($conn);
    }
} else {
    echo "ID item tidak ditemukan.";
}
?>
