<?php $data['title'] = '504 - Waktu Gerbang Habis';
include 'header.php'; ?>
<link rel="stylesheet" href="<?= BASEURL; ?>css/error.css?v=<?= time(); ?>">

<!-- Error Icon -->
<div class="error-animation">
    <i class="fas fa-hourglass-half" style="font-size: 150px; color: #ef4444;"></i>
</div>

<!-- Error Code -->
<div class="error-code">504</div>

<!-- Error Title -->
<h1 class="error-title">Waktu Permintaan Habis</h1>

<!-- Error Message -->
<p class="error-message">
    Server upstream membutuhkan waktu terlalu lama untuk merespon. Mohon coba segarkan halaman.
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