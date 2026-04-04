<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Posisi Tanda Tangan Mahasiswa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="<?= BASEURL; ?>img/logo.svg" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="<?= BASEURL; ?>css/signature_styles.css?v=<?= time(); ?>">
</head>

<body>

    <div id="loader">
        <div class="loader-content">
            <div class="spinner-premium"></div>
            <h5 class="font-weight-bold text-dark" style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
                Menyiapkan Kanvas Digital</h5>
            <p class="text-muted small mb-0">Dokumen sedang dimuat, mohon tunggu sebentar...</p>
        </div>
    </div>

    <nav class="premium-nav text-center">
        <div class="nav-title">Tanda tangan berkas peminjaman</div>
    </nav>

    <div class="container-fluid">
        <div class="row justify-content-center mb-4">
            <div class="col-md-9 col-lg-8">
                <div class="tip-card d-flex align-items-center">
                    <div class="icon-circle mr-4"
                        style="background: linear-gradient(135deg, rgba(78, 115, 223, 0.1), rgba(78, 115, 223, 0.2)); color: var(--primary); width: 50px; height: 50px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-hand-pointer"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark font-weight-bold" style="font-family: 'Outfit', sans-serif;">Posisikan
                            Tanda Tangan Anda</h6>
                        <p class="mb-0 text-muted" style="font-size: 0.85rem; line-height: 1.5;">Gunakan kursor untuk
                            **menggeser** kotak tanda tangan. Tarik **sudut kanan bawah** untuk menyesuaikan ukuran agar
                            presisi.</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="pdf-wrapper">
            <div id="pdf-container"></div>

            <div id="drag-mhs" class="drag-box">
                <span class="sig-label">Tanda Tangan</span>
                <img src="<?= BASEURL; ?>img/ttd/<?= $data['user']['file_ttd']; ?>" alt="TTD Mahasiswa">
            </div>
        </div>

        <div class="action-bar">
            <form action="<?= BASEURL; ?>TemplateSurat/prosesSignature" method="post" id="formTTD"
                class="d-flex align-items-center gap-3">
                <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($data['id_peminjaman']); ?>">
                <input type="hidden" name="mhs_page" id="mhs_page" value="1">
                <input type="hidden" name="mhs_x" id="mhs_x">
                <input type="hidden" name="mhs_y" id="mhs_y">
                <input type="hidden" name="mhs_w" id="mhs_w">
                <input type="hidden" name="mhs_h" id="mhs_h">

                <button type="button" class="btn-send-custom mr-3" onclick="handleSignatureSubmission()">
                    <i class="fas fa-eye mr-2"></i> Simpan & Preview
                </button>

                <a href="<?= BASEURL; ?>TemplateSurat/lengkapi/<?= IdObfuscator::encode($data['id_peminjaman']); ?>"
                    class="btn-back-custom">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.11/interact.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= BASEURL; ?>js/signature_manager.js?v=<?= time(); ?>"></script>

    <script>
        const manager = new SignatureManager({
            url: '<?= BASEURL; ?>files/surat-peminjaman/<?= $data['file_surat']; ?>',
            pdfContainerId: 'pdf-container',
            loaderId: 'loader',
            dragBoxId: 'drag-mhs',
            pageWidth: 850
        });

        // Initialize PDF rendering and interactions
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
        manager.init();
        manager.setupInteractions();

        function handleSignatureSubmission() {
            const coords = manager.getFinalCoordinates();

            Swal.fire({
                title: 'Simpan Tanda Tangan?',
                text: `Tanda tangan akan diletakkan pada Halaman ${coords.page}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Cek Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('mhs_page').value = coords.page;
                    document.getElementById('mhs_x').value = coords.x;
                    document.getElementById('mhs_y').value = coords.y;
                    document.getElementById('mhs_w').value = coords.w;
                    document.getElementById('mhs_h').value = coords.h;
                    document.getElementById('formTTD').submit();
                }
            });
        }
    </script>
</body>

</html>