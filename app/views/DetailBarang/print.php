<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Barang</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
    <style>
        :root { --primary: #0c1740; --bg: #f4f6f9; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg); padding: 20px; color: #333; margin: 0; }
        
        .container-box {
            background: white; padding: 25px; border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05); min-width: 1000px;
        }

        /* HEADER LAPORAN (TAMPILAN WEB) */
        .header-laporan {
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 3px solid var(--primary); padding-bottom: 15px; margin-bottom: 20px;
        }
        .header-laporan img { height: 80px; width: auto; }
        .header-text h2 { margin: 0; color: var(--primary); font-size: 24px; text-transform: uppercase; font-weight: 700; text-align: right; }
        .header-text p { margin: 5px 0 0; font-size: 12px; color: #666; text-align: right; }

        /* TOOLBAR */
        .toolbar-container {
            display: flex; justify-content: space-between; background: #f1f3f5;
            padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e9ecef;
        }
        .btn-group { display: flex; gap: 10px; }
        .btn-action {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 13px;
            border: none; cursor: pointer; text-decoration: none; color: white;
            transition: 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-back { background-color: #6c757d; }
        .btn-back:hover { background-color: #5a6268; }
        .btn-excel { background-color: #198754; }
        .btn-pdf { background-color: #dc3545; }
        .btn-print { background-color: var(--primary); }

        /* TABEL */
        table.dataTable { width: 100% !important; border-collapse: collapse; }
        table.dataTable thead th { 
            background-color: var(--primary) !important; 
            color: white !important; 
            font-size: 11px; padding: 12px 10px; border: 1px solid var(--primary);
        }
        table.dataTable tbody td { 
            font-size: 11px; padding: 8px; vertical-align: middle; border: 1px solid #dee2e6;
        }
        
        .img-preview { width: 40px; height: 40px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px; }
        .dataTables_filter, .dataTables_info, .dt-buttons { display: none !important; }

        /* MODE PRINT BROWSER */
        @media print {
            body { background: white; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .toolbar-container, .no-print { display: none !important; }
            .container-box { box-shadow: none; border: none; padding: 0; margin: 0; }
            table.dataTable thead th { background-color: #0c1740 !important; color: white !important; }
        }
    </style>
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
                <th width="3%">No</th> <th>Kode</th> <th>Nama Barang</th> <th>Merek</th> <th>Jml</th> <th>Satuan</th>
                <th>Kondisi</th> <th>Lokasi</th> <th>Status</th> <th>Deskripsi</th> <th>Tgl Ada</th>
                <th>Pinjam</th> <th>QR</th> <th>Foto</th>
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
                <td><?= $item['deskripsi_barang']; ?></td>
                <td><?= $item['tgl_pengadaan_barang']; ?></td>
                <td><?= $item['status_peminjaman']; ?></td>
                <td class="text-center"><?php if(!empty($item['qr_code'])): ?><img src="<?=BASEURL . $item['qr_code']; ?>" class="img-preview"><?php endif; ?></td>
                <td class="text-center"><?php if(!empty($item['foto_barang'])): ?><img src="<?=BASEURL . $item['foto_barang']; ?>" class="img-preview"><?php endif; ?></td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.1/js/buttons.print.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. KONVERSI LOGO KE BASE64
        var logoBase64 = null;
        var img = document.getElementById('logoImage');
        if (img) {
            var canvas = document.createElement("canvas");
            canvas.width = img.width || 200; 
            canvas.height = img.height || 60;
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            try { logoBase64 = canvas.toDataURL("image/png"); } catch(e) {}
        }

        var headerTitle = "LAPORAN INVENTARIS BARANG";
        var headerDate = "Dicetak pada: <?= date('d F Y, H:i'); ?>";
        var headerUser = "User: <?= $_SESSION['nama_user'] ?? 'Admin'; ?>";

        var table = $('#tableExport').DataTable({
            dom: 'Bfrtip',
            paging: false,
            ordering: false,
            searching: false,
            buttons: [
                { 
                    extend: 'excelHtml5', 
                    title: headerTitle, 
                    messageTop: headerDate + " | " + headerUser,
                    exportOptions: { columns: ':not(:last-child)' } 
                },
                { 
                    extend: 'pdfHtml5', 
                    title: '',
                    orientation: 'landscape', 
                    pageSize: 'LEGAL',
                    exportOptions: { columns: ':visible:not(:last-child)' },
                    customize: function(doc) {
                        // A. Gaya Header Tabel
                        doc.styles.tableHeader.fillColor = '#0c1740';
                        doc.styles.tableHeader.color = 'white';
                        doc.styles.tableHeader.alignment = 'center';
                        doc.defaultStyle.fontSize = 9;

                        // B. Header Custom (Logo + Teks)
                        var headerContent = {
                            margin: [0, 0, 0, 10],
                            columns: [
                                { image: logoBase64, width: 150, alignment: 'left' },
                                {
                                    width: '*',
                                    stack: [
                                        { text: headerTitle, fontSize: 16, bold: true, color: '#0c1740', alignment: 'right', margin: [0, 10, 0, 0] },
                                        { text: headerDate, fontSize: 10, color: '#555', alignment: 'right' },
                                        { text: headerUser, fontSize: 10, color: '#555', alignment: 'right' }
                                    ]
                                }
                            ]
                        };
                        
                        if (!logoBase64) headerContent.columns[0] = { text: 'SISTEM INVENTARIS', fontSize: 14, bold: true };

                        doc.content.splice(0, 1, headerContent); // Ganti Header Lama

                        // C. Garis Biru
                        doc.content.splice(1, 0, {
                            canvas: [{ type: 'line', x1: 0, y1: 0, x2: 950, y2: 0, lineWidth: 3, lineColor: '#0c1740' }],
                            margin: [0, 0, 0, 15]
                        });
                        
                        // D. PERBAIKAN BUG ERROR: CARI TABEL OTOMATIS
                        var objTabel = null;
                        for (var i = 0; i < doc.content.length; i++) {
                            if (doc.content[i].table) {
                                objTabel = doc.content[i];
                                break;
                            }
                        }

                        // Jika tabel ketemu, baru atur lebarnya (agar tidak error undefined)
                        if (objTabel) {
                             var colCount = objTabel.table.body[0].length;
                             var widths = [];
                             for(var k=0; k<colCount; k++) widths.push('*'); 
                             if(widths.length > 0) widths[0] = 25; // Kecilkan kolom No
                             objTabel.table.widths = widths;
                        }
                    } 
                },
                { extend: 'print', autoPrint: true, title: '', exportOptions: { columns: ':visible' } }
            ]
        });

        $('#triggerExcel').on('click', function() { table.button(0).trigger(); });
        $('#triggerPdf').on('click', function() { table.button(1).trigger(); });
        $('#triggerPrint').on('click', function() { table.button(2).trigger(); });
    });
</script>

<script src="<?= BASEURL; ?>/js/export.js"></script>

</body>
</html>