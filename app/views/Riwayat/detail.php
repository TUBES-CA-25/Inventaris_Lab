<?php
if (!isset($_SESSION['login'])) {
    header("Location: " . BASEURL . "Login");
    exit;
}

// 1. Helper untuk status & Variabel Cek Penolakan
$st = strtolower($data['info_peminjaman']['status']);
$statusClass = 'status-info';
$statusIcon = 'fa-clock';

// Cek apakah statusnya ditolak (baik peminjaman maupun pengembalian)
$isRejected = in_array($st, ['tolak peminjaman', 'tolak pengembalian', 'ditolak']);

if (in_array($st, ['disetujui', 'diterima'])) {
    $statusClass = 'status-success';
    $statusIcon = 'fa-check-circle';
} elseif ($isRejected) { 
    $statusClass = 'status-danger';
    $statusIcon = 'fa-times-circle';
} elseif (in_array($st, ['melengkapi surat', 'melengkapi'])) {
    $statusClass = 'status-warning';
    $statusIcon = 'fa-file-signature';
}
?>

<div class="container-fluid p-4">
    <div class="page-header">
        <div>
            <h2 class="page-title">Detail Peminjaman</h2>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="<?= BASEURL ?>Riwayat/index" class="btn-modern btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            
            <?php if (!$isRejected): ?>
            <a href="<?= BASEURL ?>Riwayat/cetakPdf/<?= IdObfuscator::encode($data['info_peminjaman']['id_peminjaman']) ?>"
                target="_blank" class="btn-modern btn-pdf">
                <i class="fas fa-file-pdf"></i> Cetak Bukti
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-clean mb-4">
        <div class="status-banner">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-info-circle fa-lg"></i>
                <div>
                    <h6 class="mb-0 text-white">Status Pengajuan</h6>
                    <small class="text-white-75">Terakhir diperbarui <?= date('d M Y H:i') ?></small>
                </div>
            </div>

            <span class="status-pill <?= $statusClass ?>">
                <i class="fas <?= $statusIcon ?>"></i>
                <?= ucwords($data['info_peminjaman']['status']) ?>
            </span>
        </div>

        <?php if ($isRejected): ?>
            <div class="mx-4 mt-4">
                <div class="alert alert-danger border-0 shadow-sm" role="alert" style="background-color: #ffeef0; color: #e74a3b;">
                    <h5 class="alert-heading fw-bold mb-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>Alasan Penolakan
                    </h5>
                    <p class="mb-0" style="font-size: 1rem; color: #333;">
                        <?= htmlspecialchars($data['info_peminjaman']['keterangan_tolak'] ?? 'Tidak ada catatan alasan penolakan.') ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>
        <div class="card-body-clean">
            <div class="detail-cards-grid">

                <div class="detail-card">
                    <div class="detail-card-label">Judul Kegiatan</div>
                    <div class="detail-card-value">
                        <?= htmlspecialchars($data['info_peminjaman']['judul_kegiatan'] ?? '-') ?>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-card-label">Peminjam</div>
                    <div class="detail-card-value">
                        <?= htmlspecialchars($data['info_peminjaman']['nama_peminjam'] ?? '-') ?>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-card-label">Tanggal Pengajuan</div>
                    <div class="detail-card-value">
                        <?= date('d M Y', strtotime($data['info_peminjaman']['tanggal_pengajuan'] ?? 'now')) ?>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-card-label">Durasi Peminjaman</div>
                    <?php
                    $start = new DateTime($data['info_peminjaman']['tanggal_peminjaman'] ?? 'now');
                    $end   = new DateTime($data['info_peminjaman']['tanggal_pengembalian'] ?? 'now');
                    $diff  = $start->diff($end);
                    ?>
                    <div class="detail-card-value duration-highlight">
                        <?= $diff->days ?> Hari
                    </div>
                    <div class="detail-card-range">
                        <small class="text-muted">
                            <?= date('d/m/Y', $start->getTimestamp()) ?> →
                            <?= date('d/m/Y', $end->getTimestamp()) ?>
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card-clean">
        <div class="item-header-navy">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 text-white">
                    <i class="fas fa-box-open me-2"></i>
                    Item yang Dipinjam
                </h6>
                <span class="badge bg-white text-dark border-0 px-3 py-2 fw-medium">
                    Total: <?= count($data['detail_barang'] ?? []) ?> item
                </span>
            </div>
        </div>

        <div class="table-container">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">Foto</th>
                        <th width="20%">Nama Barang</th>
                        <th width="20%">Spesifikasi</th>
                        <th width="15%">Kode</th>
                        <th width="10%" class="text-center">Jml</th>
                        <th width="15%">Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['detail_barang'])): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-2x mb-3 d-block opacity-50"></i>
                                Tidak ada data barang
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($data['detail_barang'] as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <div class="img-zoom-container" onclick="showImageModal('<?= $item['foto_url_ready'] ?>', '<?= htmlspecialchars($item['nama_barang'] ?? '-') ?>')">
                                        <img src="<?= $item['foto_url_ready'] ?>"
                                            alt="Foto" class="item-thumb"
                                            onerror="this.onerror=null; this.src='<?= BASEURL; ?>img/foto-barang/default_tools.png';">
                                        <div class="zoom-overlay"><i class="fas fa-search-plus"></i></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($item['nama_barang'] ?? '-') ?></div>
                                    <small class="text-muted">Inventaris Lab</small>
                                </td>
                                <td>
                                    <span class="text-dark">
                                        <?= htmlspecialchars($item['spesifikasi_barang']) ?>
                                    </span>
                                </td>
                                <td><span class="item-code"><?= htmlspecialchars($item['kode_barang'] ?? '-') ?></span></td>
                                <td class="text-center"><?= $item['jumlah'] ?? 0 ?></td>
                                <td>
                                    <span class="badge bg-light border text-dark px-3 py-2">
                                        <?= htmlspecialchars($item['kondisi'] ?? 'Baik') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="imageModal" class="modal-overlay" onclick="closeImageModal()">
    <span class="close-btn">&times;</span>
    <img id="fullImage" class="modal-content-img" src="">
    <div id="imageCaption" class="image-caption"></div>
</div>

<script src="<?= BASEURL; ?>js/riwayat.js"></script>