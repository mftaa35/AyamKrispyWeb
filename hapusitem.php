<?php
include 'config.php';
session_start();

// Misalnya user login pakai user_id 1
$users_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 1;

// Pastikan ada ID yang diterima
if (isset($_GET['id'])) {
    // Ambil ID dari parameter URL
    $id = (int)$_GET['id'];
    
    // Hapus item dari keranjang
    // Gunakan prepared statement untuk keamanan
    $delete_query = "DELETE FROM keranjang1 WHERE id = ? AND users_id = ?";
    
    // Siapkan statement
    $stmt = mysqli_prepare($conn, $delete_query);
    
    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, "ii", $id, $users_id);
        
        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil, kembali ke halaman cart
            echo "<script>alert('Item berhasil dihapus dari keranjang!'); window.location.href='cart.php';</script>";
        } else {
            // Jika gagal
            echo "<script>alert('Gagal menghapus item: " . mysqli_error($conn) . "'); window.location.href='cart.php';</script>";
        }
        
        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error dalam persiapan query: " . mysqli_error($conn) . "'); window.location.href='cart.php';</script>";
    }
} else {
    // Jika tidak ada ID, kembali ke halaman cart
    echo "<script>alert('ID item tidak ditemukan!'); window.location.href='cart.php';</script>";
}
?>
