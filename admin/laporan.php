<?php 
session_start();
if ($_SESSION['status_login'] != "login" || $_SESSION['Status'] != 1) {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';

// --- LOGIKA PAGINASI ---
$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$where = "WHERE 1=1";
if (isset($_GET['dari_tgl']) && $_GET['dari_tgl'] != "" && isset($_GET['sampai_tgl']) && $_GET['sampai_tgl'] != "") {
    $dari = $_GET['dari_tgl'];
    $sampai = $_GET['sampai_tgl'];
    $where .= " AND t.TglPenjualan BETWEEN '$dari' AND '$sampai'";
}
if (isset($_GET['nama_pelanggan']) && $_GET['nama_pelanggan'] != "") {
    $nama = mysqli_real_escape_string($koneksi, $_GET['nama_pelanggan']);
    $where .= " AND p.NamaPelanggan LIKE '%$nama%'";
}

// Hitung total data untuk paginasi
$query_count = "SELECT COUNT(*) as total FROM t_penjualan t JOIN t_pelanggan p ON t.PelangganID = p.PelangganID $where";
$data_count = mysqli_query($koneksi, $query_count);
$row_count = mysqli_fetch_assoc($data_count);
$total_data = $row_count['total'];
$total_halaman = ceil($total_data / $batas);

include 'header.php'; 
?>

<div class="pb-5">
    
    <!-- Judul & Tombol Cetak -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold" style="color: var(--font-brown);">Laporan Penjualan</h2>
            <p class="text-muted mb-0">Filter laporan transaksi berdasarkan rentang tanggal dan nama pelanggan.</p>
        </div>
        <div class="no-print">
            <a href="cetak_laporan.php?<?php echo http_build_query(array_merge($_GET, ['halaman' => null])); ?>" target="_blank" class="btn text-white rounded-pill px-4" style="background-color: var(--font-brown);">
                <i class="fas fa-print me-1"></i> Cetak Laporan
            </a>
        </div>
    </div>

    <!-- Form Filter -->
    <div class="card mb-4 no-print">
        <div class="card-body p-3">
            <form method="GET" action="" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold" style="color: var(--font-brown);">Dari Tanggal:</label>
                    <input type="date" name="dari_tgl" class="form-control rounded-pill border-0 bg-light px-3" value="<?php echo isset($_GET['dari_tgl']) ? $_GET['dari_tgl'] : ''; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold" style="color: var(--font-brown);">Sampai Tanggal:</label>
                    <input type="date" name="sampai_tgl" class="form-control rounded-pill border-0 bg-light px-3" value="<?php echo isset($_GET['sampai_tgl']) ? $_GET['sampai_tgl'] : ''; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold" style="color: var(--font-brown);">Cari Nama Pelanggan:</label>
                    <input type="text" name="nama_pelanggan" class="form-control rounded-pill border-0 bg-light px-3" placeholder="Masukkan nama pelanggan..." value="<?php echo isset($_GET['nama_pelanggan']) ? htmlspecialchars($_GET['nama_pelanggan']) : ''; ?>">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 rounded-pill"><i class="fas fa-search"></i></button>
                    <?php if ((isset($_GET['dari_tgl']) && $_GET['dari_tgl'] != '') || (isset($_GET['sampai_tgl']) && $_GET['sampai_tgl'] != '') || (isset($_GET['nama_pelanggan']) && $_GET['nama_pelanggan'] != '')): ?>
                    <a href="laporan.php" class="btn rounded-pill border w-100" style="background-color: transparent; color: var(--font-brown); border-color: #d988c5 !important;" title="Reset Filter">Reset</a>
                    <?php else: ?>
                    <a href="laporan.php" class="btn rounded-pill border w-100 disabled" style="background-color: transparent; color: var(--font-brown); border-color: #d988c5 !important;" title="Reset Filter">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Laporan -->
    <div class="card">
        <div class="card-body p-3">
            
            <div class="text-center mb-4 d-none d-print-block">
                <h3 class="fw-bold mb-1">KYNZALIA FASHION</h3>
                <p class="text-muted mb-1">Laporan Rekapitulasi Penjualan</p>
                <hr>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3" width="5%">No</th>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th class="text-end pe-4">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = $halaman_awal + 1;

                        // Query data dengan limit paginasi
                        $query = "SELECT t.*, p.NamaPelanggan FROM t_penjualan t JOIN t_pelanggan p ON t.PelangganID = p.PelangganID $where ORDER BY t.PenjualanID DESC LIMIT $halaman_awal, $batas";
                        $data = mysqli_query($koneksi, $query);

                        if (mysqli_num_rows($data) > 0) {
                            while ($d = mysqli_fetch_array($data)) {
                        ?>
                        <tr>
                            <td class="ps-3"><?php echo $no++; ?></td>
                            <td class="fw-semibold">#<?php echo $d['PenjualanID']; ?></td>
                            <td><?php echo $d['TglPenjualan']; ?></td>
                            <td class="fw-semibold"><?php echo htmlspecialchars($d['NamaPelanggan']); ?></td>
                            <td class="text-end pe-4 fw-bold" style="color: #d988c5;">Rp <?php echo number_format($d['TotalHarga'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php 
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada data laporan yang sesuai dengan filter pencarian.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold py-3" style="color: var(--font-brown);">TOTAL PENDAPATAN (KESELURUHAN):</td>
                            <td class="text-end pe-4 fw-bold py-3" style="color: #d988c5;">
                                <?php 
                                // Menghitung total keseluruhan dari semua data yang terfilter (bukan cuma yang tampil di halaman ini)
                                $query_total = "SELECT SUM(t.TotalHarga) as total_semua FROM t_penjualan t JOIN t_pelanggan p ON t.PelangganID = p.PelangganID $where";
                                $result_total = mysqli_query($koneksi, $query_total);
                                $row_total = mysqli_fetch_assoc($result_total);
                                $total_keseluruhan = $row_total['total_semua'] ? $row_total['total_semua'] : 0;
                                echo "Rp " . number_format($total_keseluruhan, 0, ',', '.');
                                ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- NAVIGASI TOMBOL PAGINATION -->
            <?php if ($total_halaman > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-4 px-2 no-print">
                <span class="small text-muted">
                    Menampilkan <?php echo $total_data > 0 ? $halaman_awal + 1 : 0; ?> - <?php echo min($halaman_awal + $batas, $total_data); ?> dari total <?php echo $total_data; ?> data
                </span>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?php if($halaman <= 1) echo 'disabled'; ?>">
                            <a class="page-link" style="color: var(--font-brown);" href="?halaman=<?php echo $halaman - 1; ?><?php echo isset($_GET['dari_tgl']) ? '&dari_tgl='.$_GET['dari_tgl'] : ''; ?><?php echo isset($_GET['sampai_tgl']) ? '&sampai_tgl='.$_GET['sampai_tgl'] : ''; ?><?php echo isset($_GET['nama_pelanggan']) ? '&nama_pelanggan='.urlencode($_GET['nama_pelanggan']) : ''; ?>">Previous</a>
                        </li>
                        <?php for($x = 1; $x <= $total_halaman; $x++): ?>
                        <li class="page-item <?php if($halaman == $x) echo 'active'; ?>">
                            <a class="page-link" style="<?php echo ($halaman == $x) ? 'background-color: #f2cbe6 !important; border-color: #f2cbe6 !important; color: var(--font-brown) !important; font-weight: bold;' : 'color: var(--font-brown);'; ?>" href="?halaman=<?php echo $x; ?><?php echo isset($_GET['dari_tgl']) ? '&dari_tgl='.$_GET['dari_tgl'] : ''; ?><?php echo isset($_GET['sampai_tgl']) ? '&sampai_tgl='.$_GET['sampai_tgl'] : ''; ?><?php echo isset($_GET['nama_pelanggan']) ? '&nama_pelanggan='.urlencode($_GET['nama_pelanggan']) : ''; ?>"><?php echo $x; ?></a>
                        </li>
                        <?php endfor; ?>
                        <li class="page-item <?php if($halaman >= $total_halaman) echo 'disabled'; ?>">
                            <a class="page-link" style="color: var(--font-brown);" href="?halaman=<?php echo $halaman + 1; ?><?php echo isset($_GET['dari_tgl']) ? '&dari_tgl='.$_GET['dari_tgl'] : ''; ?><?php echo isset($_GET['sampai_tgl']) ? '&sampai_tgl='.$_GET['sampai_tgl'] : ''; ?><?php echo isset($_GET['nama_pelanggan']) ? '&nama_pelanggan='.urlencode($_GET['nama_pelanggan']) : ''; ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>

        </div>
    </div>

</div>

    </div> <!-- Penutup main-content dari header.php -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>