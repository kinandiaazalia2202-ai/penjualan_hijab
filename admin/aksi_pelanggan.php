<?php
include '../koneksi.php';

$nama_pelanggan = $_POST['NamaPelanggan'];
$alamat = $_POST['Alamat'];
$no_telepon = $_POST['NoTelp'];

$panjang_telp = strlen($no_telepon);

if ($panjang_telp < 10 || $panjang_telp > 13) {
    header("location:tambah_pelanggan.php?pesan=telp_tidak_valid");
    exit();
}

$query = mysqli_query($koneksi, "INSERT INTO t_pelanggan (NamaPelanggan, Alamat, NoTelp) VALUES ('$nama_pelanggan', '$alamat', '$no_telepon')");

if ($query) {
    header("location:pelanggan.php?pesan=tambah_sukses");
} else {
    header("location:pelanggan.php?pesan=gagal");
}
?>