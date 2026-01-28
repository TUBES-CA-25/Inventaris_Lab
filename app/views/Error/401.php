<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - Unauthorized</title>
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
                <i class="fas fa-user-lock" style="font-size: 180px; color: #ff9800;"></i>
            </div>

            <!-- Error Code -->
            <div class="error-code">401</div>

            <!-- Error Title -->
            <h1 class="error-title">Unauthorized - Belum Login</h1>

            <!-- Error Message -->
            <p class="error-message">
                <?= isset($data['error_message']) ? htmlspecialchars($data['error_message']) : 'Anda harus login terlebih dahulu untuk mengakses halaman ini.'; ?>
            </p>

            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="/Inventaris_Lab/public/Login" class="btn-error btn-error-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
                <a href="/Inventaris_Lab/public/Beranda" class="btn-error btn-error-secondary">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>

</html>