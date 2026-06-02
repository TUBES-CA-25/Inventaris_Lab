<?php $data['title'] = '503 - Layanan Tidak Tersedia';
include 'header.php'; ?>
<link rel="stylesheet" href="<?= BASEURL; ?>css/error.css?v=<?= time(); ?>">

<!-- Error Icon -->
<div class="error-animation">
    <i class="fas fa-tools" style="font-size: 150px; color: #f59e0b;"></i>
</div>

<!-- Error Code -->
<div class="error-code">503</div>

<!-- Error Title -->
<h1 class="error-title">Layanan Tidak Tersedia</h1>

<!-- Error Message -->
<p class="error-message">
    Sistem sedang dalam masa pemeliharaan atau terlalu sibuk. Mohon kembali lagi beberapa saat lagi.
</p>

<!-- Action Buttons -->
<div class="error-actions">
    <a href="<?= BASEURL; ?>Beranda" class="btn-error btn-error-primary">
        <i class="fas fa-home"></i>
        Kembali ke Beranda
    </a>
    <a href="javascript:history.back()" class="btn-error btn-error-secondary">
        <i class="fas fa-arrow-left"></i>
        Halaman Sebelumnya
    </a>
</div>

<?php include 'footer.php'; ?>