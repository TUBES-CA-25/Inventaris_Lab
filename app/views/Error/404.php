<?php $data['title'] = '404 - Halaman Tidak Ditemukan';
include 'header.php'; ?>
<link rel="stylesheet" href="<?= BASEURL; ?>css/error.css?v=<?= time(); ?>">

<!-- Error Icon -->
<div class="error-animation">
    <i class="fas fa-search-minus" style="font-size: 150px; color: #3b82f6;"></i>
</div>

<!-- Error Code -->
<div class="error-code">404</div>

<!-- Error Title -->
<h1 class="error-title">Halaman Tidak Ditemukan</h1>

<!-- Error Message -->
<p class="error-message">
    Maaf, halaman yang Anda cari tidak dapat ditemukan.
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