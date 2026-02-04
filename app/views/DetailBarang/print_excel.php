<?php
// Pastikan tidak ada spasi atau karakter apapun sebelum tag <?php ini
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Inventaris.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        /* Pengaturan agar tampilan tabel di Excel rapi */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            border: 1px solid #000;
            font-weight: bold;
            text-align: center;
        }
        td {
            border: 1px solid #000;
            vertical-align: top;
        }
        .text-center {
            text-align: center;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
        }
        .info {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="title">LAPORAN INVENTARIS BARANG</div>
    
    <div class="info">
        Dicetak pada: <?= date('d F Y, H:i'); ?> 
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
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
            <?php
            $i = 1;
            if (!empty($data['dataCetak'])):
                foreach ($data['dataCetak'] as $item):
            ?>
                <tr>
                    <td class="text-center"><?= $i++; ?></td>
                    <td><?= $item['kode_barang']; ?></td>
                    <td><?= htmlspecialchars($item['sub_barang']); ?></td>
                    <td><?= htmlspecialchars($item['nama_merek_barang']); ?></td>
                    <td class="text-center"><?= $item['jumlah_barang']; ?></td>
                    <td><?= htmlspecialchars($item['nama_satuan']); ?></td>
                    <td><?= htmlspecialchars($item['kondisi_barang']); ?></td>
                    <td><?= htmlspecialchars($item['nama_lokasi_penyimpanan']); ?></td>
                    <td><?= htmlspecialchars($item['status']); ?></td>
                    <td><?= htmlspecialchars($item['spesifikasi_barang']); ?></td>
                    <td><?= date('d-m-Y', strtotime($item['tgl_pengadaan_barang'])); ?></td>
                    <td><?= htmlspecialchars($item['status_peminjaman']); ?></td>
                    <td></td> <td></td> </tr>
            <?php 
                endforeach;
            else: 
            ?>
                <tr>
                    <td colspan="14" class="text-center">Tidak ada data untuk ditampilkan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>