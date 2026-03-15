<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Posisi Tanda Tangan Mahasiswa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@500;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --navy: #0c1740;
            --bg-gray: #f8f9fc;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: var(--bg-gray);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            color: #333;
        }

        .premium-nav {
            background: linear-gradient(135deg, var(--navy) 0%, #1a2b6b 100%);
            padding: 1.5rem;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .nav-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
        }

        #pdf-wrapper {
            position: relative;
            width: 95%;
            max-width: 900px;
            margin: 0 auto 120px;
            background-color: #525659;
            border-radius: 12px;
            overflow: visible;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.2);
            min-height: 500px;
            border: 8px solid #525659;
        }

        #pdf-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #525659;
            border-radius: 4px;
        }

        .pdf-page {
            display: block;
            max-width: 100%;
            height: auto !important;
            margin-bottom: 12px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Drag box premium styling */
        .drag-box {
            position: absolute;
            top: 0;
            left: 0;
            width: 150px;
            height: auto;
            min-width: 60px;
            min-height: 30px;
            cursor: move;
            z-index: 1000;
            background: rgba(78, 115, 223, 0.08);
            border: 2px dashed var(--primary);
            backdrop-filter: blur(2px);
            display: none;
            touch-action: none;
            box-sizing: border-box;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .drag-box::after {
            content: '';
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 12px;
            height: 12px;
            background: var(--primary);
            border: 2px solid white;
            border-radius: 50%;
            cursor: nwse-resize;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .drag-box:active,
        .drag-box.active {
            border: 2px solid var(--primary-dark);
            background: rgba(78, 115, 223, 0.15);
            box-shadow: 0 0 15px rgba(78, 115, 223, 0.3);
        }

        .drag-box img {
            width: 100%;
            height: auto;
            object-fit: contain;
            pointer-events: none;
            filter: drop-shadow(0px 2px 3px rgba(0, 0, 0, 0.1));
            padding: 5px;
        }

        .sig-label {
            position: absolute;
            top: -26px;
            left: 0;
            width: 100%;
            text-align: center;
            background: var(--primary);
            color: white;
            border-radius: 4px 4px 0 0;
            font-size: 10px;
            padding: 3px 0;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Floating Fixed Footer Action Bar */
        .action-bar {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            padding: 1.25rem 2.5rem;
            border-radius: 50px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            z-index: 5000;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            width: auto;
            min-width: 400px;
            justify-content: center;
        }

        .btn-signature {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
            transition: var(--transition);
        }

        .btn-signature:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
            color: white;
        }

        .btn-cancel-custom {
            color: #666;
            background: transparent;
            border: 2px solid #ddd;
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 600;
            transition: var(--transition);
            text-decoration: none !important;
        }

        .btn-cancel-custom:hover {
            background: #f1f1f1;
            color: #333;
            border-color: #ccc;
        }

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loader-content {
            text-align: center;
        }

        .spinner-premium {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .tip-card {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            border-left: 5px solid var(--primary);
            box-shadow: var(--card-shadow);
        }

        @media (max-width: 576px) {
            .action-bar {
                width: 90%;
                min-width: unset;
                padding: 1rem;
                bottom: 20px;
            }

            .btn-signature,
            .btn-cancel-custom {
                padding: 10px 15px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>

    <div id="loader">
        <div class="loader-content">
            <div class="spinner-premium"></div>
            <p class="mt-3 font-weight-bold text-dark" style="font-family: 'Outfit', sans-serif;">Menyiapkan Dokumen
                Digital...</p>
        </div>
    </div>

    <nav class="premium-nav text-center">
        <h1 class="nav-title mb-0">Tanda Tangan Digital Mahasiswa</h1>
    </nav>

    <div class="container-fluid">
        <div class="row justify-content-center mb-4">
            <div class="col-md-9 col-lg-7">
                <div class="tip-card d-flex align-items-center">
                    <div class="icon-circle mr-3"
                        style="background: rgba(78, 115, 223, 0.1); color: var(--primary); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-magic"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-dark font-weight-bold" style="font-size: 0.9rem;">Tips Pengaturan</p>
                        <p class="mb-0 text-muted" style="font-size: 0.85rem;">Geser kotak tanda tangan ke posisi yang
                            sesuai, lalu tarik sudut kanan bawah untuk mengubah ukuran.</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="pdf-wrapper">
            <div id="pdf-container"></div>

            <div id="drag-mhs" class="drag-box">
                <span class="sig-label">Tanda Tangan Anda</span>
                <img src="<?= BASEURL; ?>img/ttd/<?= $data['user']['file_ttd']; ?>" alt="TTD Anda">
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

                <button type="button" class="btn-signature mr-3" onclick="submitSignature()">
                    <i class="fas fa-eye mr-2"></i> Simpan & Preview
                </button>

                <a href="<?= BASEURL; ?>TemplateSurat/lengkapi/<?= IdObfuscator::encode($data['id_peminjaman']); ?>"
                    class="btn-cancel-custom">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.11/interact.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const url = '<?= BASEURL; ?>files/surat-peminjaman/<?= $data['file_surat']; ?>';
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        const pdfContainer = documen t.getElementById('pdf-container');
        let pagesMetaData = [];

        // --- 1. RENDER PDF (OPTIMIZED PARALLEL) ---
        const loadingTask = pdfjsLib.getDocument(url);
        
         // Track progress
        loadingTask.onProgress = function (progress) {
            if (progress.total > 0) {
                let percent = Math.round((progress.loaded / progress.total) * 100);
                document.querySelector('#loader p').innerText = `Mengunduh Dokumen (${percent}%)...`;
            }
        };

        loadingTask.promise.then(async function(pdf) {
            document.querySelector('#loader p').innerText = `Menyiapkan ${pdf.numPages} Halaman...`;
            
            const renderPromises = [];
            let renderedCount = 0;

            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                const renderPage = pdf.getPage(pageNum).then(function(page) {
                    // Use scale 1.0 - we'll let CSS handle the fit
                    let viewport = page.getViewport({ scale: 1.0 });

                    let canvas = document.createElement('canvas');
                    canvas.className = 'pdf-page';
                    canvas.id = 'page-' + pageNum;
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    // Optimize context for static rendering
                    let ctx = canvas.getContext('2d', { 
                        alpha: false,
                        desynchronized: true
                    });
                    
                    let renderContext = { 
                        canvasContext: ctx, 
                         viewport: viewport,
                        enableWebGL: true // Experimental but can be faster
                    };

                    pdfContainer.appendChild(canvas);

                    return page.render(renderContext).promise.then(() => {
                        renderedCount++;
                        document.querySelector('#loader p').innerText = `Merender Halaman ${renderedCount} dari ${pdf.numPages}...`;
                        
                        pagesMetaData.push({
                            pageNumber: pageNum,
                            height: canvas.offsetHeight,
                            width: canvas.offsetWidth,
                            top: canvas.offsetTop
                        });
                    });
                });
                renderPromises.push(renderPage);
            }

            return Promise.all(renderPromises).then(() => {
                pagesMetaData.sort((a, b) => a.pageNumber - b.pageNumber);
                document.getElementById('loader').style.display = 'none';
                document.getElementById('drag-mhs').style.display = 'block';

                if (pagesMetaData.length > 0) {
                    initPosition('drag-mhs', 1, 0.15, 0.75);
                }
            });

        }).catch( function (error) {
            console.error(error);
            Swal.fire('Error', 'Gagal memuat PDF: ' + error.message, 'error');
        });

        function initPosition(elementId, pageNum, percentX, percentY) {
            let pageData = pagesMetaData[pageNum - 1];
            if (!pageData) return;

            let el = document.getElementById(elementId);
            let x = pageData.width * percentX;
            let y = pageData.top + (pageData.height * percentY);

            el.setAttribute('data-x', x);
            el.setAttribute('data-y', y);
            el.style.transform = `translate(${x}px, ${y}px)`;
        }

        // --- 2. DRAG & RESIZE LOGIC (INTERACT.JS) ---
        interact('.drag-box')
            .draggable({
                listeners: {
                    move(event) {
                        let target = event.target;
                        let x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                        let y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                        target.style.transform = `translate(${x}px, ${y}px)`;
                        target.setAttribute('data-x', x);
                        target.setAttribute('data-y', y);
                    }
                },
                modifiers: [
                    interact.modifiers.restrictRect({
                        restriction: '#pdf-wrapper',
                        endOnly: false
                    })
                ]
            })
            .resizable({
                // resize from all edges and corners
                edges: { left: true, right: true, bottom: true, top: true },
     listeners: {
                    move(event) {
                        let target = event.target;
                        let x = (parseFloat(target.getAttribute('data-x')) || 0);
                        let y = (parseFloat(target.getAttribute('data-y')) || 0);

                        // update the element's style
                        target.style.width = event.rect.width + 'px';
                        target.style.height = event.rect.height + 'px';

                        // translate when resizing from top or left edges
                        x += event.deltaRect.left;
                        y += event.deltaRect.top;

                        target.style.transform = 'translate(' + x + 'px,' + y + 'px)';

                        target.setAttribute('data-x', x);
                        target.setAttribute('data-y', y);
                    }
                },
                modifiers: [
                    // keep the edges inside the parent
                    interact.modifiers.restrictEdges({
                        outer: '#pdf-wrapper'
                    }),
                    // minimum size
                    interact.modifiers.restrictSize({
                        min: { width: 50, height: 20 }
                    })
                ],      inertia: true
            });

        // --- 3. SUBMISSION ---
        function submitSignature() {
            let el = document.getElementById('drag-mhs');
            let absX = parseFloat(el.getAttribute('data-x')) || 0;
            let absY = parseFloat(el.getAttribute('data-y')) || 0;
            let absW = el.offsetWidth;
            let absH = el.offsetHeight;

            let targetPage = null;
            let checkY = absY + (absH / 2); // Center check

            for (let i = 0; i < pagesMetaData.length; i++) {
                let p = pagesMetaData[i];
                if (checkY >= p.top && checkY <= (p.top + p.height + 10)) {
                    targetPage = p;
                    break;
                }
            }

            if (!targetPage) targetPage = pagesMetaData[pagesMetaData.length - 1];

            let relativeY = absY - targetPage.top;
            let percentX = (absX / targetPage.width).toFixed(4);
            let percentY = (relativeY / targetPage.height).toFixed(4);
            let percentW = (absW / targetPage.width).toFixed(4);
            let percentH = (absH / targetPage.height).toFixed(4);

            document.getElementById('mhs_page').value = targetPage.pageNumber;
            document.getElementById('mhs_x').value = percentX;
            document.getElementById('mhs_y').value = percentY;
            document.getElementById('mhs_w').value = percentW;
            document.getElementById('mhs_h').value = percentH;

            Swal.fire({
                title: 'Simpan Tanda Tangan?',
                text: `Tanda tangan akan diletakkan pada Halaman ${targetPage.pageNumber}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formTTD').submit();
                }
            });
        }
    </script>
</body>

</html>