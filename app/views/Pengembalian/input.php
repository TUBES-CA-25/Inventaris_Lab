<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<meta name="base-url" content="<?= BASEURL; ?>">
<link rel="stylesheet" href="<?= BASEURL; ?>css/pengembalianInput.css">

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
            <div class="status-section">
                <h5>Status Pengembalian</h5>
                
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
