<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}

$master = $data['dataTampilDetailBarang'];
$units = $data['listUnits'];
?>

<div class="content">
    <div class="container-fluid p-4">

        <div class="card-modern mb-4">
            <div class="card-body-modern">
                <h3 class="section-title">Detail Barang</h3>
                <div class="row g-0">
                    <div class="col-lg-8 left-panel">
                        <div class="info-grid">
                            <div class="info-group">
                                <label>Kode Barang</label>
                                <div class="value"><?= $master['kode_barang']; ?></div>
                            </div>
                            <div class="info-group">
                                <label>Tanggal Pengadaan</label>
                                <div class="value"><?= date('d/m/Y', strtotime($master['tgl_pengadaan_barang'])); ?>
                                </div>
                            </div>
                            <div class="info-group">
                                <label>Detail Penyimpanan</label>
                                <div class="value">
                                    <?= !empty($master['deskripsi_detail_lokasi']) ? $master['deskripsi_detail_lokasi'] : '-'; ?>
                                </div>
                            </div>
                            <div class="info-group">
                                <label>Lokasi Penyimpanan</label>
                                <div class="value"><?= $master['nama_lokasi_penyimpanan']; ?></div>
                            </div>
                            <div class="info-group">
                                <label>Jenis Barang</label>
                                <div class="value"><?= $master['sub_barang']; ?></div>
                            </div>
                            <div class="info-group">
                                <label>Merek Barang</label>
                                <div class="value"><?= $master['nama_merek_barang']; ?></div>
                            </div>
                            <div class="info-group">
                                <label>Spesifikasi Barang</label>
                                <div class="value">
                                    <?= !empty($master['spesifikasi_barang']) ? $master['spesifikasi_barang'] : '-'; ?>
                                </div>
                            </div>
                            <div class="info-group">
                                <label>Keterangan Label</label>
                                <div class="value"><?= $master['keterangan_label']; ?></div>
                            </div>
                            <div class="info-group">
                                <label>Jumlah Barang</label>
                                <div class="value"><?= $master['jumlah_total']; ?></div>
                            </div>
                            <div class="info-group">
                                <label>Satuan Barang</label>
                                <div class="value"><?= $master['nama_satuan']; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 right-panel">
                        <div class="product-image-container">
                            <?php if (!empty($master['foto_barang'])): ?>
                                <img src="<?= BASEURL . $master['foto_barang']; ?>" alt="Foto Barang">
                            <?php else: ?>
                                <div class="no-image-placeholder">
                                    <i class="fa-solid fa-image fa-3x mb-2"></i>
                                    <span>No Image</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn-dark-blue" data-bs-toggle="modal"
                                data-bs-target="#modalQRMaster">
                                <i class="fa-solid fa-qrcode"></i> Generate QR Code
                            </button>

                            <form action="<?= BASEURL; ?>DetailBarang/cetak" method="post" target="_blank"
                                style="width:100%;">
                                <input type="hidden" name="id_barang[]"
                                    value="<?= IdObfuscator::encode($master['id_barang']); ?>">
                                <button type="submit" class="btn-dark-blue btn-action-custom">
                                    <i class="fa-solid fa-file-pdf"></i> Ekspor PDF
                                </button>
                            </form>

                            <?php if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])): ?>
                                <a href="<?= BASEURL; ?>DetailBarang/ubah/<?= IdObfuscator::encode($master['id_barang']); ?>"
                                    class="btn-dark-blue btn-action-custom">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                            <?php endif; ?>

                            <a href="<?= BASEURL; ?>DetailBarang" class="btn-action-custom btn-gray-slide">
                                <i class="fa-solid fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-modern">
            <div class="card-body-modern">
                <h4 class="table-title mb-4"><i class="fa-solid fa-list-ul me-2"></i> Daftar Unit Barang</h4>
                <div class="table-responsive">
                    <table class="table table-hover custom-table align-middle w-100">
                        <thead>
                            <tr>
                                <th class="text-center" width="50">No</th>
                                <th>Kode Unit</th>
                                <th>Kondisi</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Peminjaman</th>
                                <th width="100" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($units as $unit): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $unit['urutan_unit']; ?></td>
                                    <td>
                                        <span class="badge-code">
                                            <?= $master['kode_barang'] . '/' . $master['jumlah_total'] . '/' . $unit['urutan_unit']; ?>
                                        </span>
                                    </td>
                                    <td><?= $unit['kondisi_barang']; ?></td>
                                    <td><?= $unit['nama_lokasi_penyimpanan']; ?></td>
                                    <td><?= $unit['status']; ?></td>
                                    <td>
                                        <?php if ($unit['status_peminjaman'] == 'Bisa'): ?>
                                            <span class="badge bg-success-soft text-success">Bisa</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-soft text-warning">Tidak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        // LOGIKA PATH GAMBAR
                                        $pathDB = isset($unit['qr_code']) ? $unit['qr_code'] : '';
                                        $cleanPath = str_replace('../public/', '', $pathDB);

                                        // Gunakan BASEURL untuk URL browser
                                        $finalUrl = BASEURL . $cleanPath;

                                        // Cek file menggunakan DOCUMENT_ROOT agar akurat di server side
                                        // Sesuaikan path jika folder public Anda berbeda
                                        $pathFisik = $_SERVER['DOCUMENT_ROOT'] . '/Inventaris_Lab1/public/' . $cleanPath;
                                        $fileAda = (!empty($pathDB) && file_exists($pathFisik));

                                        $judulRaw = "Unit " . $unit['urutan_unit'];
                                        $judulSafe = htmlspecialchars($judulRaw, ENT_QUOTES, 'UTF-8');
                                        ?>

                                        <button type="button" class="btn-icon-simple"
                                            onclick="showQrUnit('<?= $judulSafe; ?>', '<?= $finalUrl; ?>', <?= $fileAda ? 'true' : 'false'; ?>)"
                                            title="Lihat QR Code">
                                            <i class="fa-solid fa-qrcode"></i>
                                        </button>
                                        <a href="<?= BASEURL; ?>DetailBarang/cetakUnit/<?= IdObfuscator::encode($unit['id_barang']); ?>"
                                            target="_blank" class="btn-icon-simple ms-2" title="Cetak PDF Unit">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                        <?php if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2'])): ?>
                                            <a href="<?= BASEURL; ?>DetailBarang/ubahUnit/<?= IdObfuscator::encode($unit['id_barang']); ?>"
                                                class="btn-icon-simple ms-2" title="Edit Unit">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($data['totalHalaman'] > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">

                            <li class="page-item <?= ($data['halamanAktif'] <= 1) ? 'disabled' : ''; ?>">
                                <?php if ($data['halamanAktif'] <= 1): ?>
                                    <span class="page-link">
                                        <i class="fa-solid fa-angle-left"></i> Previous
                                    </span>
                                <?php else: ?>
                                    <a class="page-link"
                                        href="<?= BASEURL; ?>DetailBarang/detail/<?= $data['id_encoded']; ?>?p=<?= ($data['halamanAktif'] - 1); ?>">
                                        <i class="fa-solid fa-angle-left"></i> Previous
                                    </a>
                                <?php endif; ?>
                            </li>

                            <?php for ($i = 1; $i <= $data['totalHalaman']; $i++): ?>
                                <li class="page-item <?= ($i == $data['halamanAktif']) ? 'active' : ''; ?>">
                                    <a class="page-link"
                                        href="<?= BASEURL; ?>DetailBarang/detail/<?= $data['id_encoded']; ?>?p=<?= $i; ?>">
                                        <?= $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <li
                                class="page-item <?= ($data['halamanAktif'] >= $data['totalHalaman']) ? 'disabled' : ''; ?>">
                                <?php if ($data['halamanAktif'] >= $data['totalHalaman']): ?>
                                    <span class="page-link">
                                        Next <i class="fa-solid fa-angle-right"></i>
                                    </span>
                                <?php else: ?>
                                    <a class="page-link"
                                        href="<?= BASEURL; ?>DetailBarang/detail/<?= $data['id_encoded']; ?>?p=<?= ($data['halamanAktif'] + 1); ?>">
                                        Next <i class="fa-solid fa-angle-right"></i>
                                    </a>
                                <?php endif; ?>
                            </li>

                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalQRMaster" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-qrcode me-2"></i> QR Code Barang
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                    style="background:none; border:none; color:white; opacity:0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">

                <h4 class="mb-3"><?= $master['kode_barang']; ?></h4>

                <div class="qr-container">
                    <?php
                    // Ambil path QR Master
                    $qrCodePath = isset($master['qr_code_spesifikasi']) ? $master['qr_code_spesifikasi'] : '';
                    $cleanQrPath = str_replace('../public/', '', $qrCodePath);
                    $pathFisik = $_SERVER['DOCUMENT_ROOT'] . '/Inventaris_Lab1/public/' . $cleanQrPath;
                    ?>

                    <?php if (!empty($qrCodePath) && file_exists($pathFisik)): ?>
                        <img src="<?= BASEURL . $cleanQrPath; ?>" alt="QR Code">
                    <?php else: ?>
                        <div class="error-message">
                            <i class="fa-solid fa-triangle-exclamation fa-2x mb-2"></i><br>
                            QR Code File Tidak Ditemukan
                        </div>
                    <?php endif; ?>
                </div>

                <p class="text-muted small">
                    <i class="fa-solid fa-info-circle me-1"></i>
                    Scan QR code ini untuk melihat detail spesifikasi utama
                </p>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i> Tutup
                </button>

                <?php if (!empty($qrCodePath)): ?>
                    <a href="<?= BASEURL . $cleanQrPath; ?>"
                        download="QR_MASTER_<?= str_replace('/', '-', $master['kode_barang']); ?>.png"
                        class="btn btn-primary">
                        <i class="fa-solid fa-download"></i> Download QR
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalQR" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-qrcode me-2"></i> QR Code Barang (Unit)
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                    style="background:none; border:none; color:white; opacity:0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">

                <h4 class="mb-3" id="modalUnitTitle">Loading...</h4>

                <div id="containerGambarUnit" class="qr-container" style="display: none;">
                    <img id="modalUnitImage" src="" alt="QR Code">
                </div>

                <div id="containerErrorUnit" class="error-message" style="display: none;">
                    <i class="fa-solid fa-triangle-exclamation fa-2x mb-2"></i><br>
                    QR Code File Tidak Ditemukan
                </div>

                <p class="text-muted small">
                    <i class="fa-solid fa-info-circle me-1"></i>
                    Scan QR code ini untuk melihat detail inventaris unit ini
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i> Tutup
                </button>

                <a id="modalUnitDownload" href="#" class="btn btn-primary" download>
                    <i class="fa-solid fa-download"></i> Download QR
                </a>
            </div>

        </div>
    </div>
</div>
<script>

</script>

<style>
    /* CSS tetap sama seperti sebelumnya */
    .card-modern {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: none;
    }

    .card-body-modern {
        padding: 40px;
    }

    .section-title {
        font-size: 22px;
        font-weight: 800;
        color: #101a32;
        margin-bottom: 35px;
    }

    .left-panel {
        padding-right: 40px;
        border-right: 1px solid #e0e0e0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        row-gap: 30px;
        column-gap: 20px;
    }

    .info-group label {
        font-size: 15px;
        font-weight: 700;
        color: #101a32;
        margin-bottom: 6px;
    }

    .info-group .value {
        font-size: 15px;
        color: #8898aa;
        font-weight: 400;
    }

    .right-panel {
        padding-left: 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .product-image-container {
        width: 100%;
        max-width: 300px;
        aspect-ratio: 4/3;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        padding: 10px;
        border: 1px solid #f0f0f0;
    }

    .product-image-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .action-buttons {
        width: 100%;
        max-width: 300px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-dark-blue {
        background-color: #101a32;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        transition: 0.3s;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-dark-blue:hover {
        background-color: #1a2b50;
        color: #fff;
        text-decoration: none;
    }

    .btn-icon-simple {
        background: transparent;
        border: 1px solid #e0e0e0;
        color: #555;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .btn-icon-simple:hover {
        background: #101a32;
        color: #fff;
        border-color: #101a32;
    }

    .badge-code {
        font-family: monospace;
        color: #101a32;
        font-weight: 600;
        background: #f6f9fc;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .bg-success-soft {
        background: #e0f2f1;
        color: #00695c !important;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
    }

    .bg-warning-soft {
        background: #fff8e1;
        color: #ff8f00 !important;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
    }

    @media (max-width: 992px) {
        .left-panel {
            padding-right: 0;
            border-right: none;
            margin-bottom: 40px;
            border-bottom: 1px solid #eee;
            padding-bottom: 40px;
        }

        .right-panel {
            padding-left: 0;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>