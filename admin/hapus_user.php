<?php 
session_start();
if ($_SESSION['status_login'] != "login" || $_SESSION['Status'] != 1) {
    header("location:../login.php");
    exit();
}

include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    $cek = mysqli_query($koneksi, "SELECT * FROM t_user WHERE UserID = '$id'");
    $d = mysqli_fetch_array($cek);

    if ($d && $d['Username'] == $_SESSION['Username']) {
        header("location: user.php?pesan=hapus_sendiri");
        exit();
    }

    $query = mysqli_query($koneksi, "DELETE FROM t_user WHERE UserID = '$id'");

    if ($query) {
        header("location: user.php?pesan=hapus_sukses");
    } else {
        header("location: user.php?pesan=gagal");
    }
} else {
    header("location: user.php");
}
?>