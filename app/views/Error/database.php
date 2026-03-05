<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesalahan Database</title>
    <link rel="shortcut icon" href="<?= BASEURL; ?>img/logo.svg" />

    <!-- Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Error CSS -->
    <link rel="stylesheet" href="<?= BASEURL; ?>css/error.css?v=<?= time(); ?>">
</head>

<body>
    <div class="error-page">
        <div class="error-decoration"></div>

        <div class="error-container">
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
                <a href="<?= BASEURL; ?>Login" class="btn-error btn-error-secondary">
                    <i class="fas fa-sign-in-alt"></i>
                    Halaman Login
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
        </div>
    </div>
</body>

</html>