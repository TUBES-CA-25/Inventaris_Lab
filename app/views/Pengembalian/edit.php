<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="content">
    <div class="content-beranda">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold" style="color: #0d1b3e;">Edit Status Pengembalian</h3>
            <a href="<?= BASEURL; ?>Pengembalian" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Form Edit Status -->
        <form action="<?= BASEURL; ?>Pengembalian/proses_edit" method="POST" enctype="multipart/form-data"
            class="needs-validation" novalidate>
            <input type="hidden" name="id_peminjaman" value="<?= $data['peminjaman']['id_peminjaman']; ?>">

            <!-- Informasi Peminjaman -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4"
                        style="color: #0d1b3e; border-bottom: 2px solid #0d1b3e; padding-bottom: 10px;">
                        <i class="fas fa-info-circle"></i> Informasi Peminjaman
                    </h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Judul Kegiatan</label>
                            <input type="text" class="form-control bg-light"
                                value="<?= htmlspecialchars($data['peminjaman']['judul_kegiatan'] ?? '-'); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Peminjam</label>
                            <input type="text" class="form-control bg-light"
                                value="<?= htmlspecialchars($data['peminjaman']['nama_peminjam'] ?? '-'); ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tanggal Peminjaman</label>
                            <input type="text" class="form-control bg-light"
                                value="<?= !empty($data['peminjaman']['tanggal_peminjaman']) ? date('d/m/Y', strtotime($data['peminjaman']['tanggal_peminjaman'])) : '-'; ?>"
                                readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tanggal Jatuh Tempo</label>
                            <input type="text" class="form-control bg-light"
                                value="<?= !empty($data['peminjaman']['tanggal_pengembalian']) ? date('d/m/Y', strtotime($data['peminjaman']['tanggal_pengembalian'])) : '-'; ?>"
                                readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status Peminjaman</label>
                            <input type="text" class="form-control bg-light"
                                value="<?= htmlspecialchars($data['peminjaman']['status'] ?? '-'); ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Status Pengembalian -->
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4"
                        style="color: #0d1b3e; border-bottom: 2px solid #0d1b3e; padding-bottom: 10px;">
                        <i class="fas fa-edit"></i> Status Pengembalian
                    </h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status Barang <span class="text-danger">*</span></label>
                            <select name="status_pengembalian" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Dikembalikan" <?= (!empty($data['peminjaman']['status_pengembalian']) && $data['peminjaman']['status_pengembalian'] == 'Dikembalikan') ? 'selected' : ''; ?>>
                                    ✅ Dikembalikan (Barang sudah kembali)
                                </option>
                                <option value="Belum Dikembalikan"
                                    <?= (!empty($data['peminjaman']['status_pengembalian']) && $data['peminjaman']['status_pengembalian'] == 'Belum Dikembalikan') ? 'selected' : ''; ?>>
                                    ⏳ Belum Dikembalikan (Masih dipinjam)
                                </option>
                                <option value="Rusak" <?= (!empty($data['peminjaman']['status_pengembalian']) && $data['peminjaman']['status_pengembalian'] == 'Rusak') ? 'selected' : ''; ?>>
                                    🔧 Rusak
                                </option>
                                <option value="Hilang" <?= (!empty($data['peminjaman']['status_pengembalian']) && $data['peminjaman']['status_pengembalian'] == 'Hilang') ? 'selected' : ''; ?>>
                                    ❌ Hilang
                                </option>
                            </select>
                            <div class="invalid-feedback">Pilih status barang</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Keterangan Waktu <span
                                    class="text-danger">*</span></label>
                            <select name="keterangan" class="form-select" required>
                                <option value="">-- Pilih Keterangan --</option>
                                <option value="Tepat Waktu" <?= (!empty($data['peminjaman']['keterangan_pengembalian']) && $data['peminjaman']['keterangan_pengembalian'] == 'Tepat Waktu') ? 'selected' : ''; ?>>
                                    ⏰ Tepat Waktu
                                </option>
                                <option value="Tidak Tepat Waktu"
                                    <?= (!empty($data['peminjaman']['keterangan_pengembalian']) && $data['peminjaman']['keterangan_pengembalian'] == 'Tidak Tepat Waktu') ? 'selected' : ''; ?>>
                                    ⚠️ Terlambat
                                </option>
                                <option value="Bermasalah" <?= (!empty($data['peminjaman']['keterangan_pengembalian']) && $data['peminjaman']['keterangan_pengembalian'] == 'Bermasalah') ? 'selected' : ''; ?>>
                                    🚨 Bermasalah
                                </option>
                            </select>
                            <div class="invalid-feedback">Pilih keterangan waktu</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Pengembalian Aktual</label>
                            <input type="date" name="tgl_pengembalian_aktual" class="form-control"
                                value="<?= !empty($data['peminjaman']['tgl_pengembalian_aktual']) ? $data['peminjaman']['tgl_pengembalian_aktual'] : date('Y-m-d'); ?>">
                            <small class="text-muted">Kosongkan untuk auto-set tanggal hari ini</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Bukti Foto Pengembalian</label>
                            <input type="file" name="bukti_foto" class="form-control" accept="image/*" id="buktiInput">
                            <small class="text-muted">Format: JPG, JPEG, PNG (Max 5MB)</small>

                            <?php if (!empty($data['peminjaman']['bukti_foto'])): ?>
                                <div class="mt-2">
                                    <small class="text-success"><i class="fas fa-check-circle"></i> Bukti foto sudah
                                        ada</small>
                                    <a href="<?= BASEURL . $data['peminjaman']['bukti_foto']; ?>" target="_blank"
                                        class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Detail Masalah (Opsional)</label>
                            <textarea name="detail_masalah" class="form-control" rows="4"
                                placeholder="Tuliskan detail jika ada kerusakan, kehilangan, atau masalah lainnya..."><?= htmlspecialchars($data['peminjaman']['detail_masalah'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- Preview Image -->
                    <div class="row mb-3" id="previewContainer" style="display: none;">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Preview Foto:</label>
                            <div class="position-relative" style="max-width: 400px;">
                                <img id="previewImage" class="img-fluid rounded shadow" alt="Preview">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                    onclick="removePreview()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= BASEURL; ?>Pengembalian" class="btn btn-light px-4">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn px-4" style="background-color: #0d1b3e; color: white;">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .form-label {
        color: #0d1b3e;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d1b3e;
        box-shadow: 0 0 0 0.2rem rgba(13, 27, 62, 0.25);
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    #previewImage {
        max-height: 300px;
        object-fit: cover;
    }
</style>

<script>
    // Form validation
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Image preview
    document.getElementById('buktiInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            // Validasi ukuran (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                this.value = '';
                return;
            }

            // Validasi tipe
            if (!file.type.match('image.*')) {
                alert('Hanya file gambar yang diperbolehkan!');
                this.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function (e) {
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
</script>