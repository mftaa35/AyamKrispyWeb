<?php
session_start();
include 'config.php';

// Ambil data dari tabel menu
$sql = "SELECT * FROM menu";
$result = $conn->query($sql);
$id_produk = $_GET['edit'];

// Pastikan produk ada sebelum ditampilkan
$query = mysqli_query($conn, "SELECT * FROM menu WHERE id_produk = '$id_produk'");
if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_assoc($query);
} else {
    die("Produk tidak ditemukan!");
}

// Proses update produk
if(isset($_POST['update_product'])){
    $menu_name = mysqli_real_escape_string($conn, $_POST['menu_name']);
    $menu_price = mysqli_real_escape_string($conn, $_POST['menu_price']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $menu_image = $_FILES['gambar']['name'];
    $gambar_tmp_name = $_FILES['gambar']['tmp_name'];
    $gambar_folder = 'images/' . $menu_image;

    if(empty($menu_name) || empty($menu_price) || empty($deskripsi)){
        $message[] = 'Silakan isi semua kolom!';
    } else {
        if(!empty($menu_image)) {
            // Hapus gambar lama sebelum memperbarui
            $old_image = "images/" . $row['menu_image'];
            if(file_exists($old_image)){
               unlink($old_image);
           }           

            // Perbarui dengan gambar baru
            $update_data = "UPDATE menu SET menu_name='$menu_name', menu_price='$menu_price', deskripsi='$deskripsi',menu_image='$menu_image' WHERE id_produk='$id_produk'";
            $upload = mysqli_query($conn, $update_data);

            if($upload){
                move_uploaded_file($gambar_tmp_name, $gambar_folder);
                header('location:admin_page.php');
            } else {
                $message[] = 'Gagal memperbarui produk!';
            }
        } else {
            // Perbarui tanpa mengubah gambar
            $update_data = "UPDATE menu SET menu_name='$menu_name', menu_price='$menu_price', deskripsi='$deskripsi' WHERE id_produk='$id_produk'";
            $upload = mysqli_query($conn, $update_data);

            if($upload){
                header('location:admin_page.php');
            } else {
                $message[] = 'Gagal memperbarui produk!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Produk</title>
   <link rel="stylesheet" href="css/style.css">
   <style>
      /* Reset dan Base Styles */
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
         font-family: 'Poppins', sans-serif;
      }

      body {
         background-color: #f7f7f7;
         padding: 20px;
      }

      .container {
         max-width: 1000px;
         margin: 0 auto;
         padding: 20px;
      }

      /* Form Styles */
      .admin-product-form-container {
         background-color: #fff;
         padding: 30px;
         border-radius: 10px;
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .centered {
         max-width: 600px;
         margin: 30px auto;
      }

      .title {
         text-align: center;
         margin-bottom: 25px;
         color: #333;
         font-size: 28px;
         border-bottom: 2px solid #4CAF50;
         padding-bottom: 10px;
      }

      .box {
         width: 100%;
         padding: 12px;
         font-size: 16px;
         margin: 8px 0 20px;
         border: 1px solid #ddd;
         border-radius: 5px;
      }

      textarea.box {
         min-height: 120px;
         resize: vertical;
      }

      label {
         display: block;
         margin-bottom: 5px;
         font-weight: bold;
         color: #555;
      }

      /* Button Styles */
      .btn {
         display: inline-block;
         padding: 12px 20px;
         margin-top: 15px;
         font-size: 16px;
         font-weight: bold;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         transition: background-color 0.3s ease;
      }

      input[type="submit"].btn {
         background-color: #4CAF50;
         color: white;
         margin-right: 10px;
      }

      input[type="submit"].btn:hover {
         background-color: #45a049;
      }

      a.btn {
         background-color: #f44336;
         color: white;
         text-decoration: none;
      }

      a.btn:hover {
         background-color: #d32f2f;
      }

      /* Product Image Preview */
      .product-image {
         margin: 20px 0;
         text-align: center;
      }

      .product-image img {
         max-width: 200px;
         max-height: 200px;
         border: 1px solid #ddd;
         border-radius: 5px;
         padding: 5px;
      }

      /* Message Styles */
      .message {
         display: block;
         background-color: #fff3cd;
         color: #856404;
         border: 1px solid #ffeeba;
         padding: 12px;
         margin-bottom: 15px;
         border-radius: 5px;
         text-align: center;
      }

      /* Button Container */
      .button-container {
         display: flex;
         justify-content: space-between;
         margin-top: 20px;
      }
   </style>
</head>
<body>

<?php
if(isset($message)){
    foreach($message as $msg){
        echo '<div class="container"><span class="message">'.$msg.'</span></div>';
    }
}
?>

<div class="container">
   <div class="admin-product-form-container centered">
      <h3 class="title">Update Produk</h3>
      
      <form action="" method="post" enctype="multipart/form-data">
         <label for="menu_name">Nama Produk:</label>
         <input type="text" class="box" id="menu_name" name="menu_name" value="<?= htmlspecialchars($row['menu_name']) ?>" placeholder="Masukkan nama produk">
         
         <label for="menu_price">Harga Produk (Rp):</label>
         <input type="number" min="0" class="box" id_produk="menu_price" name="menu_price" value="<?= htmlspecialchars($row['menu_price']) ?>" placeholder="Masukkan harga produk">
         
         <label for="deskripsi">Deskripsi Produk:</label>
         <textarea class="box" id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi produk"><?= htmlspecialchars($row['deskripsi']) ?></textarea>

         <div class="product-image">
            <label>Gambar Produk Saat Ini:</label>
            <img src="newimages/<?= htmlspecialchars($row['menu_image']) ?>" alt="Gambar Produk">
         </div>

         <label for="gambar">Unggah Gambar Baru:</label>
         <input type="file" class="box" id="gambar" name="gambar" accept="image/png, image/jpeg, image/jpg">

         <div class="button-container">
            <input type="submit" value="Update Produk" name="update_product" class="btn">
            <a href="admin_page.php" class="btn">Kembali</a>
         </div>
      </form>
   </div>
</div>

</body>
</html>