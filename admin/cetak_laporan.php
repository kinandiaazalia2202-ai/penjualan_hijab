<?php 
session_start();
if ($_SESSION['status_login'] != "login" || $_SESSION['Status'] != 1) {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Penjualan - Kynzalia Fashion</title>
    <link rel="stylesheet" href="../asset/css/bootstrap.min.css">
    <style>
        body { background-color: white; color: black; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body onload="window.print()">

    <div class="container mt-4">
        
        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1">KYNZALIA FASHION</h3>
            <p class="text-muted mb-1">Laporan Rekapitulasi Transaksi Penjualan</p>
            
            <?php if (!empty($_GET['dari_tgl']) && !empty($_GET['sampai_tgl'])): ?>
                <p class="small text-muted mb-0">Periode: <?php echo date('d-m-Y', strtotime($_GET['dari_tgl'])); ?> s/d <?php echo date('d-m-Y', strtotime($_GET['sampai_tgl'])); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($_GET['nama_pelanggan'])): ?>
                <p class="small text-muted mb-0">Pencarian Pelanggan: "<?php echo htmlspecialchars($_GET['nama_pelanggan']); ?>"</p>
            <?php endif; ?>

            <hr class="mt-3">
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-secondary text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th>ID Penjualan</th>
                        <th>Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th class="text-end pe-3">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $where = "WHERE 1=1";

                    if (!empty($_GET['dari_tgl']) && !empty($_GET['sampai_tgl'])) {
                        $where .= " AND t.TglPenjualan BETWEEN '{$_GET['dari_tgl']}' AND '{$_GET['sampai_tgl']}'";
                    }

                    if (!empty($_GET['nama_pelanggan'])) {
                        $nama = mysqli_real_escape_string($koneksi, $_GET['nama_pelanggan']);
                        $where .= " AND p.NamaPelanggan LIKE '%$nama%'";
                    }

                    $query = "SELECT t.*, p.NamaPelanggan FROM t_penjualan t JOIN t_pelanggan p ON t.PelangganID = p.PelangganID $where ORDER BY t.PenjualanID DESC";
                    $data = mysqli_query($koneksi, $query);

                    $total_keseluruhan = 0;

                    if (mysqli_num_rows($data) > 0) {
                        while ($d = mysqli_fetch_array($data)) {
                            $total_keseluruhan += $d['TotalHarga'];
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="fw-semibold">#<?php echo $d['PenjualanID']; ?></td>
                        <td><?php echo $d['TglPenjualan']; ?></td>
                        <td><?php echo $d['NamaPelanggan']; ?></td>
                        <td class="text-end pe-3 fw-semibold">Rp <?php echo number_format($d['TotalHarga'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">Tidak ada data laporan yang sesuai.</td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold py-2">TOTAL PENDAPATAN:</td>
                        <td class="text-end pe-3 fw-bold text-success py-2">Rp <?php echo number_format($total_keseluruhan, 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row mt-5 no-print">
            <div class="col-md-8"></div>
            <div class="col-md-4 text-center">
                <p class="mb-5">Kendal, <?php echo date('d-m-Y'); ?><br>Administrator,</p>
                <br><br>
                <p class="fw-bold mb-0"><b><?php echo $_SESSION['Username']; ?></b></p>
            </div>
        </div>

        <div class="text-center mt-4 mb-5 no-print">
            <button onclick="window.close()" class="btn btn-secondary btn-sm">Tutup Halaman</button>
        </div>

    </div>

</body>
</html>