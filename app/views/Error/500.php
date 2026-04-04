<?php $data['title'] = '500 - Server Error';
include 'header.php'; ?>
<link rel="stylesheet" href="<?= BASEURL; ?>css/error.css?v=<?= time(); ?>">

<!-- Error Icon -->
<div class="error-animation">
    <i class="fas fa-server" style="font-size: 150px; color: #6366f1;"></i>
</div>

<!-- Error Code -->
<div class="error-code">500</div>

<!-- Error Title -->
<h1 class="error-title">Kesalahan Server</h1>

<!-- Error Message -->
<p class="error-message">
    Maaf, terjadi masalah internal pada server kami. Teknisi kami sedang menanganinya.
</p>

<!-- Action Buttons -->
<div class="error-actions">
    <a href="<?= BASEURL; ?>Beranda" class="btn-error btn-error-primary">
        <i class="fas fa-home"></i>
        Kembali ke Beranda
    </a>
    <a href="javascript:location.reload()" class="btn-error btn-error-secondary">
        <i class="fas fa-sync"></i>
        Segarkan Halaman
    </a>
</div>

<?php include 'footer.php'; ?>