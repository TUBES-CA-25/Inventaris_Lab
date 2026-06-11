<?php $data['title'] = 'Kesalahan Database';
include 'header.php'; ?>
<link rel="stylesheet" href="<?= BASEURL; ?>css/error.css?v=<?= time(); ?>">

<!-- Error Icon -->
<div class="error-animation">
    <i class="fas fa-database" style="font-size: 150px; color: #334155;"></i>
</div>

<!-- Error Code -->
<div class="error-code">DB</div>

<!-- Error Title -->
<h1 class="error-title">Koneksi Database Terputus</h1>

<!-- Error Message -->
<p class="error-message">
    Kami mohon maaf, sistem tidak dapat terhubung ke database saat ini. Silakan hubungi administrator atau
    coba segarkan halaman.
</p>

<!-- Action Buttons -->
<div class="error-actions">
    <a href="javascript:location.reload()" class="btn-error btn-error-primary">
        <i class="fas fa-sync-alt"></i>
        Coba Lagi
    </a>
    <a href="javascript:history.back()" class="btn-error btn-error-secondary">
        <i class="fas fa-arrow-left"></i>
        Halaman Sebelumnya
    </a>
</div>

<?php if (DEVELOPMENT_MODE && isset($data['db_error']) && !empty($data['db_error'])): ?>
    <!-- Database Error Details -->
    <div class="error-details">
        <h6><i class="fas fa-exclamation-triangle"></i> Detail Kesalahan Database:</h6>
        <p>
            <?= htmlspecialchars($data['db_error']); ?>
        </p>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>