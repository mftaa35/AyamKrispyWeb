<?php
include 'config.php'; // Pastikan koneksi sesuai

// Handle filter
$filter_query = "SELECT * FROM orders2 WHERE 1";
if (isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
    $bulan = $_GET['bulan'];
    $tahun = $_GET['tahun'];
    $filter_query .= " AND MONTH(created_at) = '$bulan' AND YEAR(created_at) = '$tahun'";
    $period = "Bulan " . date('F', mktime(0, 0, 0, $bulan, 10)) . " Tahun $tahun";
} elseif (isset($_GET['from_date']) && isset($_GET['to_date']) && $_GET['from_date'] != '' && $_GET['to_date'] != '') {
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];
    $filter_query .= " AND DATE(created_at) BETWEEN '$from' AND '$to'";
    $period = "Periode $from s/d $to";
} else {
    $period = "Semua Periode";
}

$data = mysqli_query($conn, $filter_query);

// Hitung total pendapatan
$total_pendapatan = 0;
$temp_data = [];
while ($row = mysqli_fetch_assoc($data)) {
    $total_pendapatan += $row['total'];
    $temp_data[] = $row;
}
// Reset pointer
mysqli_data_seek($data, 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Transaksi</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f7f9fb;
    }
  
    .sidebar {
      width: 245px;
      background-color: #4CAF50;
      position: fixed;
      top: 0; left: 0; bottom: 0;
      color: #fff;
      padding: 20px;
      transition: all 0.3s ease;
      z-index: 100;
    }
  
    .sidebar .profile {
      text-align: center;
      margin-bottom: 30px;
    }
  
    .sidebar .profile img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      border: 3px solid #fff;
    }
  
    .sidebar .profile h2 {
      margin: 10px 0 5px;
      font-size: 20px;
    }
  
    .sidebar .profile p {
      font-size: 14px;
      color: #dfe6e9;
    }
  
    .sidebar .menu ul {
      list-style: none;
      padding-left: 0;
    }
  
    .sidebar .menu li {
      margin: 15px 0;
    }
  
    .sidebar .menu a {
      color: #fff;
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 10px;
      border-radius: 8px;
      transition: background 0.3s;
    }
  
    .sidebar .menu a:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .sidebar .menu a.active {
      background: rgba(255, 255, 255, 0.2);
      font-weight: bold;
    }
  
    .sidebar .menu a i {
      margin-right: 10px;
    }
  
    .main-content {
      margin-left: 245px;
      padding: 20px;
      transition: all 0.3s ease;
    }

    .main-content h2 {
      color: #2c3e50;
      margin-bottom: 20px;
      text-align: center;
      font-size: 24px;
    }
  
    .topbar {
      background: #ffffff;
      padding: 30px 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 15px;
    }

    .filter-container {
      background: #ffffff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      margin-bottom: 20px;
    }

    .filter-row {
      display: flex;
      justify-content: center;
      margin-bottom: 15px;
      flex-wrap: wrap;
    }

    .filter-row label {
      margin: 0 10px;
      font-weight: 500;
      color: #555;
    }

    .filter-title {
      text-align: center;
      margin-bottom: 15px;
      color: #4CAF50;
      font-size: 18px;
    }

    select, input[type="date"], input[type="submit"] {
      padding: 10px;
      margin: 5px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    input[type="submit"], .btn {
      background-color: #4CAF50;
      color: white;
      cursor: pointer;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      font-weight: 500;
      transition: background 0.3s;
    }

    input[type="submit"]:hover, .btn:hover {
      background-color: #45a049;
    }

    .btn-print {
      background-color: #2196F3;
      display: inline-flex;
      align-items: center;
      margin-top: 20px;
    }

    .btn-print i {
      margin-right: 5px;
    }

    .btn-print:hover {
      background-color: #0b7dda;
    }

    .summary-box {
      background: #ffffff;
      padding: 15px 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .summary-item {
      text-align: center;
      flex: 1;
    }

    .summary-item h3 {
      font-size: 16px;
      color: #7f8c8d;
      margin-bottom: 5px;
    }

    .summary-item p {
      font-size: 22px;
      font-weight: bold;
      color: #2c3e50;
    }

    .data-container {
      background: #ffffff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 12px;
      text-align: left;
    }

    th {
      background-color: #4CAF50;
      color: white;
      font-weight: 500;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .actions-container {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    @media print {
      /* Sembunyikan elemen yang tidak diperlukan saat mencetak */
      .sidebar, .filter-container, .actions-container, input[type="submit"] {
        display: none;
      }

      /* Atur ulang margin dan padding untuk mengoptimalkan ruang A4 */
      @page {
        size: A4 portrait;
        margin: 1cm;
      }

      html, body {
        width: 210mm;
        height: 297mm;
        margin: 0;
        padding: 0;
        background-color: white;
        font-size: 12px;
      }

      .main-content {
        margin-left: 0;
        padding: 5mm;
        width: 100%;
      }

      /* Optimalkan ukuran font untuk muat dalam kertas A4 */
      h2 {
        font-size: 16px;
        margin-bottom: 8px;
      }

      .summary-box {
        margin-bottom: 8px;
      }

      .summary-item h3 {
        font-size: 12px;
      }

      .summary-item p {
        font-size: 14px;
      }

      /* Atur tabel agar muat dalam A4 */
      .data-container {
        padding: 0;
        margin: 0;
        box-shadow: none;
      }
      
      table {
        border: 1px solid black;
        font-size: 10px;
        width: 100%;
        table-layout: fixed;
        page-break-inside: auto;
      }
      
      tr {
        page-break-inside: avoid;
        page-break-after: auto;
      }

      th, td {
        padding: 4px;
        overflow-wrap: break-word;
        word-wrap: break-word;
      }
      
      th {
        background-color: #cccccc !important;
        color: black;
        font-size: 10px;
      }

      /* Atur lebar kolom */
      table th:nth-child(1), table td:nth-child(1) { width: 5%; }  /* No */
      table th:nth-child(2), table td:nth-child(2) { width: 12%; } /* Nama */
      table th:nth-child(3), table td:nth-child(3) { width: 15%; } /* Alamat */
      table th:nth-child(4), table td:nth-child(4) { width: 10%; } /* No Telp */
      table th:nth-child(5), table td:nth-child(5) { width: 18%; } /* Pesanan */
      table th:nth-child(6), table td:nth-child(6) { width: 8%;  } /* Subtotal */
      table th:nth-child(7), table td:nth-child(7) { width: 8%;  } /* Ongkir */
      table th:nth-child(8), table td:nth-child(8) { width: 8%;  } /* Total */
      table th:nth-child(9), table td:nth-child(9) { width: 8%;  } /* Tanggal */
      table th:nth-child(10), table td:nth-child(10) { width: 8%; } /* Pembayaran */
      
      /* Tambahkan header dan footer cetak */
      .print-header {
        display: block;
        text-align: center;
        margin-bottom: 10px;
      }
      
      .print-footer {
        display: block;
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
        font-size: 10px;
        padding: 5px 0;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="profile">
      <h2>Administrator</h2>
      <p>Admin</p>
    </div>
    <div class="menu">
      <ul>
        <li><a href="admindashboard.php"><i class="fas fa-home"></i><span>Beranda</span></a></li>
        <li><a href="admin_page.php"><i class="fas fa-utensils"></i><span>Menu</span></a></li>
        <li><a href="admin_pesanan.php"><i class="fas fa-shopping-cart"></i><span>Pesanan</span></a></li>
        <li><a href="admin_pengguna.php"><i class="fas fa-users"></i><span>Pelanggan</span></a></li>
        <li><a href="laporan.php" class="active"><i class="fas fa-file-alt"></i><span>Laporan</span></a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
      </ul>
    </div>
  </div>

  <div class="main-content">
    <div class="print-header" style="display: none;">
      <h1>LAPORAN TRANSAKSI</h1>
      <p>Yasaka Fried Chicken - Kemuning"</p>
      <p><?php echo $period; ?></p>
      <hr style="border: 1px solid #000; margin: 10px 0;">
    </div>
    
    <h2>Laporan Transaksi - <?php echo $period; ?></h2>

    <div class="filter-container">
      <h3 class="filter-title">Filter Laporan</h3>
      
      <!-- Filter Bulan/Tahun -->
      <form method="get">
        <div class="filter-row">
          <label>Bulan:</label>
          <select name="bulan">
            <option value="">--Pilih--</option>
            <?php 
            $bulan_names = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            for ($i = 1; $i <= 12; $i++) { 
                $selected = (isset($_GET['bulan']) && $_GET['bulan'] == $i) ? 'selected' : '';
                echo "<option value='$i' $selected>" . $bulan_names[$i] . "</option>";
            } ?>
          </select>
          <label>Tahun:</label>
          <select name="tahun">
            <option value="">--Pilih--</option>
            <?php for ($y = 2023; $y <= date('Y'); $y++) { 
                $selected = (isset($_GET['tahun']) && $_GET['tahun'] == $y) ? 'selected' : '';
                echo "<option value='$y' $selected>$y</option>";
            } ?>
          </select>
          <input type="submit" value="Filter Bulan/Tahun">
        </div>
      </form>

      <!-- Filter Tanggal -->
      <form method="get">
        <div class="filter-row">
          <label>Dari Tanggal:</label>
          <input type="date" name="from_date" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>">
          <label>Sampai Tanggal:</label>
          <input type="date" name="to_date" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>">
          <input type="submit" value="Filter Tanggal">
        </div>
      </form>
    </div>

    <div class="summary-box">
      <div class="summary-item">
        <h3>Total Transaksi</h3>
        <p><?php echo count($temp_data); ?></p>
      </div>
      <div class="summary-item">
        <h3>Total Pendapatan</h3>
        <p>Rp<?php echo number_format($total_pendapatan, 0, ',', '.'); ?></p>
      </div>
    </div>

    <div class="data-container">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No Telp</th>
            <th>Pesanan</th>
            <th>Subtotal</th>
            <th>Ongkir</th>
            <th>Total</th>
            <th>Tanggal</th>
            <th>Pembayaran</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          if (mysqli_num_rows($data) > 0) {
            while ($row = mysqli_fetch_assoc($data)) {
              echo "<tr>";
              echo "<td>" . $no++ . "</td>";
              echo "<td>" . $row['nama_depan'] . " " . $row['nama_belakang'] . "</td>";
              echo "<td>" . $row['alamat'] . ", " . $row['petunjuk_arah'] . ", " . $row['kota'] . ", " . $row['kode_pos'] . "</td>";
              echo "<td>" . $row['no_telepon'] . "</td>";
              echo "<td>";
              $items = json_decode($row['pesanan'], true);
              foreach ($items as $item) {
                  echo "- " . $item['menu_name'] . " (Rp " . number_format($item['menu_price'], 0, ',', '.') . ") x " . $item['quantity'] . "<br>";
              }
              echo "</td>";
              echo "<td>Rp" . number_format($row['subtotal'], 0, ',', '.') . "</td>";
              echo "<td>Rp" . number_format($row['ongkir'], 0, ',', '.') . "</td>";
              echo "<td>Rp" . number_format($row['total'], 0, ',', '.') . "</td>";
              echo "<td>" . date('d-m-Y H:i', strtotime($row['created_at'])) . "</td>";
              echo "<td>" . $row['metode_pembayaran'] . "</td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='10' style='text-align:center;'>Tidak ada data yang ditemukan</td></tr>";
          }
          ?>
        </tbody>
      </table>

      <div class="actions-container">
        <?php if (isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != ''): ?>
          <a href="javascript:void(0)" onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Cetak Laporan
          </a>
        <?php elseif (isset($_GET['from_date']) && isset($_GET['to_date']) && $_GET['from_date'] != '' && $_GET['to_date'] != ''): ?>
          <a href="javascript:void(0)" onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Cetak Laporan
          </a>
        <?php else: ?>
          <a href="javascript:void(0)" onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Cetak Laporan
          </a>
        <?php endif; ?>
      </div>
    </div>
    
    <div class="print-footer" style="display: none;">
      <p>Dicetak pada: <?php echo date('d-m-Y H:i:s'); ?></p>
      <p>Halaman 1</p>
    </div>
  </div>

  <script>
    // Script untuk print styling
    window.onbeforeprint = function() {
      document.querySelector('.print-header').style.display = 'block';
      document.querySelector('.print-footer').style.display = 'block';
    };
    
    window.onafterprint = function() {
      document.querySelector('.print-header').style.display = 'none';
      document.querySelector('.print-footer').style.display = 'none';
    };
    
    // Script untuk highlight menu aktif
    document.addEventListener('DOMContentLoaded', function() {
      // Dapatkan path halaman saat ini
      var path = window.location.pathname;
      var page = path.split("/").pop();
      
      // Cari link yang menuju ke halaman yang aktif
      var menuLinks = document.querySelectorAll('.sidebar .menu a');
      menuLinks.forEach(function(link) {
        if (link.getAttribute('href') === page) {
          link.classList.add('active');
        }
      });
    });
  </script>

</body>
</html>