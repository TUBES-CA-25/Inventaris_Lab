<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Unit Barang</title>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/PrintSatuDetailPeminjaman.css?v=<?= time(); ?>">

    <style>
        /* 1. RESET BODY AGAR PREVIEW DI TENGAH LAYAR BROWSER */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #525659; /* Warna latar gelap seperti browser PDF */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* 2. CONTAINER UTAMA (KERTAS) */
        #contentToPrint {
            background: white;
            /* A4 Landscape width = 297mm. 
               Kita set 275mm agar ada sisa ruang untuk margin kiri-kanan (auto center).
            */
            width: 275mm; 
            padding: 30px; /* Padding dalam kertas */
            margin: 0 auto; /* Trik agar div ke tengah */
            box-shadow: 0 0 20px rgba(0,0,0,0.5); /* Bayangan agar terlihat seperti kertas */
            box-sizing: border-box;
            
        }

        /* 3. CARD EXPORT (Garis Pinggir Biru) */
        .card-export {
            width: 100%;
            border: 2px solid #0C1740;
            border-radius: 12px;
            padding: 25px;
            background: #fff;
        }

        /* CSS KHUSUS UNIT (QR CODE & FOTO) */
        .qr-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 100%;
        }
        
        .qr-wrapper img {
            max-width: 100px;
            height: auto;
            margin-bottom: 8px;
            padding: 5px;
            border: 1px solid #eee;
            border-radius: 6px;
        }

        .img-preview {
            max-height: 80px; 
            object-fit: contain; 
            margin-bottom: 15px;
        }

        .qr-label {
            font-size: 10px;
            font-weight: 700;
            color: #555;
            letter-spacing: 1px;
        }
        
        /* LOADING SCREEN */
        #loadingMsg {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>

    <div id="loadingMsg">
        <div class="spinner"></div>
        <h3 style="color:#0C1740;">Sedang Mencetak...</h3>
        <span style="font-size: 12px; color:#666;">Mohon tunggu sebentar</span>
    </div>

    <div id="contentToPrint" >
        
        <div class="card-export">
            
            <div class="header-title">Detail Unit Barang</div>

            <?php 
                $unit = $data['unit']; 
                $kodeLengkap = $unit['kode_master'] . '/' . $unit['jumlah_total'] . '/' . $unit['urutan_unit'];
            ?>

            <div class="grid-container">
                
                <div class="col-left">
                    <div class="data-item">
                        <span class="label">Kode Unit Lengkap</span>
                        <span class="value" style="font-weight:bold; color:#0C1740; font-size:14px;">
                            <?= $kodeLengkap; ?>
                        </span>
                    </div>
                    <div class="data-item">
                        <span class="label">Nama Barang</span>
                        <span class="value"><?= $unit['sub_barang']; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Detail Penyimpanan</span>
                        <span class="value"><?= !empty($unit['deskripsi_detail_lokasi']) ? $unit['deskripsi_detail_lokasi'] : '-'; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Jenis Barang</span>
                        <span class="value"><?= $unit['sub_barang']; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Merek Barang</span>
                        <span class="value"><?= $unit['nama_merek_barang']; ?></span>
                    </div>
                </div>

                <div class="col-middle">
                    <div class="data-item">
                        <span class="label">Lokasi Penyimpanan</span>
                        <span class="value"><?= $unit['nama_lokasi_penyimpanan']; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Kondisi Barang</span>
                        <span class="value">
                            <?php if($unit['kondisi_barang'] == 'Baik'): ?>
                                <span style="color:green; font-weight:bold;">Baik</span>
                            <?php else: ?>
                                <span style="color:red; font-weight:bold;"><?= $unit['kondisi_barang']; ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="data-item">
                        <span class="label">Status Peminjaman</span>
                        <span class="value">
                            <?= ($unit['status_peminjaman'] == 'Bisa') ? 'Bisa Dipinjam' : 'Tidak Bisa'; ?>
                        </span>
                    </div>
                    <div class="data-item">
                        <span class="label">Keterangan Label</span>
                        <span class="value"><?= !empty($unit['keterangan_label']) ? $unit['keterangan_label'] : '-'; ?></span>
                    </div>
                    <div class="data-item">
                        <span class="label">Satuan</span>
                        <span class="value"><?= $unit['nama_satuan']; ?></span>
                    </div>
                </div>

                <div class="col-right">
                    <div class="image-area" style="height: auto; min-height: 220px; display:block; border:none;">
                        <div class="image-area">
                        <?php 
                            // Convert image ke Base64 agar terbaca oleh PDF generator
                            $imgSrc = '';
                            $path = !empty($unit['foto_barang']) ? $unit['foto_barang'] : 'img/no-image.jpg';
                            
                            // Cek path apakah relatif atau full url
                            // Jika path relatif (../public/...), kita coba ambil kontennya
                            // Untuk amannya di PDF JS, kita biarkan src mengarah ke URL http localhost
                            $finalSrc = BASEURL . $path;
                        ?>
                        
                        <?php if (!empty($unit['foto_barang'])) : ?>
                            <img src="<?= $finalSrc; ?>" alt="Foto Barang" crossorigin="anonymous">
                        <?php else : ?>
                            <span style="color: #ccc;">No Image</span>
                        <?php endif; ?>
                    </div>
                        <div class="qr-wrapper">
                            
                            

                            <?php 
                                $cleanQrPath = str_replace('../public/', '', $unit['qr_code']);
                                $finalQrSrc = BASEURL . $cleanQrPath;
                            ?>
                            <?php if (!empty($unit['qr_code'])) : ?>
                                <img src="<?= $finalQrSrc; ?>" alt="QR Code" crossorigin="anonymous">
                                <span class="qr-label">SCAN DETAIL</span>
                            <?php else : ?>
                                <span style="color: #ccc;">No QR Code</span>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; font-size: 10px; color: #999; text-align: right;">
                Dicetak pada: <?= date('d/m/Y H:i'); ?>
            </div>

        </div>
    </div>

    <script>
        window.onload = function() {
            const element = document.getElementById('contentToPrint');
            const safeName = '<?= preg_replace("/[^a-zA-Z0-9]/", "_", $unit['sub_barang']); ?>';
            const fileName = 'Unit_' + safeName + '_No<?= $unit['urutan_unit']; ?>.pdf';

            const opt = {
                // Margin 10mm di setiap sisi (atas, kiri, bawah, kanan)
                margin:       [10, 10, 10, 10], 
                filename:     fileName,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true }, 
                // A4 Landscape
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' } 
            };

            html2pdf().set(opt).from(element).save().then(function() {
                document.getElementById('loadingMsg').innerHTML = `
                    <div style="color:green; font-size:30px;">✔</div>
                    <h3>Selesai!</h3>
                `;
                setTimeout(function() {
                    window.close();
                }, 1500);
            });
        };
    </script>
</body>
</html>