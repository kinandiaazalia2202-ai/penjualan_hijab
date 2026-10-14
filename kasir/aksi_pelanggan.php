<?php
session_start();
if ($_SESSION['status_login'] != "login") {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['NamaPelanggan']);
    $alamat   = mysqli_real_escape_string($koneksi, $_POST['Alamat']);
    $telepon  = mysqli_real_escape_string($koneksi, $_POST['NoTelp']);

    $query = mysqli_query($koneksi, "INSERT INTO t_pelanggan (NamaPelanggan, Alamat, NoTelp) VALUES ('$nama', '$alamat', '$telepon')");
    
    if ($query) {
        header("location:pelanggan.php?pesan=berhasil");
        exit();
    } else {
        header("location:pelanggan.php?pesan=gagal");
        exit();
    }
} else {
    header("location:tambah_pelanggan.php");
    exit();
}
?>