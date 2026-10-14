<?php
session_start();
if ($_SESSION['status_login'] != "login" || $_SESSION['Status'] != 1) {
    header("location:../login.php");
    exit();
}

include '../koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST['Username']);
$password = md5($_POST['Password']);
$status   = mysqli_real_escape_string($koneksi, $_POST['Status']);

$query = "INSERT INTO t_user (Username, Password, Status) VALUES ('$username', '$password', '$status')";

if (mysqli_query($koneksi, $query)) {
    header("location:user.php?pesan=tambah_sukses");
} else {
    header("location:user.php?pesan=gagal");
}
