<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}

// LOGIKA DETEKSI MODE (TAMBAH / UBAH)
// Jika ada data['barang'], berarti mode UBAH.
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

                <div class="form-grid">

                    <div class="left-column">

                        <div class="form-group mb-4 input-group-custom" id="group-jenis">
                            <label class="form-label">Jenis Barang</label>

                            <select name="sub_barang" id="select-jenis" class="form-select" onchange="toggleInput('jenis')" required>
                                <option value="">-- Pilih Jenis --</option>
                                <?php foreach ($data['sub_barang'] as $opt) : ?>
                                    <option value="<?= $opt['id_jenis_barang'] ?>"
                                        <?= ($isEdit && $barang['id_jenis_barang'] == $opt['id_jenis_barang']) ? 'selected' : '' ?>>
                                        <?= $opt['sub_barang'] ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="NEW" style="font-weight:bold; color:blue;">+ Tambah Jenis Baru</option>
                            </select>

                            <div id="input-container-jenis" style="display:none; width: 100%;">
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" name="sub_barang_baru" id="input-jenis" class="form-input"
                                        placeholder="Nama Jenis (Ex: Mouse)" style="flex: 2;" disabled>
                                    <input type="text" name="grup_sub_baru" id="input-grup-jenis" class="form-input"
                                        placeholder="Grup (Ex: C)" style="flex: 1;" maxlength="1"
                                        style="text-transform:uppercase" disabled title="Kode Grup">
                                </div>
                            </div>

                            <button type="button" id="btn-cancel-jenis" class="btn-cancel-input" onclick="cancelInput('jenis')" title="Batal">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Spesifikasi</label>
                            <input type="text" name="spesifikasi_barang" class="form-input"
                                placeholder="Contoh: RAM 8GB, Core i5..." required
                                value="<?= $isEdit ? $barang['spesifikasi_barang'] : '' ?>">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah_barang" class="form-input" min="1" required
                                value="<?= $isEdit ? $barang['jumlah_barang'] : '1' ?>">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Tgl Pengadaan</label>
                            <input type="date" name="tgl_pengadaan_barang" class="form-input" required
                                value="<?= $isEdit ? $barang['tgl_pengadaan_barang'] : '' ?>">
                        </div>

                        <div class="form-group mb-4 input-group-custom" id="group-lokasi">
                            <label class="form-label">Lokasi Penyimpanan</label>
                            <select name="lokasi_penyimpanan" id="select-lokasi" class="form-select" onchange="toggleInput('lokasi')" required>
                                <option value="">-- Pilih Lokasi --</option>
                                <?php foreach ($data['lokasiPenyimpanan'] as $opt) : ?>
                                    <option value="<?= $opt['id_lokasi_penyimpanan'] ?>"
                                        <?= ($isEdit && $barang['id_lokasi_penyimpanan'] == $opt['id_lokasi_penyimpanan']) ? 'selected' : '' ?>>
                                        <?= $opt['nama_lokasi_penyimpanan'] ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="NEW" style="font-weight:bold; color:blue;">+ Tambah Lokasi Baru</option>
                            </select>

                            <div id="input-container-lokasi" style="display:none; width: 100%;">
                                <input type="text" name="lokasi_baru" id="input-lokasi" class="form-input" placeholder="Nama Lokasi Baru..." disabled>
                            </div>

                            <button type="button" id="btn-cancel-lokasi" class="btn-cancel-input" onclick="cancelInput('lokasi')" title="Batal">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>

                        <div class="form-group mb-4 input-group-custom" id="group-status">
                            <label class="form-label">Status</label>
                            <select name="status" id="select-status" class="form-select" onchange="toggleInput('status')" required>
                                <option value="">-- Pilih Status --</option>
                                <?php foreach ($data['status'] as $opt) : ?>
                                    <option value="<?= $opt['id_status'] ?>"
                                        <?= ($isEdit && $barang['id_status'] == $opt['id_status']) ? 'selected' : '' ?>>
                                        <?= $opt['status'] ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="NEW" style="font-weight:bold; color:blue;">+ Tambah Status Baru</option>
                            </select>

                            <div id="input-container-status" style="display:none; width: 100%;">
                                <input type="text" name="status_baru" id="input-status" class="form-input" placeholder="Status Baru..." disabled>
                            </div>

                            <button type="button" id="btn-cancel-status" class="btn-cancel-input" onclick="cancelInput('status')" title="Batal">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>

                    </div>

                    <div class="right-column">

                        <div class="form-group mb-4 input-group-custom" id="group-merek">
                            <label class="form-label">Merek Barang</label>

                            <select name="nama_merek_barang" id="select-merek" class="form-select" onchange="toggleInput('merek')" required>
                                <option value="">-- Pilih Merek --</option>
                                <?php foreach ($data['nama_merek_barang'] as $opt) : ?>
                                    <option value="<?= $opt['id_merek_barang'] ?>"
                                        <?= ($isEdit && $barang['id_merek_barang'] == $opt['id_merek_barang']) ? 'selected' : '' ?>>
                                        <?= $opt['nama_merek_barang'] ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="NEW" style="font-weight:bold; color:blue;">+ Tambah Merek Baru</option>
                            </select>

                            <div id="input-container-merek" style="display:none; width: 100%;">
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" name="nama_merek_baru" id="input-merek" class="form-input"
                                        placeholder="Nama Merek (Ex: Lenovo)" style="flex: 2;" disabled>
                                    <input type="text" name="kode_merek_baru" id="input-kode-merek" class="form-input"
                                        placeholder="Kode (Ex: 009)" style="flex: 1;" maxlength="3" disabled title="Kode Merek">
                                </div>
                            </div>

                            <button type="button" id="btn-cancel-merek" class="btn-cancel-input" onclick="cancelInput('merek')" title="Batal">
                                <i class="fa-solid fa-times"></i>
                            </button>
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

                        <div class="form-group mb-4 input-group-custom" id="group-satuan">
                            <label class="form-label">Satuan</label>
                            <select name="satuan" id="select-satuan" class="form-select" onchange="toggleInput('satuan')" required>
                                <option value="">-- Pilih Satuan --</option>
                                <?php foreach ($data['satuan'] as $opt) : ?>
                                    <option value="<?= $opt['id_satuan'] ?>"
                                        <?= ($isEdit && $barang['id_satuan'] == $opt['id_satuan']) ? 'selected' : '' ?>>
                                        <?= $opt['nama_satuan'] ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="NEW" style="font-weight:bold; color:blue;">+ Tambah Satuan Baru</option>
                            </select>

                            <div id="input-container-satuan" style="display:none; width: 100%;">
                                <input type="text" name="satuan_baru" id="input-satuan" class="form-input" placeholder="Satuan Baru..." disabled>
                            </div>

                            <button type="button" id="btn-cancel-satuan" class="btn-cancel-input" onclick="cancelInput('satuan')" title="Batal">
                                <i class="fa-solid fa-times"></i>
                            </button>
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
                            <input type="text" name="deskripsi_detail_lokasi" class="form-input" placeholder="Contoh: Rak 2..."
                                value="<?= $isEdit ? $barang['deskripsi_detail_lokasi'] : '' ?>">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Upload Foto <?= $isEdit ? '(Biarkan kosong jika tidak diganti)' : '' ?></label>
                            <input type="file" name="foto_barang" class="form-input" accept="image/*">
                            <?php if ($isEdit && !empty($barang['foto_barang'])): ?>
                                <small style="display:block; margin-top:5px; color:#666;">File saat ini: <?= basename($barang['foto_barang']) ?></small>
                            <?php endif; ?>
                        </div>

                    </div>

                </div>

                <?php if (!$isEdit): ?>
                    <div style="margin-top: 20px; border-top: 1px dashed #ddd; padding-top: 20px;">
                        <p style="font-size: 13px; font-weight: 600; color: #888; margin-bottom: 15px;">Data Penomoran (Untuk Generate Kode Barang)</p>
                        <div class="form-grid" style="grid-template-columns: 1fr 1fr 1fr;">
                            <div class="form-group">
                                <label class="form-label" style="font-size: 13px;">Barang Ke-</label>
                                <input type="number" name="barang_ke" class="form-input" placeholder="Contoh: 1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="font-size: 13px;">Total Barang</label>
                                <input type="number" name="total_barang" class="form-input" placeholder="Contoh: 10" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="font-size: 13px;">Status Peminjaman</label>
                                <select name="status_pinjam" class="form-select">
                                    <option value="Bisa">Bisa Dipinjam</option>
                                    <option value="Tidak Bisa">Tidak Bisa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="margin-top: 20px; border-top: 1px dashed #ddd; padding-top: 20px;">
                        <div class="form-group" style="max-width: 300px;">
                            <label class="form-label">Status Peminjaman</label>
                            <select name="status_pinjam" class="form-select">
                                <option value="Bisa" <?= ($barang['status_peminjaman'] == 'Bisa') ? 'selected' : '' ?>>Bisa Dipinjam</option>
                                <option value="Tidak Bisa" <?= ($barang['status_peminjaman'] == 'Tidak Bisa') ? 'selected' : '' ?>>Tidak Bisa</option>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="btn-submit-container">
                    <button type="submit" class="btn-submit"><?= $isEdit ? 'Simpan Perubahan' : 'Kirim Data' ?></button>
                </div>

            </form>

        </div>
    </div>
</div>
