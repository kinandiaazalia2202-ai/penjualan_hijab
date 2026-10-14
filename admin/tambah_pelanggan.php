<?php 
session_start();
if ($_SESSION['status_login'] != "login" || $_SESSION['Status'] != 1) {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';

include 'header.php'; 
?>

<style>
    /* Wrapper Utama dengan Jarak Atas yang Pas */
    .form-wrapper {
        background-color: #fde8f5; 
        min-height: calc(100vh - 60px); 
        display: flex; 
        flex-direction: column;
        justify-content: center; 
        align-items: center; 
        padding: 60px 20px 40px 20px;
        box-sizing: border-box;
    }

    /* Kotak Form Utama */
    .form-card {
        background: white; 
        border-radius: 30px; 
        overflow: hidden; 
        border: 2px solid #f2cbe6; 
        width: 100%;
        max-width: 650px;
        margin-bottom: 25px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.02);
        transition: all 0.4s ease;
    }

    /* Efek Menyala (Glowing) saat Fokus */
    .form-card:focus-within {
        border-color: #e59ed1; 
        box-shadow: 0 0 35px rgba(229, 158, 209, 0.35), 0 15px 50px rgba(229, 158, 209, 0.2);
        transform: translateY(-3px);
    }

    /* Header Form */
    .form-header {
        background: #fde8f5; 
        padding: 25px 40px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
    }

    .input-group-custom {
        display: flex; 
        align-items: center; 
        margin-bottom: 25px;
    }

    .input-group-custom label {
        width: 180px; 
        color: #5a2d4b; 
        font-weight: bold; 
        font-size: 15px;
        margin-bottom: 0;
    }

    /* Input Minimalis */
    .custom-input {
        border: none; 
        border-bottom: 2px solid #f2cbe6; 
        background: transparent; 
        width: 100%; 
        padding: 8px 0;
        font-size: 15px;
        color: #5a2d4b;
        outline: none;
        transition: 0.3s;
    }

    .custom-input:focus {
        border-bottom: 2px solid #e59ed1; 
    }
</style>

<div class="form-wrapper">
    <div class="form-card">
        <form method="POST" action="aksi_pelanggan.php">
            
            <div class="form-header">
                <h2 style="font-family: 'Georgia', 'Times New Roman', serif; font-style: italic; font-weight: bold; color: #5a2d4b; margin: 0; font-size: 24px;">Tambah Pelanggan Baru</h2>
                <button type="submit" name="tambah" class="btn" style="background-color: #e59ed1; color: white; border-radius: 12px; padding: 10px 35px; font-weight: bold; border: none; box-shadow: 0 10px 25px rgba(229, 158, 209, 0.3); cursor: pointer; font-size: 14px;">
                    Simpan Pelanggan
                </button>
            </div>

            <div style="padding: 40px 50px; background-color: white;">
                
                <div class="input-group-custom">
                    <label>Nama Pelanggan</label>
                    <input type="text" name="NamaPelanggan" class="custom-input" placeholder="Contoh: Siska Amelia" required autocomplete="off">
                </div>

                <div class="input-group-custom" style="align-items: flex-start;">
                    <label style="margin-top: 8px;">Alamat</label>
                    <textarea name="Alamat" class="custom-input" rows="3" placeholder="Contoh: Jl. Merpati No. 12, Kendal" required style="resize: vertical;"></textarea>
                </div>

                <div class="input-group-custom">
                    <label>Nomor Telepon</label>
                    <input type="text" name="NoTelp" class="custom-input" placeholder="Contoh: 081234567890" 
                           required minlength="10" maxlength="13" pattern="[0-9]+" 
                           title="Nomor telepon harus berupa angka, minimal 10 digit, dan maksimal 13 digit!">
                </div>

            </div>
        </form>
    </div>

    <div style="text-align: center;">
        <a href="pelanggan.php" style="color: #e59ed1; font-weight: bold; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Data Pelanggan
        </a>
    </div>
</div>

    </div> <!-- Penutup main-content -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>