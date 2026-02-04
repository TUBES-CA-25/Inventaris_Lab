<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris Barang</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
            color: #0c1740;
            text-transform: uppercase;
            font-size: 16px;
        }

        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #0c1740;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .img-preview {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
        }

        .status-badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <?php
        // Use absolute path for logo if possible, or base64 
        // For Dompdf isRemoteEnabled=true, URL might work if server accessible
        // Using text fallback if image issues arise
        ?>
        <h2>Laporan Inventaris Barang</h2>
        <p>Dicetak pada: <?= date('d F Y, H:i'); ?></p>
        <p>User: <?= $_SESSION['nama_user'] ?? 'Admin'; ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Merek</th>
                <th width="5%">Jml</th>
                <th width="8%">Satuan</th>
                <th width="8%">Kondisi</th>
                <th width="10%">Lokasi</th>
                <th width="8%">Status</th>
                <th>Spesifikasi</th>
                <th width="8%">Tgl Ada</th>
                <th width="8%">Pinjam</th>
                <th width="5%">QR</th>
                <th width="5%">Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            if (!empty($data['dataCetak'])):
                foreach ($data['dataCetak'] as $item):
                    // Handle image paths for dompdf (needs absolute system paths usually)
                    // Assuming BASEURL points to public, we need to convert to absolute path
                    // But if isRemoteEnabled is true, URLs *might* work.
                    // Let's try to verify if paths are relative or absolute URL.
            
                    $qrClean = str_replace('../public/', '', $item['qr_code'] ?? '');
                    $fotoClean = str_replace('../public/', '', $item['foto_barang'] ?? '');

                    // Helper to get image HTML
                    function getImgHtml($path)
                    {
                        if (empty($path))
                            return '-';
                        // Construct full URL
                        $fullUrl = BASEURL . '/' . $path;
                        // Alternatively, use server path if running locally to avoid network issues
                        // $serverPath = $_SERVER['DOCUMENT_ROOT'] . '/Inventaris_Lab1/public/' . $path;
                        return '<img src="' . $fullUrl . '" class="img-preview">';
                    }
                    ?>
                    <tr>
                        <td class="text-center"><?= $i++; ?></td>
                        <td><strong><?= htmlspecialchars($item['kode_barang']); ?></strong></td>
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

                        <td class="text-center">
                            <?= getImgHtml($qrClean); ?>
                        </td>

                        <td class="text-center">
                            <?= getImgHtml($fotoClean); ?>
                        </td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr>
                    <td colspan="14" class="no-data">Tidak ada data untuk ditampilkan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>