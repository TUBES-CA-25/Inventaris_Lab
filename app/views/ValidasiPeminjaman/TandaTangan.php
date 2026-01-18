<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Posisi Tanda Tangan</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body { background-color: #e3e6f0; font-family: sans-serif; }
        
        #pdf-wrapper {
            position: relative;
            width: 100%;
            max-width: 850px;
            margin: 20px auto;
            border: 1px solid #858796;
            background-color: #525659;
            overflow: hidden;
            user-select: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        #the-canvas { width: 100%; height: auto; display: block; }

        /* KOTAK DRAG UMUM */
        .drag-box {
            position: absolute;
            width: 160px; height: 70px;
            border: 2px dashed; border-radius: 8px;
            display: flex; flex-direction: column; 
            align-items: center; justify-content: center;
            text-align: center; color: #fff; font-weight: bold; font-size: 12px;
            cursor: grab; z-index: 100; touch-action: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
            text-shadow: 1px 1px 2px black;
        }
        .drag-box:active { cursor: grabbing; opacity: 0.8; }

        /* KOTAK FATIMAH (HIJAU) */
        #drag-fatimah {
            background-color: rgba(40, 167, 69, 0.6);
            border-color: #28a745;
            top: 50%; left: 30%; transform: translate(-50%, -50%);
        }

        /* KOTAK HUZAIN (BIRU) */
        #drag-huzain {
            background-color: rgba(0, 123, 255, 0.6);
            border-color: #007bff;
            top: 50%; left: 70%; transform: translate(-50%, -50%);
        }

        #loader {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.95); z-index: 9999;
            display: flex; justify-content: center; align-items: center; flex-direction: column;
        }
    </style>
</head>
<body>

    <div id="loader">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
        <p class="mt-3 font-weight-bold text-dark">Memuat PDF...</p>
    </div>

    <nav class="navbar navbar-light bg-white shadow mb-4">
        <div class="container-fluid">
            <a href="<?= BASEURL; ?>ValidasiPeminjaman/detail/<?= $data['id_peminjaman']; ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <span class="navbar-brand mb-0 h1 mx-auto font-weight-bold">Mode Pengaturan Tanda Tangan</span>
        </div>
    </nav>

    <div class="container-fluid pb-5">
        
        <div class="row justify-content-center mb-3">
            <div class="col-md-8">
                <div class="alert alert-info shadow-sm text-center">
                    <i class="fas fa-info-circle mr-1"></i> 
                    Geser <strong>Kotak Hijau</strong> (Fatimah) dan <strong>Kotak Biru</strong> (Huzain) ke posisi yang pas.
                </div>
            </div>
        </div>

        <div id="pdf-wrapper">
            <canvas id="the-canvas"></canvas>
            
            <div id="drag-fatimah" class="drag-box" data-x="0" data-y="0">
                <i class="fas fa-pen mb-1"></i> Fatimah (Laboran)
            </div>

            <div id="drag-huzain" class="drag-box" data-x="0" data-y="0">
                <i class="fas fa-user-tie mb-1"></i> Huzain (KaLab)
            </div>
        </div>

        <div class="row justify-content-center pb-5">
            <div class="col-md-8 text-center">
                <form action="<?= BASEURL; ?>ValidasiPeminjaman/prosesAccLaboran" method="post" id="formTTD">
                    <input type="hidden" name="id_peminjaman" value="<?= $data['id_peminjaman']; ?>">
                    <input type="hidden" name="page_target" id="input_page" value="1">
                    <input type="hidden" name="fatimah_x" id="fatimah_x">
                    <input type="hidden" name="fatimah_y" id="fatimah_y">
                    <input type="hidden" name="huzain_x" id="huzain_x">
                    <input type="hidden" name="huzain_y" id="huzain_y">

                    <button type="button" class="btn btn-success btn-lg px-4 shadow mr-2" onclick="submitValidasi()">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>

                    <a href="<?= BASEURL; ?>ValidasiPeminjaman/detail/<?= $data['id_peminjaman']; ?>" class="btn btn-secondary btn-lg px-4 shadow">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.11/interact.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // --- 1. SETUP PDF.JS ---
        const url = '<?= BASEURL; ?>files/surat-peminjaman/<?= $data['file_surat']; ?>';
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
        
        let pdfDoc = null, pageNum = 1, canvas = document.getElementById('the-canvas'), ctx = canvas.getContext('2d');

        function renderPage(num) {
            pdfDoc.getPage(num).then(function(page) {
                var viewport = page.getViewport({scale: 1.5});
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                page.render({canvasContext: ctx, viewport: viewport}).promise.then(() => {
                    document.getElementById('loader').style.display = 'none';
                    document.getElementById('input_page').value = num;
                    updateCoord('drag-fatimah', 'fatimah_x', 'fatimah_y');
                    updateCoord('drag-huzain', 'huzain_x', 'huzain_y');
                });
            });
        }

        pdfjsLib.getDocument(url).promise.then((pdfDoc_) => {
            pdfDoc = pdfDoc_;
            pageNum = pdfDoc.numPages; 
            renderPage(pageNum);
        }).catch(err => {
            alert("Gagal memuat PDF: " + err.message);
            document.getElementById('loader').style.display = 'none';
        });

        // --- 2. SETUP DRAG ---
        function setupDrag(idBox, inputX, inputY) {
            const position = { x: 0, y: 0 };
            interact('#' + idBox).draggable({
                listeners: {
                    move (event) {
                        position.x += event.dx;
                        position.y += event.dy;
                        event.target.style.transform = `translate(${position.x}px, ${position.y}px)`;
                    },
                    end (event) { updateCoord(idBox, inputX, inputY); }
                },
                modifiers: [ interact.modifiers.restrictRect({ restriction: 'parent', endOnly: true }) ]
            });
        }

        function updateCoord(idBox, inputXId, inputYId) {
            const wrapper = document.getElementById('pdf-wrapper');
            const box = document.getElementById(idBox);
            const boxRect = box.getBoundingClientRect();
            const wrapperRect = wrapper.getBoundingClientRect();
            
            // Hitung relatif terhadap wrapper
            const relativeLeft = boxRect.left - wrapperRect.left;
            const relativeTop = boxRect.top - wrapperRect.top;
            
            const percentX = relativeLeft / wrapperRect.width;
            const percentY = relativeTop / wrapperRect.height;

            document.getElementById(inputXId).value = percentX.toFixed(4);
            document.getElementById(inputYId).value = percentY.toFixed(4);
        }

        setupDrag('drag-fatimah', 'fatimah_x', 'fatimah_y');
        setupDrag('drag-huzain', 'huzain_x', 'huzain_y');

        function submitValidasi() {
            updateCoord('drag-fatimah', 'fatimah_x', 'fatimah_y');
            updateCoord('drag-huzain', 'huzain_x', 'huzain_y');
            
            // Cek apakah SweetAlert tersedia
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Simpan Posisi?',
                    text: "Tanda tangan akan ditempel permanen.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan'
                }).then((result) => {
                    if (result.isConfirmed) document.getElementById('formTTD').submit();
                });
            } else {
                if(confirm("Simpan posisi tanda tangan?")) document.getElementById('formTTD').submit();
            }
        }
    </script>
</body>
</html>