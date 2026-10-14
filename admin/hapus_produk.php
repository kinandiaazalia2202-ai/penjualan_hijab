<?php
    include '../koneksi.php';

    mysqli_query($koneksi, "DELETE FROM t_produk WHERE ProdukID='$_GET[id_produk]'");

   header("location: produk.php?pesan=hapus_sukses");
?>