<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloading...</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f0; /* Background abu agar terlihat beda saat loading */
            padding: 20px;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Container Utama dengan Border Biru */
        .card-export {
            width: 100%;
            max-width: 900px; /* Lebar konten PDF */
            border: 2px solid #0C1740; /* Warna Navy */
            border-radius: 12px;
            padding: 40px;
            background: white;
            /* Penting untuk html2pdf agar background putih ter-render */
            background-color: #ffffff; 
        }

        /* Judul */
        .header-title {
            text-align: center;
            color: #0C1740;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Layout Grid */
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr 250px; /* Area gambar disesuaikan */
            gap: 20px;
            align-items: start;
        }

        /* Styling Item Data */
        .data-item {
            margin-bottom: 20px;
        }

        .label {
            display: block;
            font-size: 12px; /* Diperkecil sedikit agar muat di PDF */
            font-weight: 600;
            color: #000;
            margin-bottom: 4px;
        }

        .value {
            display: block;
            font-size: 12px;
            color: #666;
            font-weight: 400;
            line-height: 1.4;
        }

        /* Area Gambar */
        .image-area {
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 8px;
            height: 200px; /* Tinggi fix agar rapi */
            overflow: hidden;
        }

        .image-area img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Loading Indicator Styles */
        #loadingMsg {
            margin-bottom: 20px;
            font-size: 18px;
            color: #333;
            font-weight: bold;
            text-align: center;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #0C1740;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

    </style>
</head>
<body>

    <div id="loadingMsg">
        <div class="spinner"></div>
        Sedang menyiapkan PDF...<br>
        <span style="font-size: 12px; font-weight: normal;">Download akan dimulai otomatis.</span>
    </div>

    <div id="contentToPrint" style="position: absolute; left: -9999px;">
        
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
                            <span class="value"><?= $item['jumlah_barang']; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Satuan</span>
                            <span class="value"><?= $item['nama_satuan']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-right">
                    <div class="image-area">
                        <?php 
                            // Convert image ke Base64 agar terbaca oleh PDF generator
                            $imgSrc = '';
                            $path = !empty($item['foto_barang']) ? $item['foto_barang'] : 'img/no-image.jpg';
                            
                            // Cek path apakah relatif atau full url
                            // Jika path relatif (../public/...), kita coba ambil kontennya
                            // Untuk amannya di PDF JS, kita biarkan src mengarah ke URL http localhost
                            $finalSrc = BASEURL . $path;
                        ?>
                        
                        <?php if (!empty($item['foto_barang'])) : ?>
                            <img src="<?= $finalSrc; ?>" alt="Foto Barang" crossorigin="anonymous">
                        <?php else : ?>
                            <span style="color: #ccc;">No Image</span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Pilih elemen yang akan dijadikan PDF
            const element = document.getElementById('contentToPrint');
            
            // Nama file custom sesuai kode barang
            const fileName = 'Detail_<?= str_replace(['/','\\'], '_', $item['kode_barang']); ?>.pdf';

            // Konfigurasi PDF
            const opt = {
                margin:       [10, 10, 10, 10], // Margin (Atas, Kiri, Bawah, Kanan) dalam mm
                filename:     fileName,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true }, // Scale 2 agar teks tajam
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' } // Landscape agar layout 3 kolom muat
            };
        };
    </script>
</body>
</html>