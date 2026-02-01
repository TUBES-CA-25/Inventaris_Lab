<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}

$isEdit = isset($data['barang']);
$barang = $isEdit ? $data['barang'] : [];
$title = $isEdit ? "Ubah Data Barang" : "Tambah Barang Baru";
$formAction = $isEdit ? BASEURL . "DetailBarang/ubahBarang" : BASEURL . "DetailBarang/tambahBarang";
?>

<div class="content">
    <div class="container-fluid p-4">

        <div class="form-card">
            <a href="<?= BASEURL; ?>DetailBarang" class="btn-close-absolute">
                <i class="fa-solid fa-times"></i>
            </a>

            <h2 class="form-title"><?= $title; ?></h2>

            <form action="<?= $formAction; ?>" method="post" enctype="multipart/form-data">

                <?php if ($isEdit): ?>
                    <input type="hidden" name="id_barang" value="<?= $barang['id_barang']; ?>">
                    <input type="hidden" name="foto_lama" value="<?= $barang['foto_barang']; ?>">
                <?php endif; ?>

                <div class="row g-4">
                    <div class="col-12 col-lg-6">
                        <!-- Left Column -->

                        <div class="form-group mb-4" id="group-jenis">
                            <label class="form-label">Jenis Barang</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select name="sub_barang" id="select-jenis" class="form-select"
                                    onfocus="this.dataset.prev = this.value;"
                                    onchange="checkSelection('jenis')" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <?php foreach ($data['sub_barang'] as $opt) : ?>
                                        <option value="<?= $opt['id_jenis_barang'] ?>"
                                            <?= ($isEdit && $barang['id_jenis_barang'] == $opt['id_jenis_barang']) ? 'selected' : '' ?>>
                                            <?= $opt['sub_barang'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="NEW" style="font-weight:bold; color:blue;">+ Tambah Jenis Baru</option>
                                </select>

                                <button type="button" id="btn-delete-jenis" class="btn-delete-master" onclick="hapusMaster('jenis')" title="Hapus Data Ini" style="display:none;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>

                            <div id="input-container-jenis" style="display:none; margin-top: 10px;">
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" name="sub_barang_baru" id="input-jenis" class="form-input" placeholder="Nama Jenis" disabled style="flex: 2;">
                                    <input type="text" name="grup_sub_baru" id="input-grup-jenis" class="form-input" placeholder="Grup (A-Z)" maxlength="1" disabled style="flex: 1;">
                                    <button type="button" class="btn-cancel-input" onclick="cancelInput('jenis')" title="Batal"><i class="fa-solid fa-times"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Spesifikasi</label>
                            <input type="text" name="spesifikasi_barang" class="form-input" placeholder="Contoh: RAM 8GB..." required value="<?= $isEdit ? $barang['spesifikasi_barang'] : '' ?>">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah_barang" class="form-input" min="1" required value="<?= $isEdit ? $barang['jumlah_total'] : '1' ?>">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Tgl Pengadaan</label>
                            <input type="date" name="tgl_pengadaan_barang" class="form-input" required value="<?= $isEdit ? $barang['tgl_pengadaan_barang'] : '' ?>">
                        </div>

                        <div class="form-group mb-4" id="group-lokasi">
                            <label class="form-label">Lokasi Penyimpanan</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select name="lokasi_penyimpanan" id="select-lokasi" class="form-select"
                                    onfocus="this.dataset.prev = this.value;"
                                    onchange="checkSelection('lokasi')" required>
                                    <option value="">-- Pilih Lokasi --</option>
                                    <?php foreach ($data['lokasiPenyimpanan'] as $opt) : ?>
                                        <option value="<?= $opt['id_lokasi_penyimpanan'] ?>"
                                            <?= ($isEdit && $barang['id_lokasi_penyimpanan'] == $opt['id_lokasi_penyimpanan']) ? 'selected' : '' ?>>
                                            <?= $opt['nama_lokasi_penyimpanan'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="NEW" style="font-weight:bold; color:blue;">+ Tambah Lokasi Baru</option>
                                </select>
                                <button type="button" id="btn-delete-lokasi" class="btn-delete-master" onclick="hapusMaster('lokasi')" title="Hapus Data Ini" style="display:none;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>

                            <div id="input-container-lokasi" style="display:none; margin-top: 10px;">
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" name="lokasi_baru" id="input-lokasi" class="form-input" placeholder="Nama Lokasi Baru..." disabled style="flex: 1;">
                                    <button type="button" class="btn-cancel-input" onclick="cancelInput('lokasi')" title="Batal"><i class="fa-solid fa-times"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4" id="group-status">
                            <label class="form-label">Status</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select name="status" id="select-status" class="form-select"
                                    onfocus="this.dataset.prev = this.value;"
                                    onchange="checkSelection('status')" required>
                                    <option value="">-- Pilih Status --</option>
                                    <?php foreach ($data['status'] as $opt) : ?>
                                        <option value="<?= $opt['id_status'] ?>"
                                            <?= ($isEdit && $barang['id_status'] == $opt['id_status']) ? 'selected' : '' ?>>
                                            <?= $opt['status'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="NEW" style="font-weight:bold; color:blue;">+ Tambah Status Baru</option>
                                </select>
                                <button type="button" id="btn-delete-status" class="btn-delete-master" onclick="hapusMaster('status')" title="Hapus Data Ini" style="display:none;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>

                            <div id="input-container-status" style="display:none; margin-top: 10px;">
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" name="status_baru" id="input-status" class="form-input" placeholder="Status Baru..." disabled style="flex: 1;">
                                    <button type="button" class="btn-cancel-input" onclick="event.preventDefault(); cancelInput('jenis');" title="Batal">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Left Column -->

                    <div class="col-12 col-lg-6">
                        <!-- Right Column -->
                        <div class="form-group mb-4" id="group-merek">
                            <label class="form-label">Merek Barang</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select name="nama_merek_barang" id="select-merek" class="form-select"
                                    onfocus="this.dataset.prev = this.value;"
                                    onchange="checkSelection('merek')" required>
                                    <option value="">-- Pilih Merek --</option>
                                    <?php foreach ($data['nama_merek_barang'] as $opt) : ?>
                                        <option value="<?= $opt['id_merek_barang'] ?>"
                                            <?= ($isEdit && $barang['id_merek_barang'] == $opt['id_merek_barang']) ? 'selected' : '' ?>>
                                            <?= $opt['nama_merek_barang'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="NEW" style="font-weight:bold; color:blue;">+ Tambah Merek Baru</option>
                                </select>
                                <button type="button" id="btn-delete-merek" class="btn-delete-master" onclick="hapusMaster('merek')" title="Hapus Data Ini" style="display:none;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>

                            <div id="input-container-merek" style="display:none; margin-top: 10px;">
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" name="nama_merek_baru" id="input-merek" class="form-input" placeholder="Nama Merek" disabled style="flex: 2;">
                                    <input type="text" name="kode_merek_baru" id="input-kode-merek" class="form-input" placeholder="Kode (001)" maxlength="3" disabled style="flex: 1;">
                                    <button type="button" class="btn-cancel-input" onclick="cancelInput('merek'); return false;" title="Batal">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Kondisi Barang</label>
                            <select name="kondisi_barang" class="form-select" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <?php foreach ($data['kondisiBarang'] as $opt) : ?>
                                    <option value="<?= $opt['id_kondisi_barang'] ?>"
                                        <?= ($isEdit && $barang['id_kondisi_barang'] == $opt['id_kondisi_barang']) ? 'selected' : '' ?>>
                                        <?= $opt['kondisi_barang'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-4" id="group-satuan">
                            <label class="form-label">Satuan</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select name="satuan" id="select-satuan" class="form-select"
                                    onfocus="this.dataset.prev = this.value;"
                                    onchange="checkSelection('satuan')" required>
                                    <option value="">-- Pilih Satuan --</option>
                                    <?php foreach ($data['satuan'] as $opt) : ?>
                                        <option value="<?= $opt['id_satuan'] ?>"
                                            <?= ($isEdit && $barang['id_satuan'] == $opt['id_satuan']) ? 'selected' : '' ?>>
                                            <?= $opt['nama_satuan'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="NEW" style="font-weight:bold; color:blue;">+ Tambah Satuan Baru</option>
                                </select>
                                <button type="button" id="btn-delete-satuan" class="btn-delete-master" onclick="hapusMaster('satuan')" title="Hapus Data Ini" style="display:none;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>

                            <div id="input-container-satuan" style="display:none; margin-top: 10px;">
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" name="satuan_baru" id="input-satuan" class="form-input" placeholder="Satuan Baru..." disabled style="flex: 1;">
                                    <button type="button" class="btn-cancel-input" onclick="cancelInput('satuan')" title="Batal"><i class="fa-solid fa-times"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Keterangan Label</label>
                            <select name="keterangan_label" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Sudah" <?= ($isEdit && $barang['keterangan_label'] == 'Sudah') ? 'selected' : '' ?>>Sudah</option>
                                <option value="Belum" <?= ($isEdit && $barang['keterangan_label'] == 'Belum') ? 'selected' : '' ?>>Belum</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Detail Penyimpanan</label>
                            <input type="text" name="deskripsi_detail_lokasi" class="form-input" placeholder="Rak 2..." value="<?= $isEdit ? $barang['deskripsi_detail_lokasi'] : '' ?>">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Upload Foto <?= $isEdit ? '(Opsional)' : '' ?></label>
                            <input type="file" name="foto_barang" class="form-input" accept="image/*">
                            <?php if ($isEdit && !empty($barang['foto_barang'])): ?>
                                <small style="display:block; margin-top:5px;">File: <?= basename($barang['foto_barang']) ?></small>
                            <?php endif; ?>
                        </div>
                        <!-- End Right Column -->
                    </div>

                    <?php if (!$isEdit): ?>
                        <div style="margin-top: 20px; border-top: 1px dashed #ddd; padding-top: 20px;">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Status Peminjaman Default</label>
                                        <select name="status_pinjam" class="form-select">
                                            <option value="Bisa">Bisa Dipinjam</option>
                                            <option value="Tidak Bisa">Tidak Bisa</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <div style="margin-top: 20px; border-top: 1px dashed #ddd; padding-top: 20px;">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Status Peminjaman</label>
                                        <select name="status_pinjam" class="form-select">
                                            <option value="Bisa" <?= ($barang['status_peminjaman'] == 'Bisa') ? 'selected' : '' ?>>Bisa Dipinjam</option>
                                            <option value="Tidak Bisa" <?= ($barang['status_peminjaman'] == 'Tidak Bisa') ? 'selected' : '' ?>>Tidak Bisa</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="btn-submit-container">
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-save me-2"></i>
                            <?= $isEdit ? 'Simpan Perubahan Unit Ini' : 'Proses & Generate QR Code' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const lastSelectValue = {};

    function checkSelection(type) {
        const select = document.getElementById('select-' + type);
        const inputContainer = document.getElementById('input-container-' + type);
        const btnDelete = document.getElementById('btn-delete-' + type);

        if (!select || !inputContainer || !btnDelete) {
            console.error('Element tidak ditemukan untuk type:', type);
            return;
        }

        const inputs = inputContainer.querySelectorAll('input');

        if (select.value !== 'NEW' && select.value !== '') {
            lastSelectValue[type] = select.value;
        }

        if (select.value === 'NEW') {
            select.style.display = 'none';
            btnDelete.style.display = 'none';
            inputContainer.style.display = 'block';

            inputs.forEach(input => {
                input.disabled = false;
                input.value = '';
            });

            if (inputs[0]) {
                inputs[0].focus();
            }
            return;
        }

        inputContainer.style.display = 'none';
        select.style.display = 'block';

        if (select.value && select.value !== '') {
            btnDelete.style.display = 'flex';
        } else {
            btnDelete.style.display = 'none';
        }
    }

    function cancelInput(type) {
        try {
            console.log('cancelInput dipanggil untuk:', type);

            const inputContainer = document.getElementById('input-container-' + type);
            const select = document.getElementById('select-' + type);
            const btnDelete = document.getElementById('btn-delete-' + type);

            if (!inputContainer || !select || !btnDelete) {
                console.error('Element tidak ditemukan:', {
                    inputContainer: !!inputContainer,
                    select: !!select,
                    btnDelete: !!btnDelete
                });
                return false;
            }

            inputContainer.style.display = 'none';

            const inputs = inputContainer.querySelectorAll('input');
            inputs.forEach(input => {
                if (input) {
                    input.value = '';
                    input.disabled = true;
                }
            });

            select.style.display = 'block';

            if (lastSelectValue[type]) {
                select.value = lastSelectValue[type];
                btnDelete.style.display = 'flex';
            } else {
                select.value = '';
                btnDelete.style.display = 'none';
            }

            console.log('Cancel berhasil, nilai dropdown:', select.value);
            return false;

        } catch (error) {
            console.error('Error di cancelInput:', error);
            return false;
        }
    }

    function hapusMaster(type) {
        try {
            const select = document.getElementById('select-' + type);
            if (!select || !select.value) {
                console.error('Select tidak ditemukan atau tidak ada nilai');
                return;
            }

            const id = select.value;
            const text = select.options[select.selectedIndex].text.trim();

            // Menggunakan SweetAlert2 sebagai pengganti confirm
            Swal.fire({
                title: 'Hapus Data Master?',
                text: "Anda akan menghapus '" + text + "'. Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Arahkan ke URL penghapusan jika user klik Ya
                    window.location.href = "<?= BASEURL; ?>DetailBarang/hapusMaster/" + type + "/" + id;
                }
            });

        } catch (error) {
            console.error('Error di hapusMaster:', error);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        console.log('DOM loaded, inisialisasi dropdown...');

        const types = ['jenis', 'lokasi', 'status', 'merek', 'satuan'];

        types.forEach(type => {
            try {
                const select = document.getElementById('select-' + type);
                const inputContainer = document.getElementById('input-container-' + type);

                if (!select) {
                    console.warn('Select tidak ditemukan untuk:', type);
                    return;
                }

                if (select.value && select.value !== 'NEW' && select.value !== '') {
                    lastSelectValue[type] = select.value;
                    console.log('Nilai awal ' + type + ':', select.value);
                }

                if (inputContainer) {
                    inputContainer.style.display = 'none';
                }

                checkSelection(type);

            } catch (error) {
                console.error('Error inisialisasi ' + type + ':', error);
            }
        });
    });
</script>