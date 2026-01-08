<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<style>
    :root {
        --primary-navy: #0c1740;
        --secondary-navy: #1a2b6b;
        --soft-gray: #f4f6f9;
        --success-green: #28a745;
        --danger-red: #dc3545;
    }

    body {
        background-color: var(--soft-gray);
    }

    /* Header Section */
    .page-header {
        background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(12, 23, 64, 0.15);
    }

    .page-header h3 {
        margin-bottom: 8px;
        font-weight: 700;
    }

    .page-header p {
        opacity: 0.9;
        margin-bottom: 0;
    }

    /* Step Card */
    .step-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }

    .step-card:hover {
        box-shadow: 0 5px 25px rgba(0,0,0,0.12);
        transform: translateY(-3px);
    }

    /* Step Header */
    .step-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 25px;
        display: flex;
        align-items: center;
        color: white;
    }

    .step-number-circle {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        margin-right: 15px;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .step-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    /* Step Body */
    .step-body {
        padding: 30px;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .info-box {
        background: #f8f9fc;
        border: 2px solid #e3e6f0;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .info-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-navy);
        transition: width 0.3s ease;
    }

    .info-box:hover {
        border-color: var(--primary-navy);
        transform: translateX(5px);
    }

    .info-box:hover::before {
        width: 8px;
    }

    .info-box-header {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }

    .info-icon-circle {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-navy), var(--secondary-navy));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        margin-right: 12px;
    }

    .info-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.5;
    }

    .info-value .badge {
        font-size: 11px;
        padding: 5px 12px;
        vertical-align: middle;
    }

    /* Download Section */
    .download-section {
        background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
        border: 2px dashed var(--danger-red);
        border-radius: 15px;
        padding: 30px;
        text-align: center;
    }

    .download-icon-wrapper {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2);
    }

    .download-icon-wrapper i {
        font-size: 35px;
        color: var(--danger-red);
    }

    .btn-download {
        background: linear-gradient(135deg, var(--danger-red), #c82333);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 15px 40px;
        font-size: 16px;
        font-weight: 700;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-download:hover {
        background: linear-gradient(135deg, #c82333, #bd2130);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
    }

    .btn-download i {
        margin-right: 10px;
        font-size: 18px;
    }

    /* Upload Section */
    .upload-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 3px dashed #3b82f6;
        border-radius: 15px;
        padding: 50px 30px;
        text-align: center;
        position: relative;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .upload-section:hover,
    .upload-section.dragover {
        border-color: var(--primary-navy);
        background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
        transform: scale(1.02);
    }

    .upload-icon-wrapper {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 5px 20px rgba(59, 130, 246, 0.2);
        transition: all 0.3s ease;
    }

    .upload-section:hover .upload-icon-wrapper {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    }

    .upload-icon-wrapper i {
        font-size: 45px;
        color: #3b82f6;
        transition: all 0.3s ease;
    }

    .upload-section:hover .upload-icon-wrapper i {
        color: var(--primary-navy);
    }

    .file-input-hidden {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .upload-title {
        font-size: 20px;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 10px;
    }

    .upload-subtitle {
        color: #64748b;
        margin-bottom: 20px;
    }

    .file-types {
        display: inline-flex;
        gap: 10px;
        margin-top: 15px;
    }

    .file-type-badge {
        background: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        border: 2px solid #e2e8f0;
    }

    /* Submit Button */
    .btn-submit {
        background: linear-gradient(135deg, var(--success-green), #218838);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 18px 50px;
        font-size: 18px;
        font-weight: 700;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        transition: all 0.3s ease;
        margin-top: 25px;
        width: 100%;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Alert Box */
    .alert-custom {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .alert-custom i {
        font-size: 24px;
        color: #ffc107;
        margin-right: 15px;
    }

    .alert-custom-content {
        flex: 1;
    }

    .alert-custom-content strong {
        display: block;
        margin-bottom: 5px;
        color: #856404;
    }

    .alert-custom-content p {
        margin: 0;
        color: #856404;
        font-size: 14px;
    }

    /* Back Button */
    .btn-back {
        background: white;
        color: var(--primary-navy);
        border: 2px solid var(--primary-navy);
        border-radius: 25px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: var(--primary-navy);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(12, 23, 64, 0.3);
    }

    /* Success State */
    .upload-section.success {
        border-color: var(--success-green);
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }

    .upload-section.success .upload-icon-wrapper {
        background: var(--success-green);
        box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
    }

    .upload-section.success .upload-icon-wrapper i {
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 20px;
        }

        .step-body {
            padding: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .download-section,
        .upload-section {
            padding: 30px 20px;
        }
    }
</style>

<div class="content">
    <div class="container-fluid p-4">

        <!-- Page Header -->
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

        <!-- Flash Message -->
        <div class="row">
            <div class="col-12">
                <?php Flasher::flash(); ?>
            </div>
        </div>

        <!-- STEP 1: DOWNLOAD -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number-circle">1</div>
                <div>
                    <h5 class="step-title">Review Data & Download Surat</h5>
                    <small style="opacity: 0.9;">Periksa data peminjaman dan unduh surat yang perlu ditandatangani</small>
                </div>
            </div>
            
            <div class="step-body">
                <!-- Info Grid -->
                <div class="info-grid">
                    <!-- Nama Peminjam -->
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

                    <!-- Kegiatan -->
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

                    <!-- Barang -->
                    <div class="info-box">
                        <div class="info-box-header">
                            <div class="info-icon-circle">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <div class="info-label">Barang Dipinjam</div>
                            </div>
                        </div>
                        <div class="info-value">
                            <?= isset($data['peminjaman']['nama_barang']) ? $data['peminjaman']['nama_barang'] : 'Tidak ditemukan'; ?>
                            <span class="badge badge-primary ml-2" style="background-color: var(--primary-navy);">
                                <?= isset($data['peminjaman']['jumlah_peminjaman']) ? $data['peminjaman']['jumlah_peminjaman'] : 0; ?> Unit
                            </span>
                        </div>
                    </div>

                    <!-- Tanggal -->
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
                </div>

                <!-- Download Section -->
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

        <!-- STEP 2: UPLOAD -->
        <div class="step-card">
            <div class="step-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="step-number-circle">2</div>
                <div>
                    <h5 class="step-title">Upload Surat Bertanda Tangan</h5>
                    <small style="opacity: 0.9;">Setelah ditandatangani, scan atau foto lalu upload di sini</small>
                </div>
            </div>
            
            <div class="step-body">
                <!-- Alert Warning -->
                <div class="alert-custom">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="alert-custom-content">
                        <strong>Perhatian!</strong>
                        <p>Pastikan surat sudah ditandatangani oleh pihak yang berwenang sebelum diupload</p>
                    </div>
                </div>

                <!-- Upload Form -->
                <form action="<?= BASEURL; ?>TemplateSurat/prosesUpload" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_peminjaman" value="<?= $data['peminjaman']['id_peminjaman']; ?>">
                    
                    <!-- Upload Zone -->
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

                    <!-- Submit Button -->
                    <button type="submit" name="submit_upload" class="btn-submit" id="btn-submit">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Berkas Peminjaman
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    // File Upload Interactions
    const fileInput = document.getElementById('file_surat');
    const fileLabel = document.getElementById('file-label');
    const dropZone = document.getElementById('drop-zone');
    const btnSubmit = document.getElementById('btn-submit');

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files.length > 0) {
            const file = this.files[0];
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
            
            // Update UI
            fileLabel.innerHTML = `
                <i class="fas fa-check-circle mr-2" style="color: var(--success-green);"></i>
                ${fileName}
            `;
            
            dropZone.classList.add('success');
            
            // Show file info
            const subtitle = dropZone.querySelector('.upload-subtitle');
            subtitle.innerHTML = `
                <i class="fas fa-file-alt mr-2"></i>
                Ukuran: ${fileSize} MB
            `;
            
            // Change icon to check
            const icon = dropZone.querySelector('.upload-icon-wrapper i');
            icon.className = 'fas fa-check-circle';
            
            // Enable submit button
            btnSubmit.disabled = false;
        }
    });

    // Drag and drop effects
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('dragover');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('dragover');
        }, false);
    });

    // Handle dropped files
    dropZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    });
</script>