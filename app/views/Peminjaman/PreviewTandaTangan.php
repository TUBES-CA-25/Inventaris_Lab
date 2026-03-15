<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Tanda Tangan Mahasiswa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Inter', sans-serif;
        }

        .preview-container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        #pdf-preview {
            width: 100%;
            height: 600px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }

        .btn-kumpulkan {
            background: #4e73df;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-kumpulkan:hover {
            background: #2e59d9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
            color: white;
        }

        .btn-ulangi {
            background: #fff;
            color: #e74a3b;
            border: 2px solid #e74a3b;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-ulangi:hover {
            background: #e74a3b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 74, 59, 0.3);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container-fluid text-center">
            <span class="navbar-brand mb-0 h1 mx-auto font-weight-bold text-primary">Preview Dokumen Ter-Tanda
                Tangan</span>
        </div>
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