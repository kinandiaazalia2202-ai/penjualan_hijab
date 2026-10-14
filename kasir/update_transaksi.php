<?php
session_start();
if ($_SESSION['status_login'] != "login") {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';

if (isset($_POST['edit_transaksi'])) {
    if (!isset($_POST['form_token']) || $_POST['form_token'] != $_SESSION['current_token']) {
        header("location:transaksi.php?pesan=invalid_token");
        exit();
    }
    unset($_SESSION['current_token']);

    $PenjualanID      = mysqli_real_escape_string($koneksi, $_POST['PenjualanID']);
    $TglPenjualan     = mysqli_real_escape_string($koneksi, $_POST['TglPenjualan']);
    $PelangganID      = mysqli_real_escape_string($koneksi, $_POST['PelangganID']);
    $MetodePembayaran = mysqli_real_escape_string($koneksi, $_POST['MetodePembayaran']);
    $TotalHarga       = mysqli_real_escape_string($koneksi, $_POST['TotalHarga']);
    $Bayar            = mysqli_real_escape_string($koneksi, $_POST['Bayar']);
    $Kembalian        = mysqli_real_escape_string($koneksi, $_POST['Kembalian']);

    $arrProdukID      = $_POST['ProdukID'];
    $arrJumlahProduk  = $_POST['JumlahProduk'];

    mysqli_begin_transaction($koneksi);

    try {
        // 1. Kembalikan stok produk lama
        $old_detail = mysqli_query($koneksi, "SELECT * FROM t_detail_penjualan WHERE PenjualanID = '$PenjualanID'");
        while ($old = mysqli_fetch_array($old_detail)) {
            mysqli_query($koneksi, "UPDATE t_produk SET Stok = Stok + {$old['JumlahProduk']} WHERE ProdukID = '{$old['ProdukID']}'");
        }

        // 2. Hapus detail lama
        mysqli_query($koneksi, "DELETE FROM t_detail_penjualan WHERE PenjualanID = '$PenjualanID'");

        // 3. Update data utama penjualan
        $update = "UPDATE t_penjualan SET TglPenjualan='$TglPenjualan', PelangganID='$PelangganID', TotalHarga='$TotalHarga', MetodePembayaran='$MetodePembayaran', Bayar='$Bayar', Kembalian='$Kembalian' WHERE PenjualanID='$PenjualanID'";
        if (!mysqli_query($koneksi, $update)) throw new Exception("Gagal update data utama.");

        // 4. Masukkan detail baru & kurangi stok baru
        for ($i = 0; $i < count($arrProdukID); $i++) {
            $prod_id = mysqli_real_escape_string($koneksi, $arrProdukID[$i]);
            $qty     = mysqli_real_escape_string($koneksi, $arrJumlahProduk[$i]);

            if (!empty($prod_id) && $qty > 0) {
                $q_prod = mysqli_query($koneksi, "SELECT Harga FROM t_produk WHERE ProdukID = '$prod_id'");
                $d_prod = mysqli_fetch_array($q_prod);
                if (!$d_prod) throw new Exception("Produk tidak ditemukan.");

                $subtotal = $d_prod['Harga'] * $qty;

                mysqli_query($koneksi, "INSERT INTO t_detail_penjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) VALUES ('$PenjualanID', '$prod_id', '$qty', '$subtotal')");
                mysqli_query($koneksi, "UPDATE t_produk SET Stok = Stok - $qty WHERE ProdukID = '$prod_id'");
            }
        }

        mysqli_commit($koneksi);
        header("location:transaksi.php?pesan=edit_berhasil");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        echo "<script>alert('Gagal: " . $e->getMessage() . "'); window.location='transaksi.php';</script>";
        exit();
    }
} else {
    header("location:transaksi.php");
    exit();
}
?>