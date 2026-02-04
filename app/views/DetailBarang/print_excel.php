<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .header {
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Laporan Inventaris Barang</h2>
        <p>Dicetak pada: <?php echo date('d F Y, H:i'); ?> | User:
            <?php echo isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Admin'; ?></p>
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
                        <td><?= htmlspecialchars($item['kode_barang'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($item['sub_barang'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($item['nama_merek_barang'] ?? ''); ?></td>
                        <td class="text-center"><?= $item['jumlah_barang'] ?? '0'; ?></td>
                        <td><?= htmlspecialchars($item['nama_satuan'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($item['kondisi_barang'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($item['nama_lokasi_penyimpanan'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($item['status'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($item['spesifikasi_barang'] ?? ''); ?></td>
                        <td><?= isset($item['tgl_pengadaan_barang']) ? date('d-m-Y', strtotime($item['tgl_pengadaan_barang'])) : '-'; ?>
                        </td>
                        <td><?= htmlspecialchars($item['status_peminjaman'] ?? ''); ?></td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr>
                    <td colspan="12" class="text-center">Tidak ada data untuk ditampilkan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>