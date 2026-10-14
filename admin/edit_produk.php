<?php 
session_start();
if ($_SESSION['status_login'] != "login" || $_SESSION['Status'] != 1) {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';

$id_produk = mysqli_real_escape_string($koneksi, $_GET['id_produk']);

$query = mysqli_query($koneksi, "SELECT * FROM t_produk WHERE ProdukID = '$id_produk'");
$d = mysqli_fetch_array($query);

if (!$d) {
    header("location: produk.php");
    exit();
}

include 'header.php'; 
?>

<style>
    .form-wrapper {
        min-height: calc(100vh - 60px); 
        display: flex; 
        flex-direction: column;
        justify-content: center; 
        align-items: center; 
        padding: 40px 20px;
        box-sizing: border-box;
    }

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

    .form-card:focus-within {
        border-color: #e59ed1; 
        box-shadow: 0 0 35px rgba(229, 158, 209, 0.35), 0 15px 50px rgba(229, 158, 209, 0.2);
        transform: translateY(-3px);
    }

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
        <form method="POST" action="update_produk.php">
            <input type="hidden" name="ProdukID" value="<?php echo $d['ProdukID']; ?>">
            
            <div class="form-header">
                <h2 style="font-family: 'Georgia', 'Times New Roman', serif; font-style: italic; font-weight: bold; color: #5a2d4b; margin: 0; font-size: 24px;">Edit Data Produk</h2>
                <button type="submit" name="edit" class="btn" style="background-color: #e59ed1; color: white; border-radius: 12px; padding: 10px 30px; font-weight: bold; border: none; box-shadow: 0 10px 25px rgba(229, 158, 209, 0.3); cursor: pointer; font-size: 14px;">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
            </div>

            <div style="padding: 40px 50px; background-color: white;">

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mb-4" role="alert" style="border-radius: 12px;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="input-group-custom">
                    <label>ID Produk</label>
                    <span style="color: #5a2d4b; font-size: 15px; font-weight: bold;"><?php echo $d['ProdukID']; ?></span>
                </div>

                <div class="input-group-custom">
                    <label>Nama Produk</label>
                    <input type="text" name="NamaProduk" class="custom-input" value="<?php echo htmlspecialchars($d['NamaProduk']); ?>" required autocomplete="off">
                </div>

                <div class="input-group-custom">
                    <label>Harga (Rp)</label>
                    <input type="number" name="Harga" class="custom-input" value="<?php echo $d['Harga']; ?>" required>
                </div>

                <div class="input-group-custom">
                    <label>Stok</label>
                    <input type="number" name="Stok" class="custom-input" value="<?php echo $d['Stok']; ?>" required>
                </div>

            </div>
        </form>
    </div>

    <div style="text-align: center;">
        <a href="produk.php" style="color: #e59ed1; font-weight: bold; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Data Produk
        </a>
    </div>
</div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>