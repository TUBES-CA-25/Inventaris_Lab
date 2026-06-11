<?php include 'header.php'; ?>
<link rel="stylesheet" href="<?= BASEURL; ?>css/error.css?v=<?= time(); ?>">

<!-- Error Icon -->
<div class="error-animation">
    <i class="fas fa-circle-exclamation" style="font-size: 150px; color: #64748b;"></i>
</div>

<!-- Error Code -->
<div class="error-code">
    <?= isset($data['error_code']) ? htmlspecialchars($data['error_code']) : 'ERR'; ?>
</div>

<!-- Error Title -->
<h1 class="error-title">
    <?= isset($data['error_title']) ? htmlspecialchars($data['error_title']) : 'Terjadi Kesalahan'; ?>
</h1>

<!-- Error Message -->
<p class="error-message">
    Maaf, terjadi kesalahan yang tidak terduga. Silakan coba lagi nanti.
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

<?php if (isset($data['error_details']) && !empty($data['error_details'])): ?>
    <!-- Error Details -->
    <div class="error-details">
        <h6><i class="fas fa-info-circle"></i> Detail Error:</h6>
        <p>
            <?= htmlspecialchars($data['error_details']); ?>
        </p>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>