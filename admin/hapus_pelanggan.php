<?php
include '../koneksi.php';

$id = $_GET['id_pelanggan'];

$query = mysqli_query($koneksi, "DELETE FROM t_pelanggan WHERE PelangganID='$id'");

if ($query) {
    header("location:pelanggan.php?pesan=hapus_sukses");
} else {
    header("location:pelanggan.php?pesan=gagal");
}
?>