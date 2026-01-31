<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloading...</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/PrintSatuDetailPeminjaman.css?v=<?= time(); ?>">
    <meta name="print-filename" content="<?= str_replace(['/','\\'], '_', $data['item']['kode_barang']); ?>">
</head>
<body>

    <div id="loadingMsg">
        <div class="spinner"></div>
        Sedang menyiapkan PDF...<br>
        <span style="font-size: 12px; font-weight: normal;">Download akan dimulai otomatis.</span>
    </div>

    <div id="contentToPrint" class="offscreen">
        
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
                            <span class="no-image">No Image</span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="<?= BASEURL; ?>js/print_satu.js"></script>
</body>
</html>