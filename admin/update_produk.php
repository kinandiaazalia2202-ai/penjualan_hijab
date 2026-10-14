<?php
include '../koneksi.php';

// Tangkap semua data yang dikirim dari form edit
$id_produk = $_POST['ProdukID'];
$nama_produk = $_POST['NamaProduk'];
$harga = $_POST['Harga'];
$stok = $_POST['Stok'];

// Query untuk UPDATE (bukan INSERT) berdasarkan ProdukID
$query = mysqli_query($koneksi, "UPDATE t_produk SET NamaProduk = '$nama_produk', Harga = '$harga', Stok = '$stok' WHERE ProdukID = '$id_produk'");

if ($query) {
    header("location: produk.php?pesan=edit_sukses");
} else {
    header("location:produk.php?pesan=gagal");
}
?>