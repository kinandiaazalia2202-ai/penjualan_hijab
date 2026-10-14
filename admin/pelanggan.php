<?php 
session_start();
if ($_SESSION['status_login'] != "login" || $_SESSION['Status'] != 1) {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';

$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$query_count = "SELECT * FROM t_pelanggan";
if (isset($_GET['cari']) && $_GET['cari'] != '') {
    $pencarian = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $query_count .= " WHERE NamaPelanggan LIKE '%$pencarian%'";
}
$data_count = mysqli_query($koneksi, $query_count);
$total_data = mysqli_num_rows($data_count);
$total_halaman = ceil($total_data / $batas);

include 'header.php';
?>

<div class="pb-5">
    
    <!-- Judul & Tombol Tambah -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold" style="color: var(--font-brown);">Data Pelanggan</h2>
            <p class="text-muted mb-0">Kelola daftar pelanggan Kin-kin Collection di sini.</p>
        </div>
        <a href="tambah_pelanggan.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pelanggan Baru
        </a>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] == 'tambah_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Data pelanggan baru berhasil <b>ditambahkan</b>!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['pesan'] == 'edit_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Data pelanggan berhasil <b>diperbarui</b>!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['pesan'] == 'hapus_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert">
                <i class="bi bi-trash-fill me-2"></i> Data pelanggan berhasil <b>dihapus</b>!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['pesan'] == 'gagal' || $_GET['pesan'] == 'telp_tidak_valid'): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <b>Gagal!</b> Terjadi kesalahan atau nomor telepon tidak valid (harus 10-13 digit).
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <div class="card mb-4">
        <div class="card-body p-3">
            <form method="GET" action="" class="row g-2">
                <div class="col-md-<?php echo (isset($_GET['cari']) && $_GET['cari'] != '') ? '8' : '10'; ?>">
                    <input type="text" name="cari" class="form-control rounded-pill border-0 bg-light px-3" placeholder="Cari nama pelanggan..." value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
                </div>
                <?php if (isset($_GET['cari']) && $_GET['cari'] != ''): ?>
                <div class="col-md-2 d-grid">
                    <a href="pelanggan.php" class="btn btn-secondary rounded-pill">Reset</a>
                </div>
                <?php endif; ?>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-dark">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Pelanggan -->
    <div class="card">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3" width="5%">No</th>
                            <th>ID Pelanggan</th>
                            <th>Nama Pelanggan</th>
                            <th>Alamat</th>
                            <th>Nomor Telepon</th>
                            <th class="text-center" width="15%">OPSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT * FROM t_pelanggan";
                        if (isset($_GET['cari']) && $_GET['cari'] != '') {
                            $pencarian = mysqli_real_escape_string($koneksi, $_GET['cari']);
                            $sql .= " WHERE NamaPelanggan LIKE '%$pencarian%'";
                        }
                        $sql .= " ORDER BY PelangganID DESC LIMIT $halaman_awal, $batas";

                        $data = mysqli_query($koneksi, $sql);
                        $no = $halaman_awal + 1;
                        if (mysqli_num_rows($data) > 0) {
                            while ($d = mysqli_fetch_array($data)) {
                        ?>
                        <tr>
                            <td class="ps-3"><?php echo $no++; ?></td>
                            <td><code><?php echo $d['PelangganID']; ?></code></td>
                            <td class="fw-semibold"><?php echo htmlspecialchars($d['NamaPelanggan']); ?></td>
                            <td><?php echo htmlspecialchars($d['Alamat']); ?></td>
                            <td><?php echo htmlspecialchars($d['NoTelp']); ?></td>
                            <td class="text-center">
                                <a href="edit_pelanggan.php?id_pelanggan=<?php echo $d['PelangganID']; ?>" class="btn btn-sm rounded-circle me-1" style="background-color: #e59ed1; color: var(--font-brown);" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="hapus_pelanggan.php?id_pelanggan=<?php echo $d['PelangganID']; ?>" class="btn btn-sm rounded-circle" style="background-color: #f2cbe6; color: var(--font-brown);" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data pelanggan.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_halaman > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-4 px-2">
                <span class="small text-muted">
                    Menampilkan <?php echo $halaman_awal + 1; ?> - <?php echo min($halaman_awal + $batas, $total_data); ?> dari total <?php echo $total_data; ?> data
                </span>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?php if($halaman <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?halaman=<?php echo $halaman - 1; ?><?php echo isset($_GET['cari']) ? '&cari='.htmlspecialchars($_GET['cari']) : ''; ?>">Previous</a>
                        </li>
                        <?php for($x = 1; $x <= $total_halaman; $x++): ?>
                        <li class="page-item <?php if($halaman == $x) echo 'active'; ?>">
                            <a class="page-link" href="?halaman=<?php echo $x; ?><?php echo isset($_GET['cari']) ? '&cari='.htmlspecialchars($_GET['cari']) : ''; ?>"><?php echo $x; ?></a>
                        </li>
                        <?php endfor; ?>
                        <li class="page-item <?php if($halaman >= $total_halaman) echo 'disabled'; ?>">
                            <a class="page-link" href="?halaman=<?php echo $halaman + 1; ?><?php echo isset($_GET['cari']) ? '&cari='.htmlspecialchars($_GET['cari']) : ''; ?>">Next</a>
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