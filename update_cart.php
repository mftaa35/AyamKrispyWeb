<?php
include 'config.php'; // Pastikan ini mengarah ke file koneksi database Anda
session_start();

// Ambil user_id dari sesi (sesuaikan dengan cara Anda mengelola login user)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Ganti dengan cara Anda mendapatkan user_id

// Pastikan request datang dari action yang valid dan memiliki ID item keranjang
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $cart_item_id = $_GET['id']; // Ini adalah ID dari tabel 'keranjang'

    // Cari item keranjang di database berdasarkan ID dan user_id
    $check_query = "SELECT * FROM keranjang WHERE id = '$cart_item_id' AND user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $item = mysqli_fetch_assoc($check_result);
        $current_quantity = $item['quantity'];

        $new_quantity = $current_quantity; // Inisialisasi kuantitas baru

        // Lakukan aksi berdasarkan parameter 'action'
        if ($action == 'increase') {
            $new_quantity = $current_quantity + 1;
        } elseif ($action == 'decrease') {
            // Pastikan kuantitas tidak kurang dari 1
            if ($current_quantity > 1) {
                $new_quantity = $current_quantity - 1;
            } else {
                // Jika kuantitas sudah 1 dan user mencoba mengurangi,
                // Anda bisa memilih untuk tidak melakukan apa-apa atau menghapus item
                // Dalam contoh ini, kita tidak melakukan apa-apa jika sudah 1
                $new_quantity = 1; // Tetapkan ke 1
            }
        }

        // Update kuantitas di database
        $update_query = "UPDATE keranjang SET quantity = '$new_quantity' WHERE id = '$cart_item_id' AND user_id = '$user_id'";

        if (mysqli_query($conn, $update_query)) {
            // Redirect kembali ke halaman keranjang belanja setelah update
            header('Location: cart.php');
            exit();
        } else {
            // Tangani error jika query gagal
            echo "Gagal update kuantitas: " . mysqli_error($conn);
            // Anda bisa menambahkan redirect ke halaman error atau menampilkan pesan
        }

    } else {
        // Item tidak ditemukan di keranjang user ini
        // Mungkin karena ID item keranjang salah atau user tidak berhak mengubah
        // Redirect ke halaman keranjang atau halaman lain
        header('Location: cart.php'); // Atau halaman error
        exit();
    }

} else {
    // Jika parameter 'action' atau 'id' tidak ada
    // Redirect ke halaman keranjang atau halaman lain
    header('Location: cart.php');
    exit();
}
?>