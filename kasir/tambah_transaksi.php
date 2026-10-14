<?php 
session_start();
if ($_SESSION['status_login'] != "login") {
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
        max-width: 850px;
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
</style>

<!-- Tambahan CDN CSS Select2 dan tema Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<div class="form-wrapper">
    <div class="form-card">
        <form method="POST" action="aksi_transaksi.php" id="form-transaksi">
            <!-- Token unik untuk mencegah data dobel/double submit -->
            <?php 
            $token = md5(uniqid(rand(), true));
            $_SESSION['current_token'] =$token;
            ?>
            <input type="hidden" name="form_token" value="<?php echo $token; ?>">

            <div class="form-header">
                <h2 style="font-family: 'Georgia', 'Times New Roman', serif; font-style: italic; font-weight: bold; color: #5a2d4b; margin: 0; font-size: 24px;">Entri Transaksi Penjualan Baru</h2>
                <button type="submit" name="simpan_transaksi" id="btn-submit" class="btn" style="background-color: #e59ed1; color: white; border-radius: 12px; padding: 10px 25px; font-weight: bold; border: none; box-shadow: 0 10px 25px rgba(229, 158, 209, 0.3); cursor: pointer; font-size: 14px;">
                    Proses & Simpan
                </button>
            </div>

            <div style="padding: 40px 50px; background-color: white;">
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #5a2d4b;">Tanggal Penjualan</label>
                        <input type="date" name="TglPenjualan" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #5a2d4b;">Pilih Pelanggan</label>
                        <select name="PelangganID" class="form-control select-pelanggan" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php 
                            $pelanggan = mysqli_query($koneksi, "SELECT * FROM t_pelanggan");
                            while ($p = mysqli_fetch_array($pelanggan)) {
                            ?>
                                <option value="<?php echo $p['PelangganID']; ?>"><?php echo $p['NamaPelanggan']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- Tambahan Pilihan Metode Pembayaran -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #5a2d4b;">Metode Pembayaran</label>
                        <select name="MetodePembayaran" id="metodePembayaran" class="form-select" required>
                            <option value="Cash">Cash (Tunai)</option>
                            <option value="Transfer">Transfer Bank</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="color: #5a2d4b;">Rincian Produk yang Dibeli</h5>
                    <button type="button" class="btn btn-sm text-white" id="tambah-baris" style="background-color: #e59ed1; border-radius: 8px;">+ Tambah Produk Lain</button>
                </div>

                <!-- Wrapper tempat baris produk bertambah -->
                <div id="wrapper-produk">
                    <div class="row mb-3 baris-produk align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold" style="color: #5a2d4b;">Pilih Produk</label>
                            <select name="ProdukID[]" class="form-control select-produk" required>
                                <option value="">-- Pilih Produk --</option>
                                <?php 
                                $produk = mysqli_query($koneksi, "SELECT * FROM t_produk WHERE Stok > 0");
                                while ($pr = mysqli_fetch_array($produk)) {
                                ?>
                                    <option value="<?php echo $pr['ProdukID']; ?>" data-harga="<?php echo $pr['Harga']; ?>" data-stok="<?php echo $pr['Stok']; ?>">
                                        <?php echo $pr['NamaProduk']; ?> - Rp <?php echo number_format($pr['Harga'], 0, ',', '.'); ?> (Stok: <?php echo$pr['Stok']; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="color: #5a2d4b;">Jumlah Beli (QTY)</label>
                            <input type="number" name="JumlahProduk[]" class="form-control input-qty" value="1" min="1" required>
                            <div class="text-danger small mt-1 error-stok" style="display: none;"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="color: #5a2d4b;">Subtotal</label>
                            <input type="text" class="form-control subtotal-text" value="Rp 0" readonly>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm hapus-baris w-100" style="display:none;">X</button>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Bagian Pembayaran dan Kembalian -->
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold" style="color: #5a2d4b;">Total Belanja</label>
                            <input type="text" id="text-total-belanja" class="form-control fw-bold fs-5 text-end" value="Rp 0" readonly>
                            <input type="hidden" name="TotalHarga" id="input-total-harga" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold" style="color: #5a2d4b;">Uang Dibayar</label>
                            <input type="number" name="Bayar" id="input-bayar" class="form-control fw-bold fs-5 text-end" min="0" placeholder="0" required>
                            <div class="text-danger small mt-1" id="error-bayar" style="display: none;">Uang pembayaran kurang!</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold" style="color: #5a2d4b;">Uang Kembalian</label>
                            <input type="text" id="text-kembalian" class="form-control fw-bold fs-5 text-end text-success" value="Rp 0" readonly>
                            <input type="hidden" name="Kembalian" id="input-kembalian" value="0">
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <div style="text-align: center;">
        <a href="transaksi.php" style="color: #e59ed1; font-weight: bold; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Data Transaksi
        </a>
    </div>
</div>

</div> <!-- Penutup main-content dari header.php -->

<!-- Panggil jQuery dan JS Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function initSelect2(element) {
        $(element).select2({
            theme: 'bootstrap-5',
            placeholder: 'Cari atau pilih...'
        });
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function hitungKalkulasi() {
        let totalBelanja = 0;
        const barisProduk = document.querySelectorAll('.baris-produk');

        barisProduk.forEach(function(baris) {
            const selectProduk = baris.querySelector('.select-produk');
            const inputQty = baris.querySelector('.input-qty');
            const subtotalText = baris.querySelector('.subtotal-text');
            const pesanError = baris.querySelector('.error-stok');

            const selectedOption = selectProduk.options[selectProduk.selectedIndex];
            const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
            const stokTersedia = parseInt(selectedOption.getAttribute('data-stok')) || 0;
            const qty = parseInt(inputQty.value) || 0;

            if (selectedOption.value !== "" && qty > stokTersedia) {
                pesanError.textContent = `Stok tidak cukup! Maksimal: ${stokTersedia}`;
                pesanError.style.display = 'block';
                inputQty.classList.add('is-invalid');
            } else {
                pesanError.style.display = 'none';
                inputQty.classList.remove('is-invalid');
            }

            const subtotal = harga * qty;
            subtotalText.value = formatRupiah(subtotal);
            totalBelanja += subtotal;
        });

        document.getElementById('text-total-belanja').value = formatRupiah(totalBelanja);
        document.getElementById('input-total-harga').value = totalBelanja;

        const metode = document.getElementById('metodePembayaran').value;
        const inputBayar = document.getElementById('input-bayar');

        if (metode === 'Transfer' || metode === 'QRIS') {
            inputBayar.value = totalBelanja;
            inputBayar.readOnly = true;
        } else if (!inputBayar.readOnly && metode === 'Cash') {
            // Biarkan manual jika cash
        }

        const bayar = parseFloat(inputBayar.value) || 0;
        const kembalian = bayar - totalBelanja;
        const errorBayar = document.getElementById('error-bayar');

        if (bayar > 0 && bayar < totalBelanja) {
            errorBayar.style.display = 'block';
            document.getElementById('text-kembalian').value = 'Rp 0';
            document.getElementById('input-kembalian').value = 0;
        } else {
            errorBayar.style.display = 'none';
            const kembalianFinal = kembalian > 0 ? kembalian : 0;
            document.getElementById('text-kembalian').value = formatRupiah(kembalianFinal);
            document.getElementById('input-kembalian').value = kembalianFinal;
        }
    }

    document.getElementById('metodePembayaran').addEventListener('change', function() {
        const metode = this.value;
        const inputBayar = document.getElementById('input-bayar');
        if (metode === 'Transfer' || metode === 'QRIS') {
            inputBayar.readOnly = true;
        } else {
            inputBayar.readOnly = false;
            inputBayar.value = '';
        }
        hitungKalkulasi();
    });

    function validasiSemuaStokDanBayar() {
        const barisProduk = document.querySelectorAll('.baris-produk');
        let semuaValid = true;
        
        barisProduk.forEach(function(baris) {
            const selectProduk = baris.querySelector('.select-produk');
            const inputQty = baris.querySelector('.input-qty');
            const stokTersedia = parseInt(selectProduk.options[selectProduk.selectedIndex].getAttribute('data-stok')) || 0;
            if (parseInt(inputQty.value) > stokTersedia) {
                semuaValid = false;
            }
        });

        const totalBelanja = parseFloat(document.getElementById('input-total-harga').value) || 0;
        const bayar = parseFloat(document.getElementById('input-bayar').value) || 0;
        if (bayar < totalBelanja) {
            semuaValid = false;
        }

        return semuaValid;
    }

    $(document).ready(function() {
        initSelect2('.select-produk');
        initSelect2('.select-pelanggan');

        $(document).on('change', '.select-produk', function() {
            hitungKalkulasi();
        });
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-qty') || e.target.id === 'input-bayar') {
            hitungKalkulasi();
        }
    });

    document.getElementById('form-transaksi').addEventListener('submit', function(e) {
        if (!validasiSemuaStokDanBayar()) {
            e.preventDefault();
            alert('Periksa kembali! Pastikan stok mencukupi dan uang pembayaran tidak kurang.');
        }
    });

    document.getElementById('tambah-baris').addEventListener('click', function() {
        const wrapper = document.getElementById('wrapper-produk');
        const barisPertama = wrapper.querySelector('.baris-produk');
        
        $(barisPertama).find('.select-produk').select2('destroy');
        const barisBaru = barisPertama.cloneNode(true);
        initSelect2($(barisPertama).find('.select-produk'));

        barisBaru.querySelector('select').value = '';
        barisBaru.querySelector('.input-qty').value = '1';
        barisBaru.querySelector('.subtotal-text').value = 'Rp 0';
        barisBaru.querySelector('.error-stok').style.display = 'none';
        barisBaru.querySelector('.input-qty').classList.remove('is-invalid');
        
        const btnHapus = barisBaru.querySelector('.hapus-baris');
        btnHapus.style.display = 'block';
        
        btnHapus.addEventListener('click', function() {
            $(barisBaru).find('.select-produk').select2('destroy');
            barisBaru.remove();
            hitungKalkulasi();
        });

        wrapper.appendChild(barisBaru);
        initSelect2($(barisBaru).find('.select-produk'));
    });
</script>
</body>
</html>