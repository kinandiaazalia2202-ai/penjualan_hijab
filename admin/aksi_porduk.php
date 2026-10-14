<?php
include '../koneksi.php';

$nama_produk = $_POST['NamaProduk'];
$harga = $_POST['Harga'];
$stok = $_POST['Stok'];

$query = mysqli_query($koneksi, "INSERT INTO t_produk (NamaProduk, Harga, Stok) VALUES ('$nama_produk', '$harga', '$stok')");

if ($query) {
    header("location: produk.php?pesan=tambah_sukses");
} else {
    header("location:produk.php?pesan=gagal");
}
?>