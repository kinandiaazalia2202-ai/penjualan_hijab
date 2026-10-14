<?php
include '../koneksi.php';

$id = $_GET['id'];

// 1. Ambil data detail penjualan terlebih dahulu untuk mengetahui ProdukID dan Jumlah Produk yang dibeli
$detail_query = mysqli_query($koneksi, "SELECT * FROM t_detail_penjualan WHERE PenjualanID='$id'");
while ($row = mysqli_fetch_array($detail_query)) {
    $produk_id = $row['ProdukID'];
    $jumlah_beli = $row['JumlahProduk']; // Sesuaikan nama kolom jumlah jika di database kamu berbeda (misal: Jumlah atau Qty)

    // Kembalikan/tambahkan stok produk ke tabel t_produk
    mysqli_query($koneksi, "UPDATE t_produk SET Stok = Stok + $jumlah_beli WHERE ProdukID = '$produk_id'");
}

// Hapus data detail penjualannya dulu agar tidak error foreign key
mysqli_query($koneksi, "DELETE FROM t_detail_penjualan WHERE PenjualanID='$id'");

// Setelah itu hapus data utama penjualannya
$query = mysqli_query($koneksi, "DELETE FROM t_penjualan WHERE PenjualanID='$id'");

if ($query) {
    header("location:transaksi.php?pesan=hapus_sukses");
} else {
    header("location:transaksi.php?pesan=gagal");
}
?>