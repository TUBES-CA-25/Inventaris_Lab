<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Tanda Tangan Mahasiswa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="<?= BASEURL; ?>img/logo.svg" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="<?= BASEURL; ?>css/signature_styles.css?v=<?= time(); ?>">
</head>

<body>

    <nav class="premium-nav text-center">
        <div class="nav-title">Tanda tangan berkas peminjaman</div>
    </nav>

    <div class="container">
        <div class="preview-container">
            <div class="alert alert-info border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                Silakan periksa hasil tanda tangan Anda di bawah ini sebelum dikumpulkan.
            </div>

            <iframe id="pdf-preview" src="<?= BASEURL; ?>files/surat-peminjaman/<?= $data['file_preview']; ?>#toolbar=0"
                frameborder="0"></iframe>

            <div class="action-buttons">
                <form action="<?= BASEURL; ?>TemplateSurat/batal/<?= IdObfuscator::encode($data['id_peminjaman']); ?>"
                    method="post">
                    <button type="submit" class="btn-ulangi">
                        <i class="fas fa-undo-alt mr-2"></i> Ulangi Posisi
                    </button>
                </form>

                <form
                    action="<?= BASEURL; ?>TemplateSurat/kumpulkan/<?= IdObfuscator::encode($data['id_peminjaman']); ?>"
                    method="post">
                    <input type="hidden" name="file_final" value="<?= $data['file_preview']; ?>">
                    <button type="submit" class="btn-kumpulkan">
                        <i class="fas fa-paper-plane mr-2"></i> Kumpulkan & Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>