<?php 
session_start();
if ($_SESSION['status_login'] != "login") {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';

// --- SETUP PAGINATION ---
$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// Memanggil file header (sidebar & navigasi terpadu)
include 'header.php';
?>

        <!-- Notifikasi -->
        <?php if (isset($_GET['pesan'])): ?>
            <?php if ($_GET['pesan'] == 'berhasil'): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <b>Berhasil!</b> Transaksi baru berhasil disimpan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['pesan'] == 'gagal'): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <b>Gagal!</b> Terjadi kesalahan saat memproses transaksi.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Judul & Tombol Tambah -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold" style="color: var(--font-brown);">Data Transaksi Penjualan</h2>
                <p class="text-muted mb-0">Kelola dan catat transaksi penjualan harian Kin-kin Collection.</p>
            </div>
            <a href="tambah_transaksi.php" class="btn text-white rounded-pill px-4 shadow-sm" style="background-color: #d988c5;">+ Tambah Transaksi</a>
        </div>

        <!-- Form Pencarian Transaksi -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form method="GET" action="" class="row g-2">
                    <div class="col-md-<?php echo (isset($_GET['cari']) && $_GET['cari'] != '') ? '8' : '10'; ?>">
                        <input type="text" name="cari" class="form-control rounded-pill border-0 bg-light px-3" placeholder="Cari berdasarkan nama pelanggan..." value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
                    </div>
                    <?php if (isset($_GET['cari']) && $_GET['cari'] != ''): ?>
                    <div class="col-md-2 d-grid">
                        <a href="transaksi.php" class="btn btn-secondary rounded-pill">Reset</a>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-dark rounded-pill">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data Transaksi -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3" width="5%">No</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Metode Pembayaran</th>
                                <th>Total Harga</th>
                                <th class="text-center" width="18%">OPSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // --- QUERY TOTAL DATA & LIMIT HALAMAN ---
                            $sql_total = "SELECT COUNT(*) as total FROM t_penjualan t JOIN t_pelanggan p ON t.PelangganID = p.PelangganID";
                            if (isset($_GET['cari']) && $_GET['cari'] != '') {
                                $pencarian = mysqli_real_escape_string($koneksi, $_GET['cari']);
                                $sql_total .= " WHERE p.NamaPelanggan LIKE '%$pencarian%'";
                            }
                            $query_total = mysqli_query($koneksi, $sql_total);
                            $row_total = mysqli_fetch_assoc($query_total);
                            $total_data = $row_total['total'];
                            $total_halaman = ceil($total_data / $batas);

                            // Query utama dengan LIMIT dan OFFSET
                            $sql = "SELECT t.*, p.NamaPelanggan FROM t_penjualan t JOIN t_pelanggan p ON t.PelangganID = p.PelangganID";
                            if (isset($_GET['cari']) && $_GET['cari'] != '') {
                                $pencarian = mysqli_real_escape_string($koneksi, $_GET['cari']);
                                $sql .= " WHERE p.NamaPelanggan LIKE '%$pencarian%'";
                            }
                            $sql .= " ORDER BY t.PenjualanID DESC LIMIT $halaman_awal, $batas";

                            $data = mysqli_query($koneksi, $sql);
                            $no = $halaman_awal + 1;

                            if (mysqli_num_rows($data) > 0) {
                                while ($d = mysqli_fetch_array($data)) {
                                    $penjualan_id = $d['PenjualanID'];
                            ?>
                            <tr>
                                <td class="ps-3"><?php echo $no++; ?></td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($d['NamaPelanggan']); ?></td>
                                <td class="text-nowrap"><?php echo $d['TglPenjualan']; ?></td>
                                <td>
                                    <span class="badge fw-bold px-2 py-1" style="background-color: #f2cbe6; color: var(--font-brown);"><?php echo $d['MetodePembayaran']; ?></span>
                                </td>
                                <td class="fw-bold" style="color: #d988c5;">Rp <?php echo number_format($d['TotalHarga'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm text-white" style="background-color: #e59ed1;" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo $penjualan_id; ?>" title="Detail">
                                        <i class="bi bi-eye-fill me-1"></i> DETAIL
                                    </button>
                                    <a href="edit_transaksi.php?id=<?php echo $penjualan_id; ?>" class="btn btn-sm rounded-circle me-1" style="background-color: #e59ed1; color: var(--font-brown);" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                            ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Data transaksi tidak ditemukan.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Navigasi Paginasi -->
                <?php if ($total_halaman > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-4 px-2">
                    <span class="small text-muted">
                        Menampilkan <?php echo $halaman_awal + 1; ?> - <?php echo min($halaman_awal + $batas, $total_data); ?> dari total <?php echo $total_data; ?> data
                    </span>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item <?php if($halaman <= 1) echo 'disabled'; ?>">
                                <a class="page-link" style="color: var(--font-brown);" href="?halaman=<?php echo $halaman - 1; ?><?php echo isset($_GET['cari']) ? '&cari='.htmlspecialchars($_GET['cari']) : ''; ?>">Previous</a>
                            </li>
                            <?php for($x = 1; $x <= $total_halaman; $x++): ?>
                            <li class="page-item <?php if($halaman == $x) echo 'active'; ?>">
                                <a class="page-link" style="<?php echo ($halaman == $x) ? 'background-color: #f2cbe6 !important; border-color: #f2cbe6 !important; color: var(--font-brown) !important; font-weight: bold;' : 'color: var(--font-brown);'; ?>" href="?halaman=<?php echo $x; ?><?php echo isset($_GET['cari']) ? '&cari='.htmlspecialchars($_GET['cari']) : ''; ?>"><?php echo $x; ?></a>
                            </li>
                            <?php endfor; ?>
                            <li class="page-item <?php if($halaman >= $total_halaman) echo 'disabled'; ?>">
                                <a class="page-link" style="color: var(--font-brown);" href="?halaman=<?php echo $halaman + 1; ?><?php echo isset($_GET['cari']) ? '&cari='.htmlspecialchars($_GET['cari']) : ''; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>

            </div>
        </div>

    </div><!-- Tutup .main-content dari header.php -->

    <!-- Bagian Modal Detail -->
    <?php 
    $data_modal = mysqli_query($koneksi, "SELECT t.*, p.NamaPelanggan FROM t_penjualan t JOIN t_pelanggan p ON t.PelangganID = p.PelangganID ORDER BY t.PenjualanID DESC");
    while ($d = mysqli_fetch_array($data_modal)) {
        $penjualan_id = $d['PenjualanID'];
    ?>
    <div class="modal fade" id="modalDetail<?php echo $penjualan_id; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $penjualan_id; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background-color: #d988c5;">
                    <h5 class="modal-title" id="modalLabel<?php echo $penjualan_id; ?>">Detail Transaksi #<?php echo $penjualan_id; ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start p-4">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <p class="mb-1 text-muted small">Tanggal Penjualan:</p>
                            <h6 class="fw-bold"><?php echo $d['TglPenjualan']; ?></h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1 text-muted small">Pelanggan:</p>
                            <h6 class="fw-bold"><?php echo htmlspecialchars($d['NamaPelanggan']); ?></h6>
                        </div>
                        <div class="col-sm-4 mt-2">
                            <p class="mb-1 text-muted small">Metode Pembayaran:</p>
                            <h6 class="fw-bold"><span class="badge px-2 py-1" style="background-color: #f2cbe6; color: var(--font-brown);"><?php echo $d['MetodePembayaran']; ?></span></h6>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-2" style="color: var(--font-brown);">Daftar Produk Dibeli:</h6>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3">No</th>
                                    <th>Jenis Produk</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query_detail = mysqli_query($koneksi, "SELECT dt.*, p.NamaProduk, p.Harga FROM t_detail_penjualan dt JOIN t_produk p ON dt.ProdukID = p.ProdukID WHERE dt.PenjualanID = '$penjualan_id'");
                                $no_detail = 1;
                                while ($dt = mysqli_fetch_array($query_detail)) {
                                ?>
                                <tr>
                                    <td class="ps-3"><?php echo $no_detail++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($dt['NamaProduk']); ?></td>
                                    <td>Rp <?php echo number_format($dt['Harga'], 0, ',', '.'); ?></td>
                                    <td><span class="badge" style="background-color: #f2cbe6; color: var(--font-brown);"><?php echo $dt['JumlahProduk']; ?> Pcs</span></td>
                                    <td class="fw-semibold" style="color: #d988c5;">Rp <?php echo number_format($dt['Subtotal'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3 p-3 rounded bg-light">
                        <h5 class="fw-bold mb-2" style="color: #d988c5;">Total Keseluruhan: Rp <?php echo number_format($d['TotalHarga'], 0, ',', '.'); ?></h5>
                        <h6 class="mb-1 text-muted small">Uang Dibayar: Rp <?php echo number_format($d['Bayar'], 0, ',', '.'); ?></h6>
                        <h6 class="mb-0 text-muted small">Uang Kembalian: Rp <?php echo number_format($d['Kembalian'], 0, ',', '.'); ?></h6>
                    </div>
                </div>
                <div class="modal-footer bg-light justify-content-between">
                    <a href="cetak_nota.php?id=<?php echo $penjualan_id; ?>" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">Cetak Nota</a>
                    <button type="button" class="btn btn-sm text-white rounded-pill px-4" style="background-color: var(--font-brown);" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- Bootstrap JS Bundle (Sudah termasuk Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>