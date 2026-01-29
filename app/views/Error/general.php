<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= isset($data['error_code']) ? $data['error_code'] : 'Error' ?> - Terjadi Kesalahan
    </title>
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

            <!-- Error Code -->
            <?php if (isset($data['error_code'])): ?>
                <div class="error-code">
                    <?= htmlspecialchars($data['error_code']); ?>
                </div>
            <?php endif; ?>

            <!-- Error Title -->
            <h1 class="error-title">
                <?= isset($data['error_title']) ? htmlspecialchars($data['error_title']) : 'Terjadi Kesalahan'; ?>
            </h1>

            <!-- Error Message -->
            <p class="error-message">
                <?= isset($data['error_message']) ? htmlspecialchars($data['error_message']) : 'Maaf, terjadi kesalahan yang tidak terduga. Silakan coba lagi nanti.'; ?>
            </p>

            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="/Inventaris_Lab/public/Beranda" class="btn-error btn-error-primary">
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
        </div>
    </div>

    <!-- Lottie Player Script -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>

</html>