<?php
session_start();
if ($_SESSION['status_login'] != "login") {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';

if (!isset($_GET['id'])) {
    header("location:transaksi.php");
    exit();
}

$penjualan_id = $_GET['id'];

// Ambil data transaksi utama & pelanggan
$query_transaksi = mysqli_query($koneksi, "SELECT t.*, p.NamaPelanggan FROM t_penjualan t JOIN t_pelanggan p ON t.PelangganID = p.PelangganID WHERE t.PenjualanID = '$penjualan_id'");
$d = mysqli_fetch_array($query_transaksi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi #<?php echo $penjualan_id; ?> - Kynzalia Fashion</title>
    <!-- Menggunakan Bootstrap CSS yang sudah ada -->
    <link rel="stylesheet" href="../asset/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .receipt-box {
            max-width: 400px;
            margin: 30px auto;
            background: #fff;
        }
        @media print {
            body {
                background-color: #fff;
            }
            .receipt-box {
                max-width: 100% !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="container">
        <div class="card shadow-sm border receipt-box p-4">
            
            <!-- Header Nota & Alamat Toko -->
            <div class="text-center mb-3">
                <h4 class="fw-bold mb-0">Kin-kin Collection</h4>
                <p class="text-muted small mb-1">Jl. Puri Delta Asri 3 Blok P no 9 - Semarang, Jawa Tengah</p>
                <p class="text-muted small mb-0">Nota Belanja Pelanggan</p>
            </div>

            <hr class="border-secondary">

            <!-- Informasi Transaksi -->
            <div class="small mb-3">
                <div class="d-flex justify-content-between">
                    <span>No. Transaksi:</span>
                    <span class="fw-semibold">#<?php echo $d['PenjualanID']; ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Tanggal:</span>
                    <span class="fw-semibold"><?php echo $d['TglPenjualan']; ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Pelanggan:</span>
                    <span class="fw-semibold"><?php echo $d['NamaPelanggan']; ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Kasir:</span>
                    <span class="fw-semibold"><?php echo $_SESSION['Username']; ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Metode Pembayaran:</span>
                    <span class="fw-semibold"><?php echo isset($d['MetodePembayaran']) ? $d['MetodePembayaran'] : '-'; ?></span>
                </div>
            </div>

            <hr class="border-secondary">

            <!-- Tabel Produk -->
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-2" style="font-size: 13px;">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Jml</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query_detail = mysqli_query($koneksi, "SELECT dt.*, p.NamaProduk, p.Harga FROM t_detail_penjualan dt JOIN t_produk p ON dt.ProdukID = p.ProdukID WHERE dt.PenjualanID = '$penjualan_id'");
                        while ($dt = mysqli_fetch_array($query_detail)) {
                        ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?php echo $dt['NamaProduk']; ?></div>
                                <small class="text-muted">@ Rp <?php echo number_format($dt['Harga'], 0, ',', '.'); ?></small>
                            </td>
                            <td class="text-center"><?php echo $dt['JumlahProduk']; ?></td>
                            <td class="text-end">Rp <?php echo number_format($dt['Subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <hr class="border-secondary">

            <!-- Total Harga, Bayar, dan Kembalian -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold mb-0">TOTAL KESELURUHAN:</h6>
                    <h5 class="fw-bold text-success mb-0">Rp <?php echo number_format($d['TotalHarga'], 0, ',', '.'); ?></h5>
                </div>
                <div class="d-flex justify-content-between align-items-center small text-muted mb-1">
                    <span>Uang Dibayar:</span>
                    <span>Rp <?php echo number_format($d['Bayar'], 0, ',', '.'); ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center small text-muted">
                    <span>Uang Kembalian:</span>
                    <span>Rp <?php echo number_format($d['Kembalian'], 0, ',', '.'); ?></span>
                </div>
            </div>

            <!-- Footer / Ucapan -->
            <div class="text-center small text-muted mb-4">
                <p class="mb-1">Terima Kasih Telah Berbelanja!</p>
                <p class="mb-0">Kynzalia Fashion - Tampil Modis Setiap Hari</p>
            </div>

            <!-- Tombol Aksi (Hilang saat diprint) -->
            <div class="text-center d-grid gap-2 no-print">
                <button onclick="window.print()" class="btn btn-primary btn-sm">Cetak Ulang</button>
                <a href="transaksi.php" class="btn btn-secondary btn-sm">Kembali ke Transaksi</a>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="../asset/js/bootstrap.bundle.min.js"></script>
</body>
</html>