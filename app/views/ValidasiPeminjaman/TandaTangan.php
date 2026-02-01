<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Posisi Tanda Tangan</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>css/tanda_tangan.css">
</head>

<body>

    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-3 font-weight-bold">Memuat Seluruh Halaman Dokumen...</p>
    </div>

    <nav class="navbar navbar-light bg-white shadow mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1 mx-auto font-weight-bold">Atur Posisi Tanda Tangan</span>
        </div>
    </nav>

    <div class="container-fluid pb-5">
        <div class="row justify-content-center mb-3">
            <div class="col-md-8">
                <div class="alert alert-warning shadow-sm text-center">
                    <i class="fas fa-hand-paper mr-2"></i>
                    Geser <strong>Gambar Tanda Tangan</strong> ke halaman dan posisi yang diinginkan.
                </div>
            </div>
        </div>

        <div id="pdf-wrapper">
            <div id="pdf-container"></div>

            <div id="drag-fatimah" class="drag-box">
                <img src="<?= BASEURL; ?>img/ttd/ttd_fatimah.png?t=<?= time(); ?>" alt="TTD Fatimah">
            </div>

            <div id="drag-huzain" class="drag-box">
                <img src="<?= BASEURL; ?>img/ttd/ttd_huzain.png?t=<?= time(); ?>" alt="TTD Huzain">
            </div>
        </div>

        <div class="row justify-content-center pb-5 mt-4">
            <div class="col-md-8 text-center">
                <form action="<?= BASEURL; ?>ValidasiPeminjaman/prosesAccLaboran" method="post" id="formTTD">
                    <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($data['id_peminjaman']); ?>">

                    <input type="hidden" name="fatimah_page" id="fatimah_page" value="1">
                    <input type="hidden" name="huzain_page" id="huzain_page" value="1">

                    <input type="hidden" name="fatimah_x" id="fatimah_x">
                    <input type="hidden" name="fatimah_y" id="fatimah_y">
                    <input type="hidden" name="huzain_x" id="huzain_x">
                    <input type="hidden" name="huzain_y" id="huzain_y">

                    <button type="button" class="btn btn-primary btn-lg px-5 shadow mr-2" onclick="submitValidasi()">
                        <i class="fas fa-save mr-2"></i> Simpan Posisi
                    </button>

                    <a href="<?= BASEURL; ?>ValidasiPeminjaman/detail/<?= $data['id_peminjaman']; ?>" class="btn btn-secondary btn-lg px-5 shadow">
                        Batal
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.11/interact.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const url = '<?= BASEURL; ?>files/surat-peminjaman/<?= $data['file_surat']; ?>';
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        const pdfContainer = document.getElementById('pdf-container');
        // Array untuk menyimpan info tinggi setiap halaman untuk kalkulasi nanti
        let pagesMetaData = [];

        // --- 1. RENDER PDF (ALL PAGES) ---
        pdfjsLib.getDocument(url).promise.then(async function(pdf) {

            // Loop semua halaman
            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                await pdf.getPage(pageNum).then(function(page) {
                    let viewport = page.getViewport({
                        scale: 1.5
                    });

                    // Buat Canvas baru untuk halaman ini
                    let canvas = document.createElement('canvas');
                    canvas.className = 'pdf-page';
                    canvas.id = 'page-' + pageNum;
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    let ctx = canvas.getContext('2d');
                    let renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };

                    // Tambahkan ke container
                    pdfContainer.appendChild(canvas);

                    // Render
                    return page.render(renderContext).promise.then(() => {
                        // Simpan metadata halaman (Tinggi halaman)
                        pagesMetaData.push({
                            pageNumber: pageNum,
                            height: canvas.offsetHeight,
                            width: canvas.offsetWidth,
                            top: canvas.offsetTop // Posisi Y dari atas wrapper
                        });
                    });
                });
            }

            // Selesai render semua halaman
            document.getElementById('loader').style.display = 'none';
            document.querySelectorAll('.drag-box').forEach(el => el.style.display = 'block');

            // Set posisi awal di halaman pertama (Page 1)
            // Asumsi: metadata[0] adalah halaman 1
            if (pagesMetaData.length > 0) {
                // Fatimah di kiri bawah halaman 1
                initPositionOnPage('drag-fatimah', 1, 0.15, 0.75);
                // Huzain di kanan bawah halaman 1
                initPositionOnPage('drag-huzain', 1, 0.60, 0.75);
            }

        }).catch(function(error) {
            console.error(error);
            alert('Gagal memuat PDF: ' + error.message);
        });

        // --- Fungsi Helper: Set Posisi di Halaman Tertentu ---
        function initPositionOnPage(elementId, pageNum, percentX, percentY) {
            // Ambil metadata halaman yang sesuai (index array = pageNum - 1)
            let pageData = pagesMetaData[pageNum - 1];
            if (!pageData) return;

            let el = document.getElementById(elementId);

            let x = pageData.width * percentX;
            // Y Absolute = Posisi Y halaman + (Tinggi Halaman * Persen)
            let y = pageData.top + (pageData.height * percentY);

            el.setAttribute('data-x', x);
            el.setAttribute('data-y', y);
            el.style.transform = `translate(${x}px, ${y}px)`;
        }

        // --- 2. FUNGSI DRAG (INTERACT.JS) ---
        interact('.drag-box').draggable({
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
                    restriction: '#pdf-wrapper', // Batasi drag di dalam wrapper utama
                    endOnly: false
                })
            ]
        });

        // --- 3. SIMPAN KOORDINAT ---
        function submitValidasi() {

            // Fungsi untuk mencari tahu elemen ada di halaman mana
            function calculatePosition(id) {
                let el = document.getElementById(id);
                // Koordinat Absolute terhadap Wrapper
                let absX = parseFloat(el.getAttribute('data-x')) || 0;
                let absY = parseFloat(el.getAttribute('data-y')) || 0;

                // Cari halaman mana yang 'diduduki' oleh tanda tangan ini
                // Kita cek berdasarkan posisi Y
                let targetPage = null;

                // Tambahkan offset kecil (+50px) agar kalau ttd pas di garis potong, masuk ke halaman bawahnya
                let checkY = absY + 50;

                for (let i = 0; i < pagesMetaData.length; i++) {
                    let p = pagesMetaData[i];
                    // Cek jika Y berada dalam rentang halaman ini (termasuk margin)
                    // (p.top + p.height) + 10 (margin) 
                    if (checkY >= p.top && checkY <= (p.top + p.height + 10)) {
                        targetPage = p;
                        break;
                    }
                }

                // Jika tidak ketemu (misal drag terlalu jauh), default ke halaman terakhir
                if (!targetPage) {
                    targetPage = pagesMetaData[pagesMetaData.length - 1];
                }

                // Hitung koordinat Relatif terhadap Halaman tersebut
                // Y Relatif = Y Absolute - Y Awal Halaman
                let relativeY = absY - targetPage.top;

                // Hitung Persentase (0.0 - 1.0)
                let percentX = (absX / targetPage.width).toFixed(4);
                let percentY = (relativeY / targetPage.height).toFixed(4);

                return {
                    page: targetPage.pageNumber,
                    x: percentX,
                    y: percentY
                };
            }

            let fatimah = calculatePosition('drag-fatimah');
            let huzain = calculatePosition('drag-huzain');

            // Masukkan data halaman masing-masing ke input yang baru dibuat
            document.getElementById('fatimah_page').value = fatimah.page;
            document.getElementById('huzain_page').value = huzain.page;

            // Masukkan koordinat (X, Y)
            document.getElementById('fatimah_x').value = fatimah.x;
            document.getElementById('fatimah_y').value = fatimah.y;
            document.getElementById('huzain_x').value = huzain.x;
            document.getElementById('huzain_y').value = huzain.y;
            
            // Update pesan konfirmasi agar user tahu posisi halaman masing-masing
            let warningText = `TTD Fatimah di Hal ${fatimah.page}, TTD Huzain di Hal ${huzain.page}. Simpan?`;

            Swal.fire({
                title: 'Simpan Posisi?',
                text: warningText,
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