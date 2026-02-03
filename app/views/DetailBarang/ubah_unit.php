<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}

// Ambil data unit yang dikirim dari Controller
$unit = $data['unit'];
$title = "Ubah Data Unit Barang";

// Format kode unit untuk tampilan (misal: 2026/01/C/LP1/401/30/5)
$kodeLengkap = $unit['kode_barang'] . '/' . $unit['urutan_unit'] . '/' . $unit['jumlah_total'];
?>

<div class="content">
    <div class="container-fluid p-4">

        <div class="form-card">
            <a href="<?= BASEURL; ?>DetailBarang/detail/<?= IdObfuscator::encode($unit['id_spesifikasi']); ?>"
                class="btn-close-absolute">
                <i class="fa-solid fa-times"></i>
            </a>

            <h2 class="form-title">
                <?= $title; ?>
                <span style="font-size: 0.6em; color: #888; font-weight: normal; margin-left: 10px;">
                    (Unit ke-<?= $unit['urutan_unit']; ?>)
                </span>
            </h2>

            <form action="<?= BASEURL; ?>DetailBarang/prosesUbahUnit" method="post">

                <input type="hidden" name="id_barang" value="<?= $unit['id_barang']; ?>">
                <input type="hidden" name="id_spesifikasi" value="<?= $unit['id_spesifikasi']; ?>">

                <div class="row g-4">

                    <div class="col-12 col-lg-6">

                        <div class="form-group mb-4">
                            <label class="form-label">Kode Unit (Permanen)</label>
                            <input type="text" class="form-input" value="<?= $kodeLengkap; ?>" disabled
                                style="background-color: #f0f2f5; color: #1250ba; font-family: monospace; font-weight: bold;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Jenis & Merek</label>
                            <input type="text" class="form-input"
                                value="<?= $unit['sub_barang']; ?> - <?= $unit['nama_merek_barang']; ?>" disabled
                                style="background-color: #f0f2f5;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Kondisi Barang</label>
                            <select name="id_kondisi_barang" class="form-select" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <?php foreach ($data['kondisi'] as $opt): ?>
                                    <option value="<?= $opt['id_kondisi_barang'] ?>"
                                        <?= ($unit['id_kondisi_barang'] == $opt['id_kondisi_barang']) ? 'selected' : '' ?>>
                                        <?= $opt['kondisi_barang'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Status Barang</label>
                            <select name="id_status" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                <?php foreach ($data['status'] as $opt): ?>
                                    <option value="<?= $opt['id_status'] ?>" <?= ($unit['id_status'] == $opt['id_status']) ? 'selected' : '' ?>>
                                        <?= $opt['status'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <div class="col-12 col-lg-6">

                        <div class="form-group mb-4">
                            <label class="form-label">Lokasi Penyimpanan</label>
                            <select name="id_lokasi_penyimpanan" class="form-select" required>
                                <option value="">-- Pilih Lokasi --</option>
                                <?php foreach ($data['lokasi'] as $opt): ?>
                                    <option value="<?= $opt['id_lokasi_penyimpanan'] ?>"
                                        <?= ($unit['id_lokasi_penyimpanan'] == $opt['id_lokasi_penyimpanan']) ? 'selected' : '' ?>>
                                        <?= $opt['nama_lokasi_penyimpanan'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Detail Letak (Rak/Lemari)</label>
                            <input type="text" name="deskripsi_detail_lokasi" class="form-input"
                                value="<?= $unit['deskripsi_detail_lokasi']; ?>" placeholder="Contoh: Rak 2, Baris 1">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Keterangan Label</label>
                            <select name="keterangan_label" class="form-select">
                                <option value="Sudah" <?= ($unit['keterangan_label'] == 'Sudah') ? 'selected' : '' ?>>Sudah
                                    Dilabel</option>
                                <option value="Belum" <?= ($unit['keterangan_label'] == 'Belum') ? 'selected' : '' ?>>Belum
                                    Dilabel</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Status Peminjaman</label>
                            <select name="status_peminjaman" class="form-select">
                                <option value="Bisa" <?= ($unit['status_peminjaman'] == 'Bisa') ? 'selected' : '' ?>>Bisa
                                    Dipinjam</option>
                                <option value="Tidak Bisa" <?= ($unit['status_peminjaman'] == 'Tidak Bisa') ? 'selected' : '' ?>>Tidak Bisa</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="btn-submit-container" style="margin-top: 20px; text-align: right;">
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-save me-2"></i>
                        Simpan Perubahan Unit
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    .form-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        padding: 40px;
        position: relative;
        max-width: 1000px;
        margin: 0 auto;
    }

    .btn-close-absolute {
        position: absolute;
        top: 25px;
        right: 25px;
        color: #aaa;
        font-size: 24px;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-close-absolute:hover {
        color: #dc3545;
        transform: rotate(90deg);
    }

    .form-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 30px;
        border-left: 5px solid #1250ba;
        padding-left: 15px;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        color: #333;
        transition: 0.3s;
        background: #fff;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: #1250ba;
        box-shadow: 0 0 0 3px rgba(18, 80, 186, 0.1);
        outline: none;
    }

    .btn-submit {
        background: linear-gradient(135deg, #1250ba 0%, #0d3c94 100%);
        color: #fff;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 4px 10px rgba(18, 80, 186, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(18, 80, 186, 0.4);
    }
</style>