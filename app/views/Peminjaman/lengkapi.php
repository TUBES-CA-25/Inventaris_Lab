<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

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

        <div class="step-card">
            <div class="step-header">
                <div class="step-number-circle">1</div>
                <div>
                    <h5 class="step-title">Review Data & Download Surat</h5>
                    <small style="opacity: 0.9;">Periksa data peminjaman dan unduh surat yang perlu ditandatangani</small>
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
                            <?= isset($data['peminjaman']['nama_peminjam']) ? $data['peminjaman']['nama_peminjam'] : '-'; ?>
                        </div>
                    </div>

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
                            <i class="fas fa-arrow-right mx-2 text-muted" style="font-size: 12px;"></i>
                            <?= isset($data['peminjaman']['tanggal_pengembalian']) ? date('d M Y', strtotime($data['peminjaman']['tanggal_pengembalian'])) : '-'; ?>
                        </div>
                    </div>

                    <div class="info-box1">
                        <div class="info-box-header">
                            <div class="info-icon-circle">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <div class="info-label">Barang Dipinjam</div>
                            </div>
                        </div>
                        <div class="info-value">
                            <?php if (!empty($data['detail_barang'])) : ?>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    <?php foreach ($data['detail_barang'] as $item) : ?>
                                        <li style="margin-bottom: 4px;">
                                            • <?= $item['nama_barang']; ?> 
                                            <span style="font-weight: bold; color: var(--primary-navy);">
                                                (<?= $item['jumlah']; ?> Unit)
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <?= isset($data['peminjaman']['nama_barang']) ? $data['peminjaman']['nama_barang'] : '-'; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <div class="download-section">
                    <div class="download-icon-wrapper">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h5 style="color: var(--danger-red); font-weight: 700; margin-bottom: 10px;">
                        Unduh Surat Peminjaman
                    </h5>
                    <p style="color: #6c757d; margin-bottom: 25px;">
                        Pastikan semua data di atas sudah benar sebelum mengunduh surat
                    </p>
                    <a href="<?= BASEURL; ?>TemplateSurat/generatePDF/<?= $data['peminjaman']['id_peminjaman']; ?>" 
                       class="btn-download">
                        <i class="fas fa-download"></i>Download Surat PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="step-card">
            <div class="step-header">
                <div class="step-number-circle">2</div>
                <div>
                    <h5 class="step-title">Upload Surat Bertanda Tangan</h5>
                    <small style="opacity: 0.9;">Setelah ditandatangani, scan atau foto lalu upload di sini</small>
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
                    <input type="hidden" name="id_peminjaman" value="<?= $data['peminjaman']['id_peminjaman']; ?>">
                    
                    <div class="upload-section" id="drop-zone">
                        <input type="file" 
                               class="file-input-hidden" 
                               id="file_surat" 
                               name="file_surat" 
                               required 
                               accept=".pdf,.jpg,.jpeg,.png">
                        
                        <div class="upload-icon-wrapper">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        
                        <h5 class="upload-title" id="file-label">
                            Klik atau Seret File ke Sini
                        </h5>
                        <p class="upload-subtitle">
                            Pilih file surat yang sudah ditandatangani
                        </p>
                        
                        <div class="file-types">
                            <span class="file-type-badge">📄 PDF</span>
                            <span class="file-type-badge">🖼️ JPG</span>
                            <span class="file-type-badge">🖼️ PNG</span>
                        </div>
                        
                        <small class="d-block mt-3 text-muted">
                            <i class="fas fa-info-circle mr-1"></i>Ukuran maksimal: 2MB
                        </small>
                    </div>

                    <button type="submit" name="submit_upload" class="btn-submit" id="btn-submit">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Berkas Peminjaman
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>