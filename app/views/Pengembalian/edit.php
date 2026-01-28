<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<style>
    /* Modern Item Card with Glassmorphism */
    .item-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 1px solid rgba(13, 27, 62, 0.08);
        border-radius: 16px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .item-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #0d1b3e 0%, #3498db 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .item-card:hover {
        border-color: rgba(13, 27, 62, 0.2);
        box-shadow: 0 8px 30px rgba(13, 27, 62, 0.12);
        transform: translateY(-4px) scale(1.01);
    }

    .item-card:hover::before {
        opacity: 1;
    }

    /* Modern Badge */
    .qty-badge {
        background: linear-gradient(135deg, #0d1b3e 0%, #1e3a5f 100%);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(13, 27, 62, 0.2);
        letter-spacing: 0.3px;
    }

    /* Item Info Section */
    .item-info {
        flex: 1;
    }

    .item-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .item-code {
        display: inline-flex;
        align-items: center;
        background: #f7fafc;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        color: #4a5568;
        border: 1px solid #e2e8f0;
        font-family: 'Courier New', monospace;
        font-weight: 600;
    }

    .item-spec {
        background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
        padding: 6px 12px;
        border-radius: 8px;
        margin-top: 8px;
        border-left: 3px solid #3b82f6;
    }

    /* Modern Condition Panel */
    .condition-panel {
        background: #ffffff;
        border: 2px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
        transition: all 0.3s ease;
    }

    .condition-panel:hover {
        border-color: #0d1b3e;
        box-shadow: 0 4px 20px rgba(13, 27, 62, 0.08);
    }

    .form-label-sm {
        font-size: 0.8rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Modern Select Dropdown */
    .modern-select {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .modern-select:focus {
        border-color: #0d1b3e;
        box-shadow: 0 0 0 3px rgba(13, 27, 62, 0.1);
        outline: none;
    }

    .modern-input {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 14px;
        transition: all 0.3s ease;
    }

    .modern-input:focus {
        border-color: #0d1b3e;
        box-shadow: 0 0 0 3px rgba(13, 27, 62, 0.1);
        outline: none;
    }

    /* Section Header */
    .section-header {
        background: linear-gradient(135deg, #0d1b3e 0%, #1e3a5f 100%);
        color: white;
        padding: 16px 24px;
        border-radius: 14px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(13, 27, 62, 0.2);
    }

    /* Enhanced Cards */
    .info-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .info-card .card-header {
        background: linear-gradient(135deg, #0d1b3e 0%, #1e3a5f 100%);
        border-bottom: 2px solid #e5e7eb;
        padding: 18px 24px;

    }

    .finalize-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 8px 30px rgba(13, 27, 62, 0.12);
        border-top: 4px solid #0d1b3e;
    }

    /* Modern Button */
    .btn-modern-primary {
        background: linear-gradient(135deg, #0d1b3e 0%, #1e3a5f 100%);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(13, 27, 62, 0.3);
    }

    .btn-modern-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(13, 27, 62, 0.4);
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .item-card {
        animation: fadeInUp 0.5s ease backwards;
    }

    .item-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .item-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .item-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .item-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .item-card:nth-child(5) {
        animation-delay: 0.5s;
    }

    /* Preview Container */
    #previewContainer img {
        border-radius: 12px;
        border: 3px solid #e5e7eb;
    }
</style>

<div class="content">
    <div class="content-beranda">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold" style="color: #0d1b3e;">
                Verifikasi Pengembalian
            </h3>
            <a href="<?= BASEURL; ?>Pengembalian" class="btn btn-secondary btn-sm rounded-pill px-4">
                Kembali
            </a>
        </div>

        <form action="<?= BASEURL; ?>Pengembalian/proses_edit" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="id_peminjaman" value="<?= $data['peminjaman']['id_peminjaman']; ?>">

            <div class="row g-4">

                <div class="col-lg-8">
                    <div class="section-header">
                        <h5 class="fw-bold mb-0">
                            Daftar Barang & Verifikasi Kondisi
                        </h5>
                    </div>

                    <?php if (!empty($data['items'])): ?>
                        <?php foreach ($data['items'] as $index => $item):
                            $kondisi_sebelumnya = $item['kondisi_existing'] ?? 'Baik';
                            $ket_sebelumnya = $item['ket_existing'] ?? '';
                        ?>

                            <div class="item-card p-4 mb-3">
                                <div class="row g-4 align-items-center">
                                    <div class="col-lg-6">
                                        <div class="d-flex align-items-start">
                                            <div class="item-info">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                                    <h6 class="item-title mb-0">
                                                        <?= htmlspecialchars($item['nama_barang']); ?>
                                                    </h6>
                                                    <span class="qty-badge badge text-white">
                                                        <?= $item['jumlah']; ?> Unit
                                                    </span>
                                                </div>

                                                <div class="item-code mb-2">
                                                    <?= htmlspecialchars($item['kode_barang']); ?>
                                                </div>

                                                <?php if (!empty($item['spesifikasi_barang']) && $item['spesifikasi_barang'] != '-'): ?>
                                                    <div class="item-spec">
                                                        <div class="d-flex align-items-start">
                                                            <small class="text-dark fw-medium">
                                                                <?= htmlspecialchars($item['spesifikasi_barang']); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="condition-panel">
                                            <div class="mb-3">
                                                <label class="form-label-sm">
                                                    Kondisi Pengembalian
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="kondisi[<?= $item['id_detail']; ?>]"
                                                    class="form-select modern-select" required>
                                                    <option value="Baik" <?= ($kondisi_sebelumnya == 'Baik') ? 'selected' : ''; ?>>
                                                        Baik / Normal
                                                    </option>
                                                    <option value="Rusak" <?= ($kondisi_sebelumnya == 'Rusak') ? 'selected' : ''; ?>>
                                                        Rusak / Perlu Perbaikan
                                                    </option>
                                                    <option value="Hilang" <?= ($kondisi_sebelumnya == 'Hilang') ? 'selected' : ''; ?>>
                                                        Hilang / Tidak Kembali
                                                    </option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="form-label-sm">
                                                    Catatan Kondisi
                                                </label>
                                                <input type="text"
                                                    name="ket_item[<?= $item['id_detail']; ?>]"
                                                    class="form-control modern-input"
                                                    placeholder="Deskripsikan kerusakan atau masalah..."
                                                    value="<?= htmlspecialchars($ket_sebelumnya); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning rounded-3 border-0 shadow-sm">
                            Tidak ada data barang yang ditemukan untuk peminjaman ini.
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4">

                    <div class="card info-card mb-4">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0 text-white">
                                Informasi Peminjam
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="small text-muted fw-bold mb-1">
                                    Nama Peminjam
                                </label>
                                <div class="fw-bold text-dark fs-6">
                                    <?= htmlspecialchars($data['peminjaman']['nama_peminjam']); ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted fw-bold mb-1">
                                    Kegiatan
                                </label>
                                <div class="text-dark">
                                    <?= htmlspecialchars($data['peminjaman']['judul_kegiatan']); ?>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="small text-muted fw-bold mb-1">
                                        Tgl Pinjam
                                    </label>
                                    <div class="badge bg-primary text-white px-3 py-2 w-100">
                                        <?= date('d/m/Y', strtotime($data['peminjaman']['tanggal_peminjaman'])); ?>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted fw-bold mb-1">
                                        Jatuh Tempo
                                    </label>
                                    <div class="badge bg-danger text-white px-3 py-2 w-100">
                                        <?= date('d/m/Y', strtotime($data['peminjaman']['tanggal_pengembalian'])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card info-card mb-4 ">
                        <div class="card-header ">
                            <h6 class="fw-bold mb-0 text-white">
                                Informasi Peminjam
                            </h6>
                        </div>
                        <div class="p-3">
                            <div class="mb-3">
                                <label class="form-label-sm">
                                    Petugas Pemeriksa
                                </label>
                                <input type="text"
                                    class="form-control modern-input bg-light"
                                    value="<?= $data['profile']['nama_user']; ?>"
                                    readonly>
                                <input type="hidden" name="id_petugas" value="<?= $_SESSION['id_user']; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label-sm">
                                    Tanggal Kembali Aktual
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                    name="tgl_pengembalian_aktual"
                                    class="form-control modern-input"
                                    value="<?= !empty($data['peminjaman']['tgl_pengembalian_aktual']) ? $data['peminjaman']['tgl_pengembalian_aktual'] : date('Y-m-d'); ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-sm">
                                    Status Pemeriksaan
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="status_pengembalian" class="form-select modern-select fw-bold" required>

                                    <option value="Selesai Periksa"
                                        <?= (!empty($data['peminjaman']['status_pengembalian']) && $data['peminjaman']['status_pengembalian'] == 'Selesai Periksa') ? 'selected' : ''; ?>>
                                        Selesai Periksa
                                    </option>

                                    <option value="Periksa"
                                        <?= (!empty($data['peminjaman']['status_pengembalian']) && $data['peminjaman']['status_pengembalian'] == 'Periksa') ? 'selected' : ''; ?>>
                                        Periksa
                                    </option>

                                    <option value="Periksa Ulang"
                                        <?= (!empty($data['peminjaman']['status_pengembalian']) && $data['peminjaman']['status_pengembalian'] == 'Periksa Ulang') ? 'selected' : ''; ?>>
                                        Periksa Ulang
                                    </option>

                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-sm">
                                    Bukti Foto
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="file"
                                    name="bukti_foto"
                                    class="form-control modern-input"
                                    accept="image/*"
                                    id="buktiInput"
                                    required>

                                <div class="form-text text-muted small">
                                    Wajib melampirkan foto bukti barang.
                                </div>

                                <div id="previewContainer" class="mt-3 text-center" style="display: none;">
                                    <img id="previewImage" class="img-fluid" style="max-height: 150px;">
                                    <button type="button"
                                        class="btn btn-sm btn-danger mt-2 w-100 rounded-pill"
                                        onclick="removePreview()">
                                        Hapus Preview
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-sm">
                                    Catatan Umum
                                </label>
                                <textarea name="detail_masalah"
                                    class="form-control modern-input"
                                    rows="3"
                                    placeholder="Tambahkan informasi atau catatan penting..."><?= htmlspecialchars($data['peminjaman']['detail_masalah'] ?? ''); ?></textarea>
                            </div>

                            <hr class="my-4">

                            <button type="submit" class="btn btn-modern-primary w-100 text-white">
                                Simpan & Verifikasi Data
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview Image with modern UX
    document.getElementById('buktiInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('previewContainer').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    function removePreview() {
        document.getElementById('buktiInput').value = '';
        document.getElementById('previewContainer').style.display = 'none';
    }

    // Form Validation with modern feedback
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>