<?php 
session_start();
if ($_SESSION['status_login'] != "login" || $_SESSION['Status'] != 1) {
    header("location:../login.php");
    exit();
}
include '../koneksi.php';

// Logika untuk memproses penyimpanan data saat tombol 'simpan' ditekan
if (isset($_POST['simpan'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['Username']);
    $password = md5($_POST['Password']);
    $status   = mysqli_real_escape_string($koneksi, $_POST['Status']);

    $query = mysqli_query($koneksi, "INSERT INTO t_user (Username, Password, Status) VALUES ('$username', '$password', '$status')");

    if ($query) {
        header("location: user.php?pesan=tambah_sukses");
        exit();
    } else {
        $error = "Gagal menyimpan data user!";
    }
}

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
        padding: 8px 30px 8px 0; /* Tambah ruang di kanan untuk icon mata */
        font-size: 15px;
        color: #5a2d4b;
        outline: none;
        transition: 0.3s;
    }

    .custom-input:focus {
        border-bottom: 2px solid #e59ed1; 
    }

    /* Pembungkus Password & Tombol Mata */
    .password-wrapper {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
    }

    .toggle-password {
        position: absolute;
        right: 0;
        background: none;
        border: none;
        color: #5a2d4b;
        cursor: pointer;
        font-size: 18px;
        padding: 0 5px;
    }

    /* Radio Button Custom */
    .status-option {
        display: flex;
        align-items: center;
        cursor: pointer;
        gap: 15px;
        margin-bottom: 12px;
    }

    .status-option input[type="radio"] {
        display: none; 
    }

    .circle-indicator {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 3px solid transparent; 
        transition: all 0.3s ease;
        display: inline-block;
    }

    /* Warna Lingkaran Status */
    .circle-1 { background-color: #e8bade; } /* Admin */
    .circle-2 { background-color: #f2cbe6; } /* Kasir */

    /* Efek Saat Radio Terpilih */
    input[type="radio"]:checked + .circle-indicator {
        border-color: #5a2d4b; 
        transform: scale(1.15);
        box-shadow: 0 4px 10px rgba(90, 45, 75, 0.15);
    }

    .status-label {
        font-size: 14px;
        font-weight: 600;
        color: #5a2d4b;
    }
</style>

<div class="form-wrapper">
    <div class="form-card">
        <form action="" method="post" id="formTambahUser">
            
            <div class="form-header">
                <h2 style="font-family: 'Georgia', 'Times New Roman', serif; font-style: italic; font-weight: bold; color: #5a2d4b; margin: 0; font-size: 24px;">Form Tambah User</h2>
                <button type="submit" name="simpan" form="formTambahUser" class="btn" style="background-color: #e59ed1; color: white; border-radius: 12px; padding: 10px 35px; font-weight: bold; border: none; box-shadow: 0 10px 25px rgba(229, 158, 209, 0.3); cursor: pointer; font-size: 14px;">
                    Simpan
                </button>
            </div>

            <div style="padding: 40px 50px; background-color: white;">
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mb-4" role="alert" style="border-radius: 12px;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="input-group-custom">
                    <label>Username</label>
                    <input type="text" name="Username" class="custom-input" placeholder="Masukkan username..." required autocomplete="off">
                </div>

                <div class="input-group-custom">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="Password" id="password_input" class="custom-input" placeholder="Masukkan password..." required>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class="bi bi-eye-slash" id="icon_password"></i>
                        </button>
                    </div>
                </div>

                <div class="input-group-custom" style="align-items: flex-start;">
                    <label style="margin-top: 3px;">Status User</label>
                    <div style="display: flex; flex-direction: column;">
                        
                        <label class="status-option">
                            <input type="radio" name="Status" value="1" required checked>
                            <div class="circle-indicator circle-1"></div>
                            <span class="status-label">Admin</span>
                        </label>

                        <label class="status-option">
                            <input type="radio" name="Status" value="2">
                            <div class="circle-indicator circle-2"></div>
                            <span class="status-label">Kasir</span>
                        </label>

                    </div>
                </div>

            </div>
        </form>
    </div>

    <div style="text-align: center;">
        <a href="user.php" style="color: #e59ed1; font-weight: bold; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Data User
        </a>
    </div>
</div>

</div> <!-- Penutup main-content -->

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password_input');
    const iconPassword = document.getElementById('icon_password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        iconPassword.classList.remove('bi-eye-slash');
        iconPassword.classList.add('bi-eye');
    } else {
        passwordInput.type = 'password';
        iconPassword.classList.remove('bi-eye');
        iconPassword.classList.add('bi-eye-slash');
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>