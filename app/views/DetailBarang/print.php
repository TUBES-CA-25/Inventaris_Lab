<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Barang</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/ExportDetailBarang.css">
    
</head>
<body>

<div class="container-box">
    <div class="header-laporan">
        <img id="logoImage" src="<?=BASEURL;?>img/logo bg putih.svg" alt="Logo" crossorigin="anonymous">
        <div class="header-text">
            <h2>Laporan Inventaris Barang</h2>
            <p>Dicetak pada: <?= date('d F Y, H:i'); ?></p>
            <p>User: <?= $_SESSION['nama_user'] ?? 'Admin'; ?></p>
        </div>
    </div>

    <div class="toolbar-container">
        <a href="<?= BASEURL ?>DetailBarang" class="btn-action btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        <div class="btn-group">
            <button id="triggerExcel" class="btn-action btn-excel"><i class="fa-solid fa-file-excel"></i> Excel</button>
            <button id="triggerPdf" class="btn-action btn-pdf"><i class="fa-solid fa-file-pdf"></i> PDF</button>
            <button id="triggerPrint" class="btn-action btn-print"><i class="fa-solid fa-print"></i> Print</button>
        </div>
    </div>

    <table id="tableExport" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th width="3%">No</th> 
                <th>Kode</th> 
                <th>Nama Barang</th> 
                <th>Merek</th> 
                <th>Jml</th> 
                <th>Satuan</th>
                <th>Kondisi</th> 
                <th>Lokasi</th> 
                <th>Status</th> 
                <th>Spesifikasi</th> 
                <th>Tgl Ada</th>
                <th>Pinjam</th> 
                <th>QR</th> 
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; if (!empty($data['dataCetak'])) : foreach ($data['dataCetak'] as $row): $item = isset($row[0]) ? $row[0] : $row; ?>
            <tr>
                <td class="text-center"><?= $i++; ?></td>
                <td><strong><?= $item['kode_barang']; ?></strong></td>
                <td><?= $item['sub_barang']; ?></td>
                <td><?= $item['nama_merek_barang']; ?></td>
                <td class="text-center"><?= $item['jumlah_barang']; ?></td>
                <td><?= $item['nama_satuan']; ?></td>
                <td><?= $item['kondisi_barang']; ?></td>
                <td><?= $item['nama_lokasi_penyimpanan']; ?></td>
                <td><?= $item['status']; ?></td>
                <td><?= $item['spesifikasi_barang']; ?></td> 
                <td><?= $item['tgl_pengadaan_barang']; ?></td>
                <td><?= $item['status_peminjaman']; ?></td>
                
                <td class="text-center">
                    <?php if(!empty($item['qr_code'])): ?>
                        <img src="<?=BASEURL . $item['qr_code']; ?>" class="img-preview" crossorigin="anonymous">
                    <?php endif; ?>
                </td>
                
                <td class="text-center">
                    <?php if(!empty($item['foto_barang'])): ?>
                        <img src="<?=BASEURL . $item['foto_barang']; ?>" class="img-preview" crossorigin="anonymous">
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script src="<?= BASEURL; ?>/js/export.js"></script>

</body>
</html>



