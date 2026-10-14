<?php
session_start();
if ($_SESSION['status_login'] != "login") {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';

// Kueri untuk mengecek stok barang yang habis (Stok <= 0)
$cek_stok_habis = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_produk WHERE Stok <= 0");
$data_stok = mysqli_fetch_assoc($cek_stok_habis);
$jumlah_stok_habis = $data_stok['total'];

include 'header.php'; 
?>

<style>
    /* Wrapper Utama Dashboard dengan Background Putih Keunguan Muda ala Admin */
    .dashboard-wrapper {
        background-color: #f8f6fc; 
        width: 100%;
        min-height: 100vh;
        padding: 30px 20px 60px 20px;
        box-sizing: border-box;
    }

    /* Banner Selamat Datang ala Butik Hijab */
    .welcome-banner {
        background: linear-gradient(135deg, #ffffff 0%, #fdf9fc 100%);
        border: 2px solid #f2cbe6;
        border-radius: 30px;
        padding: 35px 40px;
        margin-bottom: 30px;
        box-shadow: 0 10px 35px rgba(229, 158, 209, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .welcome-text h1 {
        font-family: 'Georgia', 'Times New Roman', serif;
        font-style: italic;
        font-weight: bold;
        color: #5a2d4b;
        font-size: 26px;
        margin-bottom: 8px;
    }

    .welcome-text p {
        color: #8c5a7d;
        font-size: 14px;
        margin: 0;
    }

    /* Kartu Menu ala Rak / Display Butik */
    .hijab-rack-card {
        background: white;
        border-radius: 25px;
        border: 2px solid #f2cbe6;
        padding: 25px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .hijab-rack-card:hover {
        transform: translateY(-5px);
        border-color: #e59ed1;
        box-shadow: 0 12px 30px rgba(229, 158, 209, 0.2);
    }

    /* Kotak Ikon Bootstrap */
    .rack-visual {
        background: #f8f6fc;
        border-radius: 20px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 40px;
        color: #5a2d4b;
        border: 1px dashed #e59ed1;
        transition: 0.3s ease;
        position: relative;
    }

    .hijab-rack-card:hover .rack-visual {
        background-color: #fde8f5;
        color: #432137;
    }

    /* Badge Peringatan Stok di atas Ikon */
    .badge-stok-alert {
        position: absolute;
        top: 12px;
        right: 12px;
        background-color: #ff4d4d;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 4px 10px;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(255, 77, 77, 0.3);
    }

    .card-title-custom {
        font-weight: bold;
        color: #5a2d4b;
        font-size: 17px;
        margin-bottom: 8px;
    }

    .card-desc {
        color: #8c5a7d;
        font-size: 13px;
        margin-bottom: 20px;
    }

    /* Tombol Aksi */
    .btn-custom-action {
        background-color: #e59ed1;
        color: white;
        border-radius: 12px;
        padding: 8px 22px;
        font-weight: bold;
        border: none;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 5px 15px rgba(229, 158, 209, 0.3);
        transition: 0.2s;
        font-size: 13px;
    }

    .btn-custom-action:hover {
        background-color: #d487bc;
        color: white;
    }
</style>

<div class="dashboard-wrapper">
    <div class="container-fluid px-4">
        
        <!-- Notifikasi Peringatan jika ada Stok Barang Habis -->
        <?php if ($jumlah_stok_habis > 0): ?>
            <div class="alert alert-warning alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 20px; border: 2px solid #f6d198; background-color: #fff9e6; color: #7a521c;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Perhatian!</strong> Ada <b><?php echo $jumlah_stok_habis; ?> produk/hijab</b> yang stoknya sudah habis di butik. Segera lakukan pengecekan!
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Banner Sambutan -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>Kin-kin Collection Dashboard</h1>
                <p>Halo, <b><?php echo htmlspecialchars($_SESSION['Username']); ?></b>! Selamat bertugas melayani pelanggan dan menata butik hari ini.</p>
            </div>
            <div>
                <span class="badge" style="background-color: #e59ed1; color: white; padding: 10px 18px; border-radius: 15px; font-size: 13px;">
                    Kasir Aktif
                </span>
            </div>
        </div>

        <!-- Bagian Menu Utama (Data Pelanggan, Transaksi, Stok Barang) -->
        <div class="row g-4">
            
            <!-- Menu 1: Data Pelanggan -->
            <div class="col-md-4">
                <div class="hijab-rack-card">
                    <div class="rack-visual">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="card-title-custom">Data Pelanggan</div>
                    <p class="card-desc">Kelola dan tambah data pelanggan setia yang berbelanja hijab di butik.</p>
                    <a href="pelanggan.php" class="btn-custom-action">Kelola Pelanggan</a>
                </div>
            </div>

            <!-- Menu 2: Transaksi Penjualan -->
            <div class="col-md-4">
                <div class="hijab-rack-card">
                    <div class="rack-visual">
                        <i class="bi bi-bag-heart-fill"></i>
                    </div>
                    <div class="card-title-custom">Transaksi Penjualan</div>
                    <p class="card-desc">Catat pembelian produk hijab & pakaian harian dengan cepat dan akurat.</p>
                    <a href="transaksi.php" class="btn-custom-action">Buka Transaksi</a>
                </div>
            </div>

            <!-- Menu 3: Stok Barang -->
            <div class="col-md-4">
                <div class="hijab-rack-card">
                    <div class="rack-visual">
                        <i class="bi bi-box-seam-fill"></i>
                        <?php if ($jumlah_stok_habis > 0): ?>
                            <span class="badge-stok-alert"><?php echo $jumlah_stok_habis; ?> Kosong</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-title-custom">Stok Barang</div>
                    <p class="card-desc">Pantau ketersediaan model hijab, pashmina, dan pakaian di rak display.</p>
                    <a href="barang.php" class="btn-custom-action">Cek Stok</a>
                </div>
            </div>

        </div>

    </div>
</div>