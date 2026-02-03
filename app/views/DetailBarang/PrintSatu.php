<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Detail Barang</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/PrintSatuDetailPeminjaman.css?v=<?= time(); ?>">

    <style>
        /* Notifikasi proses download */
        #loadingMsg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Layout Grid Container agar rapi */
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            /* 3 Kolom */
            gap: 20px;
        }

        /* Styling area QR Code Master */
        .qr-master-area {
            margin-top: 20px;
            text-align: center;
            border-top: 2px dashed #eee;
            padding-top: 15px;
            width: 100%;
        }

        .qr-master-label {
            display: block;
            font-size: 11px;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .qr-master-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border: 1px solid #ddd;
            padding: 5px;
            background: #fff;
        }

        .image-area img {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
        }
    </style>
</head>

<body>

    <div id="loadingMsg">
        <div class="spinner"></div>
        <h3 style="color:#0C1740;">Sedang Mencetak...</h3>
        <span style="font-size: 12px; color:#666;">Mohon tunggu sebentar</span>
    </div>

    <div id="contentToPrint">

        <div class="card-export">

            <div class="header-title">Detail Barang</div>

            <?php $item = $data['item']; ?>

            <div class="grid-container">

                <div class="col-left">
                    <div class="data-item">
                        <span class="label">Kode Barang</span>
                        <span class="value"><?= $item['kode_barang']; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Detail Penyimpanan</span>
                        <span class="value"><?= !empty($item['deskripsi_detail_lokasi']) ? $item['deskripsi_detail_lokasi'] : '-'; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Jenis Barang</span>
                        <span class="value"><?= $item['sub_barang']; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Status Barang</span>
                        <span class="value"><?= $item['status_peminjaman']; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Deskripsi Barang</span>
                        <span class="value"><?= !empty($item['spesifikasi_barang']) ? $item['spesifikasi_barang'] : '-'; ?></span>
                    </div>
                </div>

                <div class="col-middle">
                    <div class="data-item">
                        <span class="label">Tanggal Pengadaan</span>
                        <span class="value"><?= date('d/m/Y', strtotime($item['tgl_pengadaan_barang'])); ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Lokasi Penyimpanan</span>
                        <span class="value"><?= $item['nama_lokasi_penyimpanan']; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Merek Barang</span>
                        <span class="value"><?= $item['nama_merek_barang']; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Kondisi Barang</span>
                        <span class="value"><?= $item['kondisi_barang']; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Keterangan Label</span>
                        <span class="value"><?= $item['keterangan_label']; ?></span>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="data-item">
                            <span class="label">Jumlah</span>
                            <span class="value"><?= isset($item['jumlah_total']) ? $item['jumlah_total'] : (isset($item['jumlah_barang']) ? $item['jumlah_barang'] : '0'); ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Satuan</span>
                            <span class="value"><?= $item['nama_satuan']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-right" style="display: flex; flex-direction: column; align-items: center;">

                    <div class="image-area">
                        <?php
                        $path = !empty($item['foto_barang']) ? $item['foto_barang'] : 'img/no-image.jpg';
                        $finalSrc = BASEURL . $path;
                        ?>
                        <?php if (!empty($item['foto_barang'])) : ?>
                            <img src="<?= $finalSrc; ?>" alt="Foto Barang" crossorigin="anonymous">
                        <?php else : ?>
                            <div style="height: 150px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; width: 100%; border: 1px dashed #ccc; color: #999;">
                                No Image
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="qr-master-area">
                        <span class="qr-master-label">QR Code Master</span>
                        <?php
                        // FIX 2: Menggunakan nama kolom yang benar 'qr_code_spesifikasi'
                        $qrPath = !empty($item['qr_code_spesifikasi']) ? $item['qr_code_spesifikasi'] : '';
                        $qrFinalSrc = BASEURL . $qrPath;
                        ?>

                        <?php if (!empty($item['qr_code_spesifikasi'])): ?>
                            <img src="<?= $qrFinalSrc; ?>" alt="QR Master" class="qr-master-img" crossorigin="anonymous">
                        <?php else: ?>
                            <div style="font-size: 10px; color: #999; margin-top: 5px;">(Belum Generated)</div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {

            const element = document.getElementById('contentToPrint');
            // Bersihkan nama file dari karakter aneh
            const cleanCode = '<?= str_replace(['/', '\\', ' '], '_', $item['kode_barang']); ?>';
            const fileName = 'Detail_' + cleanCode + '.pdf';

            const opt = {
                margin: [30, 30, 10, 10],
                filename: fileName,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    scrollY: 0
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'landscape'
                }
            };

            html2pdf()
                .set(opt)
                .from(element)
                .save()
                .then(function() {
                    document.getElementById('loadingMsg').innerHTML = `
                    <div style="color:green; font-size:30px;">✔</div>
                    <h3>Selesai!</h3>
                `;

                    setTimeout(function() {
                        window.history.back();
                    }, 1500);
                });
        };
    </script>
</body>

</html>