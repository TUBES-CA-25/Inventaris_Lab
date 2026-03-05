<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - Unauthorized</title>
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
                <i class="fas fa-lock" style="font-size: 150px; color: #f59e0b;"></i>
            </div>

            <!-- Error Code -->
            <div class="error-code">401</div>

            <!-- Error Title -->
            <h1 class="error-title">Sesi Tidak Valid</h1>

            <!-- Error Message -->
            <p class="error-message">
                Anda harus login terlebih dahulu untuk mengakses halaman ini.
            </p>

                <!-- Action Buttons -->
            <div class="error-actions">
                <a href="<?= BASEURL; ?>Login" class="btn-error btn-error-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Login Sekarang
                </a>
                <a href="<?= BASEURL; ?>Beranda" class="btn-error btn-error-secondary">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>

</html>