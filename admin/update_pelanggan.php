<?php
include '../koneksi.php';

$id = $_POST['PelangganID'];
$nama_pelanggan = $_POST['NamaPelanggan'];
$alamat = $_POST['Alamat'];
$no_telepon = $_POST['NoTelp'];

$panjang_telp = strlen($no_telepon);

if ($panjang_telp < 10 || $panjang_telp > 13) {
    header("location:edit_pelanggan.php?id_pelanggan=$id&pesan=telp_tidak_valid");
    exit();
}

$query = mysqli_query($koneksi, "UPDATE t_pelanggan SET NamaPelanggan='$nama_pelanggan', Alamat='$alamat', NoTelp='$no_telepon' WHERE PelangganID='$id'");

if ($query) {
    header("location:pelanggan.php?pesan=edit_sukses");
} else {
    header("location:pelanggan.php?pesan=gagal");
}
?>