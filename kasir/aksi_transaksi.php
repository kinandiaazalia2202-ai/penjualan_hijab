<?php
session_start();
if ($_SESSION['status_login'] != "login") {
    header("location:../login.php");
    exit();
}

include '../koneksi.php';

if (isset($_POST['simpan_transaksi'])) {
   $TglPenjualan = date('Y-m-d H:i:s');
    $PelangganID = $_POST['PelangganID'];
    $MetodePembayaran = $_POST['MetodePembayaran'];
    $TotalHarga = $_POST['TotalHarga'];
    $Bayar = $_POST['Bayar'];
    $Kembalian = $_POST['Kembalian'];

    // Ambil UserID dari sesi login yang sedang aktif
    $UserID = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';

    // Simpan ke tabel penjualan (UserID ditambahkan ke query)
    $query_penjualan = "INSERT INTO t_penjualan (TglPenjualan, TotalHarga, PelangganID, UserID, MetodePembayaran, Bayar, Kembalian) 
                        VALUES ('$TglPenjualan', '$TotalHarga', '$PelangganID', '$UserID', '$MetodePembayaran', '$Bayar', '$Kembalian')";
    
    if (mysqli_query($koneksi, $query_penjualan)) {
        $PenjualanID = mysqli_insert_id($koneksi);

        $ProdukID = $_POST['ProdukID'];
        $JumlahProduk = $_POST['JumlahProduk'];

        for ($i = 0; $i < count($ProdukID); $i++) {
            $id_produk = $ProdukID[$i];
            $qty = $JumlahProduk[$i];

            // Ambil harga produk
            $q_produk = mysqli_query($koneksi, "SELECT Harga, Stok FROM t_produk WHERE ProdukID = '$id_produk'");
            $d_produk = mysqli_fetch_array($q_produk);
            $harga = $d_produk['Harga'];
            $stok_sekarang = $d_produk['Stok'];
            
            $subtotal = $harga * $qty;

            // Simpan detail penjualan
            $query_detail = "INSERT INTO t_detail_penjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                           VALUES ('$PenjualanID', '$id_produk', '$qty', '$subtotal')";
            mysqli_query($koneksi, $query_detail);

            // Kurangi stok produk
            $stok_baru = $stok_sekarang - $qty;
            mysqli_query($koneksi, "UPDATE t_produk SET Stok = '$stok_baru' WHERE ProdukID = '$id_produk'");
        }

        // Redirect ke transaksi.php dengan membawa pesan berhasil agar alert hijau muncul
        header("location:transaksi.php?pesan=berhasil");
        exit();
    } else {
        // Redirect ke transaksi.php dengan membawa pesan gagal jika error
        header("location:transaksi.php?pesan=gagal");
        exit();
    }
}
?>