<?php 
session_start();
include 'koneksi.php';

$username = $_POST['Username'];
$password = md5($_POST['Password']);

$query = mysqli_query($koneksi, "SELECT * FROM t_user WHERE Username='$username' AND Password='$password'");
$cek   = mysqli_num_rows($query);

if ($cek > 0) {
    $data = mysqli_fetch_assoc($query);

    $_SESSION['UserID']       = $data['UserID'];
    $_SESSION['Username']     = $data['Username'];
    $_SESSION['Status']       = $data['Status'];
    $_SESSION['status_login'] = "login";

    if ($data['Status'] == 1) {
        header("location:admin/dashboard_admin.php");
    } else if ($data['Status'] == 2) {
        header("location:kasir/transaksi.php");
    } else {
        echo "<script>alert('Status user tidak valid!'); window.location.href='login.php';</script>";
    }

} else {
    echo "<script>alert('Username atau Password salah!'); window.location.href='login.php';</script>";
}
?>