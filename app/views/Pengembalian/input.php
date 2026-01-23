<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<style>
/* Modern Clean Design */
.input-page {
    padding: 30px;
    margin-left: 280px;
    min-height: 100vh;
    background: #f5f7fa;
}

.input-container {
    background: white;
    border-radius: 20px;
    padding: 40px;
    max-width: 1200px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    position: relative;
}

.page-title {
    color: #0d1b3e;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 30px;
}

.form-label-custom {
    color: #0d1b3e;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
}

.form-control-custom {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e8ecf1;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #fafbfc;
}

.form-control-custom:focus {
    outline: none;
    border-color: #0d1b3e;
    background: white;
}

.form-control-custom::placeholder {
    color: #a8b2c1;
}

.date-input-wrapper {
    position: relative;
}

.date-input-wrapper input[type="date"] {
    position: relative;
    padding-right: 40px;
}

.date-input-wrapper::after {
    content: '\f073';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #0d1b3e;
    pointer-events: none;
}

.select-custom {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%230d1b3e' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    padding-right: 40px;
}

.robot-illustration {
    position: absolute;
    right: 40px;
    top: 80px;
    width: 200px;
    opacity: 0.9;
}

.btn-submit {
    background: #0d1b3e;
    color: white;
    padding: 14px 50px;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    float: right;
    margin-top: 30px;
}

.btn-submit:hover {
    background: #1a2d5a;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 27, 62, 0.3);
}

.item-group {
    background: #f8f9fc;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    border: 1px solid #e8ecf1;
}

.add-item-btn {
    background: transparent;
    border: 2px dashed #0d1b3e;
    color: #0d1b3e;
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 15px;
}

.add-item-btn:hover {
    background: #0d1b3e;
    color: white;
}

.remove-item-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 12px;
    cursor: pointer;
    float: right;
}

/* Responsive */
@media (max-width: 1200px) {
    .robot-illustration {
        width: 150px;
        right: 20px;
    }
}

@media (max-width: 992px) {
    .input-page {
        margin-left: 0;
        padding: 20px;
    }
    
    .robot-illustration {
        position: relative;
        right: auto;
        top: auto;
        margin: 20px auto;
        display: block;
    }
}
</style>

<div class="input-page">
    <div class="input-container">
        <!-- Robot Illustration -->
        <img src="<?= BASEURL; ?>img/robot-illustration.svg" alt="Robot" class="robot-illustration" 
             onerror="this.style.display='none'">

        <h2 class="page-title">Input Pengembalian Barang</h2>

        <form action="<?= BASEURL; ?>Pengembalian/proses_input" method="POST" id="formPengembalian">
            <input type="hidden" name="id_peminjaman" value="<?= $data['peminjaman']['id_peminjaman'] ?? ''; ?>">

            <!-- Judul Kegiatan -->
            <div class="mb-4">
                <label class="form-label-custom">Judul kegiatan</label>
                <input type="text" class="form-control-custom" name="judul_kegiatan" 
                       value="<?= htmlspecialchars($data['peminjaman']['judul_kegiatan'] ?? ''); ?>" 
                       readonly>
            </div>

            <!-- Tanggal Pengajuan -->
            <div class="mb-4">
                <label class="form-label-custom">Tanggal pengajuan</label>
                <div class="date-input-wrapper">
                    <input type="date" class="form-control-custom" name="tanggal_pengajuan" 
                           value="<?= $data['peminjaman']['tanggal_pengajuan'] ?? ''; ?>" 
                           readonly>
                </div>
            </div>

            <!-- Mulai dari tanggal & Sampai tanggal -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label-custom">Mulai dari tanggal</label>
                    <div class="date-input-wrapper">
                        <input type="date" class="form-control-custom" name="tanggal_peminjaman" 
                               value="<?= $data['peminjaman']['tanggal_peminjaman'] ?? ''; ?>" 
                               readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Sampai tanggal</label>
                    <div class="date-input-wrapper">
                        <input type="date" class="form-control-custom" name="tanggal_pengembalian" 
                               value="<?= $data['peminjaman']['tanggal_pengembalian'] ?? ''; ?>" 
                               readonly>
                    </div>
                </div>
            </div>

            <!-- Container untuk Item Barang -->
            <div id="itemsContainer">
                <!-- Item Barang 1 (Default) -->
                <div class="item-group" data-item="1">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Jenis barang</label>
                            <select class="form-control-custom select-custom" name="jenis_barang[]" required>
                                <option value="">Select Option</option>
                                <?php if (!empty($data['jenis_barang'])): ?>
                                    <?php foreach ($data['jenis_barang'] as $jenis): ?>
                                        <option value="<?= $jenis['id_jenis_barang']; ?>"
                                            <?= (isset($data['peminjaman']['id_jenis_barang']) && $data['peminjaman']['id_jenis_barang'] == $jenis['id_jenis_barang']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($jenis['sub_barang']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label-custom">Jumlah</label>
                            <input type="number" class="form-control-custom" name="jumlah[]" 
                                   value="<?= $data['peminjaman']['jumlah'] ?? ''; ?>" 
                                   placeholder="0" min="1" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label-custom">Keterangan</label>
                            <input type="text" class="form-control-custom" name="keterangan_barang[]" 
                                   placeholder="Masukkan keterangan">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Tambah Item -->
            <button type="button" class="add-item-btn" onclick="addItem()">
                <i class="fas fa-plus"></i> Tambah Barang
            </button>

            <!-- Data Pengembalian -->
            <div class="mt-5 pt-4" style="border-top: 2px solid #e8ecf1;">
                <h5 style="color: #0d1b3e; font-weight: 700; margin-bottom: 20px;">Status Pengembalian</h5>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label-custom">Status Kondisi Barang</label>
                        <select class="form-control-custom select-custom" name="status_pengembalian" required>
                            <option value="">Select Option</option>
                            <option value="Dikembalikan">Dikembalikan - Kondisi Baik</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Hilang">Hilang</option>
                            <option value="Belum Dikembalikan">Belum Dikembalikan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Keterangan Waktu</label>
                        <select class="form-control-custom select-custom" name="keterangan" required>
                            <option value="">Select Option</option>
                            <option value="Tepat Waktu">Tepat Waktu</option>
                            <option value="Tidak Tepat Waktu">Terlambat</option>
                            <option value="Bermasalah">Bermasalah</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-custom">Detail Masalah (Opsional)</label>
                    <textarea class="form-control-custom" name="detail_masalah" rows="4" 
                              placeholder="Tuliskan detail jika ada kerusakan atau masalah..."></textarea>
                </div>
            </div>

            <!-- Tombol Kirim -->
            <div class="clearfix">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Kirim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let itemCount = 1;

function addItem() {
    itemCount++;
    const container = document.getElementById('itemsContainer');
    const newItem = document.createElement('div');
    newItem.className = 'item-group';
    newItem.setAttribute('data-item', itemCount);
    newItem.innerHTML = `
        <button type="button" class="remove-item-btn" onclick="removeItem(this)">
            <i class="fas fa-trash"></i> Hapus
        </button>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label-custom">Jenis barang</label>
                <select class="form-control-custom select-custom" name="jenis_barang[]" required>
                    <option value="">Select Option</option>
                    <?php if (!empty($data['jenis_barang'])): ?>
                        <?php foreach ($data['jenis_barang'] as $jenis): ?>
                            <option value="<?= $jenis['id_jenis_barang']; ?>">
                                <?= htmlspecialchars($jenis['sub_barang']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label-custom">Jumlah</label>
                <input type="number" class="form-control-custom" name="jumlah[]" 
                       placeholder="0" min="1" required>
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label-custom">Keterangan</label>
                <input type="text" class="form-control-custom" name="keterangan_barang[]" 
                       placeholder="Masukkan keterangan">
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removeItem(btn) {
    const itemGroup = btn.closest('.item-group');
    itemGroup.remove();
}

// Form validation
document.getElementById('formPengembalian').addEventListener('submit', function(e) {
    const status = document.querySelector('select[name="status_pengembalian"]').value;
    const keterangan = document.querySelector('select[name="keterangan"]').value;
    
    if (!status || !keterangan) {
        e.preventDefault();
        alert('Mohon lengkapi Status Kondisi Barang dan Keterangan Waktu!');
        return false;
    }
});
</script>
