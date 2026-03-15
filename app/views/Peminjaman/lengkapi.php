<div class="content">
    <div class="container-fluid p-4">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h3>📋 Lengkapi Berkas Peminjaman</h3>
                    <p>Ikuti langkah-langkah di bawah ini untuk melengkapi berkas peminjaman Anda</p>
                </div>
                <div class="col-md-3 text-md-right mt-3 mt-md-0">
                    <a href="<?= BASEURL; ?>Riwayat" class="btn btn-back">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php Flasher::flash(); ?>
            </div>
        </div>

        <div class="step-card1">
            <div class="step-header">
                <div class="step-number-circle">1</div>
                <div>
                    <h5 class="step-title">Review Data & Download Surat</h5>
                    <small class="step-desc">Periksa data peminjaman dan unduh surat yang perlu ditandatangani</small>
                </div>
            </div>

            <div class="step-body">
                <div class="info-grid">
                    <div class="info-box">
                        <div class="info-box-header">
                            <div class="info-icon-circle">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="info-label">Nama Peminjam</div>
                            </div>
                        </div>
                        <div class="info-value">
                            <?= isset($data['peminjaman']['nama_user']) ? $data['peminjaman']['nama_user'] : '-'; ?>
                        </div>
                    </div>

                    <?php if (!empty($data['peminjaman']['kategori_kegiatan'])): ?>
                        <div class="info-box">
                            <div class="info-box-header">
                                <div class="info-icon-circle">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <div>
                                    <div class="info-label">Kategori Kegiatan</div>
                                </div>
                            </div>
                            <div class="info-value">
                                <?= htmlspecialchars($data['peminjaman']['kategori_kegiatan']); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="info-box">
                        <div class="info-box-header">
                            <div class="info-icon-circle">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div>
                                <div class="info-label">Judul Kegiatan</div>
                            </div>
                        </div>
                        <div class="info-value">
                            <?= isset($data['peminjaman']['judul_kegiatan']) ? $data['peminjaman']['judul_kegiatan'] : '-'; ?>
                        </div>
                    </div>


                    <div class="info-box">
                        <div class="info-box-header">
                            <div class="info-icon-circle">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <div class="info-label">Periode Peminjaman</div>
                            </div>
                        </div>
                        <div class="info-value">
                            <?= isset($data['peminjaman']['tanggal_peminjaman']) ? date('d M Y', strtotime($data['peminjaman']['tanggal_peminjaman'])) : '-'; ?>
                            <i class="fas fa-arrow-right mx-2 text-muted date-arrow"></i>
                            <?= isset($data['peminjaman']['tanggal_pengembalian']) ? date('d M Y', strtotime($data['peminjaman']['tanggal_pengembalian'])) : '-'; ?>
                        </div>
                    </div> <?php if (!empty($data['peminjaman']['dosen_pembimbing'])): ?>
                        <div class="info-box">
                            <div class="info-box-header">
                                <div class="info-icon-circle">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <div class="info-label">Dosen Pembimbing</div>
                                </div>
                            </div>
                            <div class="info-value">
                                <?= htmlspecialchars($data['peminjaman']['dosen_pembimbing']); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="info-box">
                        <div class="info-box-header">
                            <div class="info-icon-circle">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                            <div>
                                <div class="info-label">Keterangan / Catatan</div>
                            </div>
                        </div>
                        <div class="info-value">
                            <?= !empty($data['peminjaman']['keterangan_peminjaman']) ? $data['peminjaman']['keterangan_peminjaman'] : '-'; ?>
                        </div>
                    </div>

                    <div class="info-box1 info-box-full">
                        <div class="info-box-header mb-3">
                            <div class="info-icon-circle">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <div class="info-label">Daftar Barang & Spesifikasi</div>
                            </div>
                        </div>

                        <div class="info-value p-0 d-flex flex-column w-100">

                            <?php if (!empty($data['detail_barang'])): ?>
                                <div class="item-list-header">
                                    <div style="width: 30%;">NAMA BARANG</div>
                                    <div style="width: 55%;">SPESIFIKASI</div>
                                    <div style="width: 15%; text-align: right;">JUMLAH</div>
                                </div>

                                <ul class="item-list-ul">
                                    <?php foreach ($data['detail_barang'] as $item): ?>
                                        <li class="item-list-row">
                                            <div class="item-content-wrapper">

                                                <div class="col-item-name">
                                                    <?= $item['nama_barang']; ?>
                                                    <?php if (!empty($item['urutan_unit'])): ?>
                                                        <br><small class="text-primary font-weight-bold">Unit:
                                                            <?= $item['urutan_unit']; ?></small>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="col-item-spec">
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-wrench mt-1 mr-2 icon-spec"></i>
                                                        <span><?= !empty($item['spesifikasi_barang']) ? $item['spesifikasi_barang'] : '-'; ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-item-qty">
                                                    <span class="badge-qty">
                                                        <?= $item['jumlah']; ?> Unit
                                                    </span>
                                                </div>

                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center text-muted font-italic py-4 w-100">
                                    <i class="fas fa-box-open mb-2 empty-state-icon"></i><br>
                                    - Tidak ada data barang -
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="download-section">
                    <div class="download-icon-wrapper">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h5 class="download-title">
                        Unduh Surat Peminjaman
                    </h5>
                    <p class="download-desc">
                        Pastikan semua data di atas sudah benar sebelum mengunduh surat
                    </p>
                    <a href="<?= BASEURL; ?>TemplateSurat/generatePDF/<?= IdObfuscator::encode($data['peminjaman']['id_peminjaman']); ?>"
                        class="btn-download">
                        <i class="fas fa-download mr-2"></i>Download Surat PDF
                    </a>

                    <div class="mt-4">
                        <p class="text-muted small mb-2">- atau -</p>
                        <a href="<?= BASEURL; ?>TemplateSurat/tandaTangan/<?= IdObfuscator::encode($data['peminjaman']['id_peminjaman']); ?>"
                            class="btn-back d-inline-block">
                            <i class="fas fa-signature mr-2"></i>Tanda Tangan Digital
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="step-card1">
            <div class="step-header">
                <div class="step-number-circle">2</div>
                <div>
                    <h5 class="step-title">Upload Surat Bertanda Tangan</h5>
                    <small class="step-desc">Setelah ditandatangani, scan atau foto lalu upload di sini</small>
                </div>
            </div>

            <div class="step-body">
                <div class="alert-custom">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="alert-custom-content">
                        <strong>Perhatian!</strong>
                        <p>Pastikan surat sudah ditandatangani oleh pihak yang berwenang sebelum diupload</p>
                    </div>
                </div>

                <form action="<?= BASEURL; ?>TemplateSurat/prosesUpload" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_peminjaman"
                        value="<?= IdObfuscator::encode($data['peminjaman']['id_peminjaman']); ?>">

                    <input type="file" id="file_surat" name="file_surat" required accept=".pdf"
                        onchange="updateFileName(this)"
                        style="position: absolute; width: 1px; height: 1px; opacity: 0; overflow: hidden; z-index: -1;">

                    <div class="upload-section" id="drop-zone" onclick="triggerUpload()" style="cursor: pointer;">

                        <div id="view-default">
                            <div class="upload-icon-wrapper">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h5 class="upload-title">Klik atau Seret File ke Sini</h5>
                            <p class="upload-subtitle">Pilih file surat yang sudah ditandatangani</p>
                            <div class="file-types">
                                <span class="file-type-badge">📄 PDF</span>
                            </div>
                            <small class="d-block mt-3 text-muted">
                                <i class="fas fa-info-circle mr-1"></i>Ukuran maksimal: 2MB
                            </small>
                        </div>

                        <div id="view-preview" class="upload-preview-wrapper" style="display: none;">
                            <div class="upload-icon-wrapper upload-icon-success">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <h5 class="upload-title upload-title-success">File Siap Dikirim!</h5>
                            <p id="filename-display" class="filename-text">nama_file.pdf</p>

                            <button type="button" class="btn btn-outline-danger btn-sm mt-2"
                                onclick="resetUpload(event)" style="border-radius: 50px;">
                                <i class="fas fa-sync-alt mr-1"></i> Ganti File
                            </button>
                        </div>
                    </div>

                    <button type="submit" name="submit_upload" class="btn-submit mt-3" id="btn-submit" disabled>
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Berkas Peminjaman
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>