<?php 
session_start();
if ($_SESSION['status_login'] != "login" || $_SESSION['Status'] != 1) {
    header("location:../login.php");
    exit();
}

include 'header.php'; 
?>

<style>
    .welcome-card {
        background-color: #ffffff;
        border-radius: 20px;
        padding: 1.8rem 2rem;
        box-shadow: 0 6px 20px rgba(91, 44, 44, 0.03);
        border: 1px solid rgba(212, 154, 208, 0.25);
    }

    .stat-card {
        border-radius: 20px;
        padding: 1.5rem;
        color: var(--font-brown);
        border: none;
        box-shadow: 0 8px 20px rgba(91, 44, 44, 0.05);
        transition: transform 0.2s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
    }

    .card-1 { background-color: var(--lilac-card-1); }
    .card-2 { background-color: var(--lilac-card-2); }
    .card-3 { background-color: var(--lilac-card-3); }
    .card-4 { background-color: var(--lilac-card-4); }

    .custom-table-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(212, 154, 208, 0.25);
        box-shadow: 0 8px 20px rgba(91, 44, 44, 0.03);
        overflow: hidden;
    }
</style>

<div class="pb-5">
    
    <div class="welcome-card mb-4">
        <h1 class="display-6 fw-bold mb-2">Selamat Datang, Admin!</h1>
        <p class="fs-5 mb-0" style="color: var(--font-brown); opacity: 0.85;">Anda memiliki akses penuh untuk mengelola produk, transaksi, pelanggan, dan laporan penjualan.</p>
    </div>

    <?php 
    $query_stok_habis = mysqli_query($koneksi, "SELECT * FROM t_produk WHERE Stok <= 5 ORDER BY Stok ASC");
    $jumlah_stok_habis = mysqli_num_rows($query_stok_habis);

    if ($jumlah_stok_habis > 0) {
    ?>
        <div class="alert alert-dismissible fade show border mb-4" role="alert" style="background-color: #ffffff; color: var(--font-brown); border-color: rgba(212, 154, 208, 0.4) !important; border-radius: 18px; box-shadow: 0 6px 18px rgba(91, 44, 44, 0.03);">
            <div class="d-flex align-items-center mb-2">
                <h5 class="alert-heading fw-bold mb-0">Peringatan Stok Produk</h5>
            </div>
            <p class="mb-2">Terdapat <strong><?php echo $jumlah_stok_habis; ?> produk</strong> yang stoknya habis atau hampir habis:</p>
            <ul class="mb-3">
                <?php 
                while ($stok_info = mysqli_fetch_array($query_stok_habis)) {
                    $badge_stok = ($stok_info['Stok'] == 0) 
                        ? '<span class="badge" style="background-color: var(--lilac-card-3); color: var(--font-brown);">Habis (0)</span>' 
                        : '<span class="badge" style="background-color: var(--lilac-card-2); color: var(--font-brown);">Sisa ' . $stok_info['Stok'] . '</span>';
                    echo "<li class='mb-1'><strong>" . htmlspecialchars($stok_info['NamaProduk']) . "</strong> — Stok: " . $badge_stok . "</li>";
                }
                ?>
            </ul>
            <hr class="my-2" style="border-color: rgba(212, 154, 208, 0.3);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small style="color: var(--font-brown); opacity: 0.8;">Segera perbarui stok produk agar proses penjualan tidak terganggu.</small>
                <a href="produk.php" class="btn btn-sm fw-bold px-3" style="background-color: var(--lilac-sidebar); color: var(--font-brown); border-radius: 12px;">Kelola Data Produk &raquo;</a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card card-1">
                <div class="fw-bold mb-2 fs-6">Data Produk</div>
                <h2 class="mb-0 fw-bold"><?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM t_produk")); ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card-2">
                <div class="fw-bold mb-2 fs-6">Data Pelanggan</div>
                <h2 class="mb-0 fw-bold"><?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM t_pelanggan")); ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card-3">
                <div class="fw-bold mb-2 fs-6">Data Transaksi</div>
                <h2 class="mb-0 fw-bold"><?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM t_penjualan")); ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card-4">
                <div class="fw-bold mb-2 fs-6">Total Pendapatan</div>
                <?php 
                $query_pendapatan = mysqli_query($koneksi, "SELECT SUM(TotalHarga) AS total FROM t_penjualan");
                $data_pendapatan = mysqli_fetch_assoc($query_pendapatan);
                $total_pendapatan = $data_pendapatan['total'] ? $data_pendapatan['total'] : 0;
                ?>
                <h3 class="mb-0 fw-bold" style="font-size: 1.5rem;">Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>

    <div class="custom-table-card">
        <div class="p-3 bg-white d-flex justify-content-between align-items-center border-bottom" style="border-color: rgba(212, 154, 208, 0.2) !important;">
            <h4 class="fs-5 mb-0 fw-bold">Riwayat Transaksi Terakhir</h4>
            <a href="transaksi.php" class="btn btn-sm px-3 fw-bold" style="background-color: var(--lilac-card-2); color: var(--font-brown); border-radius: 12px;">Lihat Semua Data</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr style="background-color: var(--lilac-sidebar); color: var(--font-brown);">
                        <th class="ps-4">No</th>
                        <th>ID Penjualan</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $query_riwayat = mysqli_query($koneksi, "SELECT t.*, p.NamaPelanggan FROM t_penjualan t JOIN t_pelanggan p ON t.PelangganID = p.PelangganID ORDER BY t.PenjualanID DESC LIMIT 5");
                    
                    if(mysqli_num_rows($query_riwayat) > 0) {
                        while($data = mysqli_fetch_array($query_riwayat)){
                    ?>
                    <tr>
                        <td class="ps-4"><?php echo $no++; ?></td>
                        <td>#<?php echo $data['PenjualanID']; ?></td>
                        <td><?php echo htmlspecialchars($data['NamaPelanggan']); ?></td>
                        <td><?php echo $data['TglPenjualan']; ?></td>
                        <td class="fw-bold">Rp <?php echo number_format($data['TotalHarga'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="5" class="text-center py-4" style="color: var(--font-brown); opacity: 0.6;">Belum ada data transaksi penjualan.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

    </div> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>