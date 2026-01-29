<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
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
            <div class="error-code">403</div>

            <!-- Error Title -->
            <h1 class="error-title">Akses Ditolak</h1>

            <!-- Error Message -->
            <p class="error-message">
                <?= isset($data['error_message']) ? htmlspecialchars($data['error_message']) : 'Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.'; ?>
            </p>

            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="/Inventaris_Lab/public/Beranda" class="btn-error btn-error-primary">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
                <?php if (isset($_SESSION['login'])): ?>
                    <a href="/Inventaris_Lab/public/Logout" class="btn-error btn-error-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                <?php else: ?>
                    <a href="/Inventaris_Lab/public/Login" class="btn-error btn-error-secondary">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Lottie Player Script -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>

</html>