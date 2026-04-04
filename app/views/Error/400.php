<?php $data['title'] = '400 - Permintaan Salah';
include 'header.php'; ?>
<link rel="stylesheet" href="<?= BASEURL; ?>css/error.css?v=<?= time(); ?>">

<!-- Error Icon -->
<div class="error-animation">
    <i class="fas fa-exclamation-circle" style="font-size: 150px; color: #f59e0b;"></i>
</div>

<!-- Error Code -->
<div class="error-code">400</div>

<!-- Error Title -->
<h1 class="error-title">Permintaan Tidak Valid</h1>

<!-- Error Message -->
<p class="error-message">
    Maaf, server tidak dapat memproses permintaan Anda karena formatnya tidak didukung atau salah.
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