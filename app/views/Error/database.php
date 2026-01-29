<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesalahan Database</title>
    <link rel="shortcut icon" href="/Inventaris_Lab/public/img/logo.svg" />

    <!-- Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Error CSS -->
    <link rel="stylesheet" href="/Inventaris_Lab/public/css/error.css?v=<?= time(); ?>">
</head>

<body>
    <div class="error-page">
        <div class="error-decoration"></div>

        <div class="error-container">
            <!-- Error Icon -->
            <div class="error-animation">
                <i class="fas fa-exclamation-triangle" style="font-size: 180px; color: #ff9800;"></i>
            </div>

            <!-- Error Icon -->
            <div class="error-code">
                <i class="fas fa-database"></i>
            </div>

            <!-- Error Title -->
            <h1 class="error-title">Kesalahan Koneksi Database</h1>

            <!-- Error Message -->
            <p class="error-message">
                Tidak dapat terhubung ke database. Mohon periksa koneksi database Anda atau hubungi administrator
                sistem.
            </p>

            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="javascript:location.reload()" class="btn-error btn-error-primary">
                    <i class="fas fa-sync-alt"></i>
                    Coba Lagi
                </a>
                <a href="/Inventaris_Lab/public/Login" class="btn-error btn-error-secondary">
                    <i class="fas fa-sign-in-alt"></i>
                    Ke Halaman Login
                </a>
            </div>

            <?php if (isset($data['db_error']) && !empty($data['db_error'])): ?>
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

    <!-- Lottie Player Script -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>

</html>