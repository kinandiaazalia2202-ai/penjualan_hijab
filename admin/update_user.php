<?php 
session_start();
if ($_SESSION['status_login'] != "login" || $_SESSION['Status'] != 1) {
    header("location:../login.php");
    exit();
}

include '../koneksi.php';

if (isset($_POST['update'])) {
    $id            = mysqli_real_escape_string($koneksi, $_POST['UserID']);
    $username      = mysqli_real_escape_string($koneksi, $_POST['Username']);
    $status        = mysqli_real_escape_string($koneksi, $_POST['Status']);
    $password_baru = $_POST['PasswordBaru'];

    // Jika kolom Password Baru diisi, di-hash dengan MD5. Jika kosong, pertahankan password lama.
    if (!empty($password_baru)) {
        $password_fix = md5($password_baru);
        $query = mysqli_query($koneksi, "UPDATE t_user SET Username = '$username', Password = '$password_fix', Status = '$status' WHERE UserID = '$id'");
    } else {
        $query = mysqli_query($koneksi, "UPDATE t_user SET Username = '$username', Status = '$status' WHERE UserID = '$id'");
    }

    if ($query) {
        header("location: user.php?pesan=edit_sukses");
    } else {
        header("location: user.php?pesan=gagal");
    }
} else {
    header("location: user.php");
}
?>