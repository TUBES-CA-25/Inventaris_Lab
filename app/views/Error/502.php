<?php $data['title'] = '502 - Gerbang Buruk';
include 'header.php'; ?>
<link rel="stylesheet" href="<?= BASEURL; ?>css/error.css?v=<?= time(); ?>">

<!-- Error Icon -->
<div class="error-animation">
    <i class="fas fa-server" style="font-size: 150px; color: #ef4444;"></i>
</div>

<!-- Error Code -->
<div class="error-code">502</div>

<!-- Error Title -->
<h1 class="error-title">Kesalahan Gerbang (Bad Gateway)</h1>

<!-- Error Message -->
<p class="error-message">
    Server menerima respon yang tidak valid dari server upstream saat mencoba memproses permintaan Anda.
</p>

<!-- Action Buttons -->
<div class="error-actions">
    <a href="<?= BASEURL; ?>Beranda" class="btn-error btn-error-primary">
        <i class="fas fa-home"></i>
        Kembali ke Beranda
    </a>
    <a href="javascript:location.reload()" class="btn-error btn-error-secondary">
        <i class="fas fa-sync-alt"></i>
        Coba Lagi
    </a>
</div>

<?php include 'footer.php'; ?>