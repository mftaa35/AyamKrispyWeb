<?php
session_start();
include 'config.php';

// Ambil bulan dan tahun dari parameter URL dan pastikan itu integer
$bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : date('m');
$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');

// Debugging: Pastikan bulan dan tahun yang dikirim benar
// echo "Bulan yang dipilih: " . $bulan . " - Tahun yang dipilih: " . $tahun;

// Query laporan transaksi berdasarkan bulan dan tahun
$query_transaksi = "SELECT * FROM orders2 WHERE MONTH(tanggal_pemesanan) = ? AND YEAR(tanggal_pemesanan) = ?";
$stmt_transaksi = mysqli_prepare($conn, $query_transaksi);
mysqli_stmt_bind_param($stmt_transaksi, "ii", $bulan, $tahun);
mysqli_stmt_execute($stmt_transaksi);
$result_transaksi = mysqli_stmt_get_result($stmt_transaksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 20px;
    padding: 20px;
    background: white;
    color: black;
    text-align: center;
}

h2 {
    font-size: 22px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    overflow: hidden;
}

table, th, td {
    border: 1px solid black;
    padding: 12px;
    text-align: left;
}

th {
    background: linear-gradient(135deg, #28a745, #218838);
    color: white;
    font-size: 16px;
}

td {
    font-size: 14px;
}

tr:nth-child(even) {
    background: #f2f2f2;
}

.button-container {
    margin-top: 30px;
}

.btn {
    padding: 12px 20px;
    font-size: 16px;
    background: linear-gradient(135deg, #218838, #218838);
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    transition: 0.3s;
    font-weight: bold;
    margin: 5px;
}

.btn:hover {
    opacity: 0.8;
}

.btn-back {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

@media print {
    .button-container {
        display: none; /* Sembunyikan tombol saat mencetak */
    }

    body {
        font-size: 14px;
        color: black;
        background: white;
    }

    table {
        border: 1px solid black;
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 8px;
        border: 1px solid black;
        text-align: left;
    }

    th {
        background-color: #ccc;
        color: black;
    }
}
    </style>
</head>
<body>

<h2>Laporan Transaksi - Bulan <?php echo $bulan; ?> Tahun <?php echo $tahun; ?></h2>

<table>
    <tr>
            <th>ID</th>
            <th>Nama Depan</th>
            <th>Alamat</th>
            <th>Kota</th>
            <th>Telepon</th>
            <th>Pesanan</th>
            <th>Subtotal</th>
            <th>Total</th>
            <th>Tanggal Pemesanan</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result_transaksi)): ?>
        <tr>
        <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['nama_depan']); ?></td>
                <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                <td><?php echo htmlspecialchars($row['kota']); ?></td>
                <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
                <td><?php echo htmlspecialchars($row['pesanan']); ?></td>
                <td>Rp <?php echo number_format($row['subtotal'], 2, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['total'], 2, ',', '.'); ?></td>
                <td><?php echo $row['tanggal_pemesanan']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<div class="button-container">
    <button class="btn" onclick="window.print()">🖨️ Print Laporan</button>
    <button class="btn btn-back" onclick="window.history.back()">🔙 Kembali</button>
</div>

</body>
</html>
