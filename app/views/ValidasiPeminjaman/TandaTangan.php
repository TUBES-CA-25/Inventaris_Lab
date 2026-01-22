<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Posisi Tanda Tangan</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #e3e6f0;
            font-family: sans-serif;
            overflow-x: hidden;
        }

        #pdf-wrapper {
            position: relative;
            width: 100%;
            max-width: 850px;
            /* Lebar standar A4 di layar */
            margin: 20px auto;
            background-color: #525659;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
<<<<<<< HEAD
            /* Hapus overflow: hidden agar drag bisa lebih leluasa, tapi wrapper tetap membungkus */
            min-height: 500px;
        }

        /* Container untuk menampung semua canvas halaman */
        #pdf-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .pdf-page {
            display: block;
            width: 100%;
            height: auto;
            margin-bottom: 10px;
            /* Jarak antar halaman */
            background-color: white;
        }

=======
            overflow: hidden;
        }

        #the-canvas {
            width: 100%;
            height: auto;
            display: block;
        }

        /* --- GAYA BARU: GAMBAR TANDA TANGAN --- */
>>>>>>> 88e9f128c9946328174c406131f6bbb7ee021c28
        .drag-box {
            position: absolute;
            top: 0;
            left: 0;
<<<<<<< HEAD
            width: 140px;
            height: auto;
            cursor: move;
            z-index: 100;
            background: transparent;
            border: 1px dashed rgba(0, 0, 0, 0.2);
            display: none;
        }

=======

            /* Ukuran ini disesuaikan agar mirip hasil cetak PDF (sekitar 3-4cm) */
            width: 140px;
            height: auto;
            /* Tinggi menyesuaikan rasio gambar */

            cursor: move;
            z-index: 100;

            /* Transparan agar teks di belakang terlihat */
            background: transparent;

            /* Border tipis putus-putus untuk bantu lihat area klik */
            border: 1px dashed rgba(0, 0, 0, 0.2);

            display: none;
            /* Sembunyi dulu sebelum PDF load */
        }

        /* Saat didrag, border jadi jelas */
>>>>>>> 88e9f128c9946328174c406131f6bbb7ee021c28
        .drag-box:active,
        .drag-box:hover {
            border: 1px dashed #007bff;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Gambar Tanda Tangan */
        .drag-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
            /* Agar gambar tidak ter-select saat drag */
            filter: drop-shadow(0px 2px 2px rgba(0, 0, 0, 0.1));
            /* Bayangan tipis biar kontras */
        }

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    </style>
</head>

<body>

    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-3 font-weight-bold">Memuat Dokumen & Tanda Tangan...</p>
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
                    Geser <strong>Gambar Tanda Tangan</strong> di bawah ini ke posisi yang diinginkan.
                </div>
            </div>
        </div>

        <div id="pdf-wrapper">
            <canvas id="the-canvas"></canvas>

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
                    <input type="hidden" name="id_peminjaman" value="<?= $data['id_peminjaman']; ?>">
                    <input type="hidden" name="page_target" id="input_page" value="1">

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

        let canvas = document.getElementById('the-canvas');
        let ctx = canvas.getContext('2d');

        // --- 1. RENDER PDF ---
        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            return pdf.getPage(1);
        }).then(function(page) {
            let viewport = page.getViewport({
                scale: 1.5
            });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            let renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            page.render(renderContext).promise.then(function() {
                document.getElementById('loader').style.display = 'none';
                document.querySelectorAll('.drag-box').forEach(el => el.style.display = 'block');

                // SET POSISI AWAL (Sesuaikan visual agar enak dilihat pertama kali)
                // Kiri Bawah (Fatimah)
                initPosition('drag-fatimah', 0.15, 0.75);
                // Kanan Bawah (Huzain)
                initPosition('drag-huzain', 0.60, 0.75);
            });
        }).catch(function(error) {
            alert('Gagal memuat PDF: ' + error.message);
        });

        // --- 2. FUNGSI POSISI & DRAG ---
        function initPosition(id, percentX, percentY) {
            let el = document.getElementById(id);
            let w = canvas.offsetWidth;
            let h = canvas.offsetHeight;
            let x = w * percentX;
            let y = h * percentY;

            el.setAttribute('data-x', x);
            el.setAttribute('data-y', y);
            el.style.transform = `translate(${x}px, ${y}px)`;
        }

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
                    restriction: '#the-canvas', // Batasi dalam gambar PDF
                    endOnly: false
                })
            ]
        });

        // --- 3. SIMPAN KOORDINAT ---
        function submitValidasi() {
            let w = canvas.offsetWidth;
            let h = canvas.offsetHeight;

            function getPercent(id) {
                let el = document.getElementById(id);
                let x = parseFloat(el.getAttribute('data-x')) || 0;
                let y = parseFloat(el.getAttribute('data-y')) || 0;

                // Kalkulasi Persentase (Presisi Tinggi)
                return {
                    x: (x / w).toFixed(4),
                    y: (y / h).toFixed(4)
                };
            }

            let fatimah = getPercent('drag-fatimah');
            let huzain = getPercent('drag-huzain');

            document.getElementById('fatimah_x').value = fatimah.x;
            document.getElementById('fatimah_y').value = fatimah.y;
            document.getElementById('huzain_x').value = huzain.x;
            document.getElementById('huzain_y').value = huzain.y;

            Swal.fire({
                title: 'Simpan Posisi?',
                text: "Posisi tanda tangan akan disimpan sesuai tampilan ini.",
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
