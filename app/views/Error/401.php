<?php $data['title'] = '401 - Unauthorized';
include 'header.php'; ?>
<link rel="stylesheet" href="<?= BASEURL; ?>css/error.css?v=<?= time(); ?>">

<!-- Error Icon -->
<div class="error-animation">
    <i class="fas fa-lock" style="font-size: 150px; color: #f59e0b;"></i>
</div>

<!-- Error Code -->
<div class="error-code">401</div>

<!-- Error Title -->
<h1 class="error-title">Sesi Tidak Valid</h1>

<!-- Error Message -->
<p class="error-message">
    Anda harus login terlebih dahulu untuk mengakses halaman ini.
</p>

<!-- Action Buttons -->
<div class="error-actions">
    <a href="<?= BASEURL; ?>Login" class="btn-error btn-error-primary">
        <i class="fas fa-sign-in-alt"></i>
        Login Sekarang
    </a>
    <a href="<?= BASEURL; ?>Beranda" class="btn-error btn-error-secondary">
        <i class="fas fa-home"></i>
        Kembali ke Beranda
    </a>
</div>

<?php include 'footer.php'; ?>