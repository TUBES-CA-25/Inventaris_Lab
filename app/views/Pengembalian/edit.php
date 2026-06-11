<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>


<div class="content">
    <div class="content-beranda">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold" style="color: #0d1b3e;">
                Verifikasi Pengembalian
            </h3>
            <a href="<?= BASEURL; ?>Pengembalian" class="btn-back btn-sm rounded-pill px-4">
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

                                                <div class="item-code mb-2" >
                                                    
                                                    <?php
                                                    if (!empty($item['urutan_unit'])) {
                                                        echo htmlspecialchars($item['kode_barang'] . '/' . $item['urutan_unit'] . '/' . $item['jumlah_total']);
                                                    } else {
                                                        echo htmlspecialchars($item['kode_barang']) . ' <span class="text-muted fw-normal" style="font-size: 0.8em;">(Unit Belum Ditentukan)</span>';
                                                    }
                                                    ?>
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
                                    <div class="badge btn-send text-white px-3 py-2 w-100">
                                        <?= date('d/m/Y', strtotime($data['peminjaman']['tanggal_peminjaman'])); ?>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted fw-bold mb-1">
                                        Jatuh Tempo
                                    </label>
                                    <div class="badge btn-back px-3 py-2 w-100" >
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
    document.getElementById('buktiInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Peringatan', 'Ukuran file terlalu besar! Maksimal 5MB', 'warning');
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