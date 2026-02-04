<?php
if (!isset($_SESSION['login']) && !in_array($_SESSION['id_role'], ['3', '4'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="content">
    <div class="container-fluid">

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden;">
            <div class="card-body bg-navy-gradient text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-0  fw-bold">
                            <i class="fas fa-clipboard-check me-2 mr-1"></i>
                            Detail & Status Pengembalian
                        </h3>
                    </div>

                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="<?= BASEURL; ?>Pengembalian" class="btn btn-light text-navy fw-bold px-4 py-2"
                            style="border-radius: 50px;">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="flash">
                    <?php Flasher::flash(); ?>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon navy me-3">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase mb-1 small fw-bold">Judul Kegiatan</p>
                                <h6 class="mb-0 text-navy fw-bold"
                                    style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; line-height: 1.4;">
                                    <?= htmlspecialchars($data['detail']['judul_kegiatan'] ?? '-'); ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon navy me-3">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase mb-1 small fw-bold">Peminjam</p>
                                <h6 class="mb-0 text-navy fw-bold text-truncate">
                                    <?= htmlspecialchars($data['detail']['nama_peminjam'] ?? '-'); ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon navy me-3">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase mb-1 small fw-bold">Tanggal Pinjam</p>
                                <div class="flex-grow-1">
                                    <p class="text-muted text-uppercase mb-1 small fw-bold">Peminjam</p>
                                    <h6 class="mb-0 text-navy fw-bold"
                                        style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; line-height: 1.4;">
                                        <?= htmlspecialchars($data['detail']['nama_peminjam'] ?? '-'); ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div
                    class="card stat-card shadow-sm border-0 h-100 <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'active' : ''; ?>">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div
                                class="stat-icon <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'gold' : 'navy'; ?> me-3">
                                <i
                                    class="fas <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'fa-check-circle' : 'fa-clock'; ?>"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase mb-1 small fw-bold">Status</p>
                                <h6 class="mb-0 fw-bold"
                                    style="color: <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'var(--accent-gold)' : 'var(--navy-primary)'; ?>">
                                    <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'Selesai' : 'Berjalan'; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-4 px-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 text-navy fw-bold">
                            <i class="fas fa-boxes me-2" style="color: var(--accent-gold);"></i>
                            Rincian Barang & Kondisi
                        </h5>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <div class="search-box-custom">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="detailSearch" class="form-control"
                                placeholder="Cari nama atau kode barang...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-navy table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="18%">Kode Barang</th>
                                <th width="25%">Nama Barang</th>
                                <th width="10%" class="text-center">Jumlah</th>
                                <th width="15%" class="text-center">Kondisi Akhir</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($data['items_kembali'])):
                                $no = 1;
                                foreach ($data['items_kembali'] as $item):
                                    $kondisi = !empty($item['kondisi_barang']) ? $item['kondisi_barang'] : 'Dipinjam';
                                    $jumlah = !empty($item['jumlah_kembali']) ? $item['jumlah_kembali'] : $item['jumlah_pinjam'];

                                    $statusClass = 'badge-pinjam';
                                    $icon = 'fa-clock';

                                    if (strtolower($kondisi) == 'baik') {
                                        $statusClass = 'badge-baik';
                                        $icon = 'fa-check-circle';
                                    } elseif (stripos($kondisi, 'rusak') !== false) {
                                        $statusClass = 'badge-rusak';
                                        $icon = 'fa-exclamation-circle';
                                    } elseif (strtolower($kondisi) == 'hilang') {
                                        $statusClass = 'badge-hilang';
                                        $icon = 'fa-question-circle';
                                    }
                            ?>
                                    <tr>
                                        <td class="text-center fw-bold text-muted"><?= $no++; ?></td>

                                        <td>
                                            <?php if (!empty($item['urutan_unit'])): ?>
                                                <code class="text-navy fw-bold"
                                                    style="background-color: #eef2ff; padding: 6px 10px; border-radius: 6px; border: 1px dashed #a5b4fc; display: inline-block;">
                                                    <?= htmlspecialchars($item['kode_barang'] . '/' . $item['urutan_unit'] . '/' . $item['jumlah_total']); ?>
                                                </code>
                                            <?php else: ?>
                                                <code
                                                    class="text-muted"><?= htmlspecialchars($item['kode_barang'] ?? '-'); ?></code>
                                            <?php endif; ?>
                                        </td>

                                        <td class="fw-bold text-dark" style="font-size: 1rem;">
                                            <?= htmlspecialchars($item['nama_barang']); ?>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border px-3 py-2" style="font-size: 0.9rem;">
                                                <?= htmlspecialchars($jumlah); ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <span class="status-badge <?= $statusClass ?>">
                                                <i class="fas <?= $icon ?>"></i>
                                                <?= ucfirst($kondisi); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?php if (!empty($item['keterangan_kondisi']) && $item['keterangan_kondisi'] != '-'): ?>
                                                <span class="text-dark"><i class="fas fa-info-circle me-1 text-muted"></i>
                                                    <?= htmlspecialchars($item['keterangan_kondisi']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small font-italic">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3 text-gray-300"></i>
                                        <p class="mb-0">Tidak ada data barang yang ditemukan.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if (!empty($data['detail']['id_pengembalian'])): ?>
            <div class="card shadow-sm border-0 mt-4" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="info-box">

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <p class="text-uppercase text-muted mb-0 fw-bold small">
                                <i class="fas fa-history me-2"></i> Riwayat Pemeriksaan Asisten
                            </p>

                            <span class="badge bg-light text-navy border">
                                Status Terakhir:
                                <?= !empty($data['detail']['status_pengembalian']) ? $data['detail']['status_pengembalian'] : '-'; ?>
                            </span>
                        </div>

                        <?php if (!empty($data['logs'])): ?>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach ($data['logs'] as $log): ?>
                                    <div
                                        class="d-flex align-items-center justify-content-between p-3 rounded bg-white shadow-sm border border-light">

                                        <div class="d-flex align-items-center">
                                            <div class="petugas-avatar me-3" style="width: 40px; height: 40px; font-size: 16px;">
                                                <i class="fas fa-user-check"></i>
                                            </div>
                                            <h6 class="mb-0 text-navy fw-bold small">
                                                <?= htmlspecialchars($log['nama_user']); ?>
                                            </h6>
                                        </div>

                                        <div class="d-flex align-items-center">

                                            <small class="text-muted fw-medium me-4 d-none d-md-block">
                                                <i class="fas fa-clock me-1 text-warning"></i>
                                                <?= date('d M Y, H:i', strtotime($log['waktu_periksa'])); ?> WITA
                                            </small>

                                            <?php if (!empty($log['bukti_foto'])): ?>
                                                <a href="<?= BASEURL; ?>public/<?= $log['bukti_foto']; ?>" target="_blank"
                                                    class="btn btn-sm btn-outline-navy d-flex align-items-center gap-2 px-3"
                                                    title="Lihat Bukti Foto" style="border-radius: 50px;">
                                                    <i class="fas fa-image"></i>
                                                    <span class="d-none d-sm-inline" style="font-size: 0.8rem;">Lihat Bukti</span>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small font-italic ms-2"
                                                    style="font-size: 0.8rem; opacity: 0.6;">
                                                    Tanpa Foto
                                                </span>
                                            <?php endif; ?>

                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-muted small font-italic p-4 bg-light rounded border border-dashed text-center">
                                <i class="fas fa-info-circle me-1"></i> Belum ada riwayat pemeriksaan yang tercatat.
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($data['detail']['detail_masalah']) && $data['detail']['detail_masalah'] != '-'): ?>
                            <hr class="my-4">
                            <div class="alert alert-warning mb-0 d-flex align-items-center border-0 shadow-sm"
                                style="border-radius: 8px;">
                                <i class="fas fa-exclamation-triangle me-3 text-warning" style="font-size: 1.5rem;"></i>
                                <div>
                                    <strong class="text-dark d-block mb-1">Catatan Masalah Terakhir:</strong>
                                    <span
                                        class="text-dark opacity-75"><?= htmlspecialchars($data['detail']['detail_masalah']); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('detailSearch');
        const table = document.querySelector('.table-navy');

        if (searchInput && table) {
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');

                let visibleCount = 0;
                rows.forEach(row => {
                    if (row.classList.contains('no-result-row')) return;

                    const kode = row.cells[1]?.textContent.toLowerCase() || '';
                    const nama = row.cells[2]?.textContent.toLowerCase() || '';

                    if (nama.indexOf(filter) > -1 || kode.indexOf(filter) > -1) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const tbody = table.querySelector('tbody');
                let noResultRow = tbody.querySelector('.no-result-row');

                if (visibleCount === 0 && filter !== '') {
                    if (!noResultRow) {
                        noResultRow = tbody.insertRow();
                        noResultRow.className = 'no-result-row';
                        noResultRow.innerHTML = `
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-3x mb-3 text-gray-300"></i>
                            <p class="mb-0">Tidak ada hasil untuk "<strong>${filter}</strong>"</p>
                        </td>
                    `;
                    }
                } else {
                    if (noResultRow) {
                        noResultRow.remove();
                    }
                }
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    searchInput.dispatchEvent(new Event('keyup'));
                }
            });
        }
    });
</script>