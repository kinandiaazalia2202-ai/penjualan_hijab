<?php 
include 'header.php';
?>

<div class="pb-5">
    
    <!-- Judul & Tombol Tambah -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold" style="color: var(--font-brown);">Data Produk</h2>
            <p class="text-muted mb-0">Kelola katalog produk Kin-kin Collection di sini.</p>
        </div>
        
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] == 'tambah_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Data produk baru berhasil <b>ditambahkan</b>!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['pesan'] == 'edit_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Data produk berhasil <b>diperbarui</b>!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['pesan'] == 'hapus_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert">
                <i class="bi bi-trash-fill me-2"></i> Data produk berhasil <b>dihapus</b>!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['pesan'] == 'gagal'): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <b>Gagal!</b> Terjadi kesalahan pada database.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <div class="card mb-4">
        <div class="card-body p-3">
            <form method="GET" action="" class="row g-2">
                <div class="col-md-<?php echo (isset($_GET['cari']) && $_GET['cari'] != '') ? '8' : '10'; ?>">
                    <input type="text" name="cari" class="form-control rounded-pill border-0 bg-light px-3" placeholder="Cari nama produk..." value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
                </div>
                <?php if (isset($_GET['cari']) && $_GET['cari'] != ''): ?>
                <div class="col-md-2 d-grid">
                    <a href="produk.php" class="btn btn-secondary rounded-pill">Reset</a>
                </div>
                <?php endif; ?>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-dark">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Produk -->
    <div class="card">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3" width="5%">No</th>
                            <th>ID Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (isset($_GET['cari']) && $_GET['cari'] != '') {
                            $pencarian = mysqli_real_escape_string($koneksi, $_GET['cari']);
                            $data = mysqli_query($koneksi, "SELECT * FROM t_produk WHERE NamaProduk LIKE '%$pencarian%' ORDER BY ProdukID DESC");
                        } else {
                            $data = mysqli_query($koneksi, "SELECT * FROM t_produk ORDER BY ProdukID DESC");
                        }

                        if (mysqli_num_rows($data) > 0) {
                            while ($d = mysqli_fetch_array($data)) {
                        ?>
                        <tr>
                            <td class="ps-3"><?php echo $no++; ?></td>
                            <td><code><?php echo htmlspecialchars($d['ProdukID']); ?></code></td>
                            <td class="fw-semibold"><?php echo htmlspecialchars($d['NamaProduk']); ?></td>
                            <td class="fw-bold" style="color: var(--font-brown);">Rp <?php echo number_format($d['Harga'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="fw-bold" style="color: #d988c5;"><?php echo $d['Stok']; ?> Pcs</span>
                            </td>
                            
                        </tr>
                        <?php 
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data produk.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

    </div> <!-- Penutup main-content -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>