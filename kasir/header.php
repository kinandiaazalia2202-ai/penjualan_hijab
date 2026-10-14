<?php
// 1. LOGIKA SESSION & KONEKSI DATABASE
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menghubungkan database agar $koneksi selalu siap di semua halaman kasir
if (file_exists('../koneksi.php')) {
    include '../koneksi.php';
}

// Menentukan nama kasir untuk teks salam
$nama_kasir = isset($_SESSION['Username']) ? $_SESSION['Username'] : (isset($_SESSION['nama_kasir']) ? $_SESSION['nama_kasir'] : 'Kasir');

// Mengambil nama halaman aktif untuk penanda menu aktif
$halaman_aktif = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - Kin-kin Collection</title>

    <!-- Bootstrap CSS & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --bg-cream: #f6eff7;        /* Latar belakang keunguan muda */
            --font-brown: #5B2C2C;      /* Warna font coklat tua */
            --lilac-sidebar: #d49ad0;   /* Lilac neon pastel/pop untuk navigasi */
            --lilac-card-1: #e59ed1;    /* Kartu 1 */
            --lilac-card-2: #f2cbe6;    /* Kartu 2 */
            --lilac-card-3: #d988c5;    /* Kartu 3 */
            --lilac-card-4: #e8bade;    /* Kartu 4 */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--bg-cream) !important; 
            color: var(--font-brown) !important;
            overflow-x: hidden;
        }

        /* Font serif khusus untuk judul */
        h1, h2, h3, h4, .font-serif, .sidebar-brand {
            font-family: 'Playfair Display', serif !important;
        }

        /* Sidebar Styling */
        aside {
            background-color: var(--lilac-sidebar); 
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }

        aside::-webkit-scrollbar {
            width: 5px;
        }
        aside::-webkit-scrollbar-thumb {
            background: rgba(91, 44, 44, 0.2);
            border-radius: 10px;
        }

        .sidebar-brand {
            text-align: center;
            margin-bottom: 15px;
            font-style: italic;
            font-size: 28px;
            font-weight: 700;
            color: var(--font-brown) !important;
            line-height: 1.1;
        }

        .user-badge {
            padding: 6px 18px;
            margin: 0 auto 30px auto;
            font-size: 13px;
            color: var(--font-brown);
            text-align: center;
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 30px;
            font-weight: 500;
            display: inline-block;
        }

        .nav-link {
            padding: 12px 22px !important;
            margin: 6px 0;
            font-size: 14px !important;
            border-radius: 50px !important;
            color: var(--font-brown) !important;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none !important;
            transition: 0.3s ease;
            font-weight: 500;
        }

        .menu-active { 
            background-color: rgba(255, 255, 255, 0.85) !important; 
            color: var(--font-brown) !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .nav-link:hover:not(.menu-active) {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .main-content {
            margin-left: 260px;
            padding: 40px 50px;
            background-color: var(--bg-cream);
            min-height: 100vh;
            width: calc(100% - 260px);
        }

        .logout-container { 
            margin-top: auto; 
            padding-top: 20px;
        }
        
        .logout-btn {
            background-color: white !important;
            color: var(--font-brown) !important;
            border-radius: 30px !important;
            padding: 10px 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: 0.3s;
        }

        .logout-btn:hover {
            background-color: #f8d7da !important;
            color: #721c24 !important;
        }

        /* Penyesuaian Komponen Bootstrap agar Serasi */
        .card {
            background-color: #ffffff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }

        .btn-primary {
            background-color: var(--lilac-card-1) !important;
            border: none !important;
            color: var(--font-brown) !important;
            font-weight: 600;
            border-radius: 30px;
            padding: 8px 20px;
        }

        .btn-primary:hover {
            background-color: var(--lilac-card-3) !important;
            color: white !important;
        }

        .btn-dark {
            background-color: var(--font-brown) !important;
            border: none !important;
            border-radius: 30px;
        }

        .table-dark {
            --bs-table-bg: var(--lilac-sidebar);
            --bs-table-color: var(--font-brown);
        }

        .table code {
            color: var(--font-brown);
            background-color: var(--bg-cream);
            padding: 2px 8px;
            border-radius: 6px;
        }
    </style>
</head>
<body class="d-flex">

    <!-- Sidebar Navigasi Kasir -->
    <aside>
        <div class="sidebar-brand">
            Kin-kin<br>Collection
        </div>
      
        <div class="user-badge">
            Halo, <b><?php echo htmlspecialchars($nama_kasir); ?>!</b> (Kasir)
        </div>

        <nav>
            <a href="dashboard_kasir.php" class="nav-link <?= ($halaman_aktif == 'dashboard_kasir.php' || $halaman_aktif == 'index.php') ? 'menu-active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>

            <a href="pelanggan.php" class="nav-link <?= (in_array($halaman_aktif, ['pelanggan.php', 'tambah_pelanggan.php', 'edit_pelanggan.php'])) ? 'menu-active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Data Pelanggan</span>
            </a>

            <a href="transaksi.php" class="nav-link <?= (in_array($halaman_aktif, ['transaksi.php', 'tambah_transaksi.php', 'detail_transaksi.php'])) ? 'menu-active' : ''; ?>">
                <i class="fas fa-exchange-alt"></i>
                <span>Transaksi</span>
            </a>

            <a href="produk.php" class="nav-link <?= (in_array($halaman_aktif, ['produk.php'])) ? 'menu-active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Data Produk</span>
            </a>
        </nav>

        <div class="logout-container">
            <a href="../logout.php" class="logout-btn" onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-sign-out-alt"></i> Log out
            </a>
        </div>
    </aside>

    <!-- Konten Utama Halaman -->
    <div class="main-content">