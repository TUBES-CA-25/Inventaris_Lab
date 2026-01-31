<?php
if (!isset($_SESSION['login']) && !in_array($_SESSION['id_role'], ['3', '4'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<meta name="base-url" content="<?= BASEURL; ?>">
<link rel="stylesheet" href="<?= BASEURL; ?>css/pengembalianDetil.css">

<div class="content">
    <div class="container-fluid">

        <!-- Header Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body bg-navy-gradient text-white p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-1 opacity-75">
                            <i class="fas fa-hashtag"></i> ID Transaksi: <?= $data['detail']['id_peminjaman']; ?>
                        </p>
                        <h3 class="mb-0 fw-bold">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Detail & Status Pengembalian
                        </h3>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="<?= BASEURL; ?>Pengembalian" class="btn btn-light btn-lg">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon navy me-3">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase mb-1 small fw-semibold">Judul Kegiatan</p>
                                <h6 class="mb-0 text-navy fw-bold"><?= htmlspecialchars($data['detail']['judul_kegiatan'] ?? '-'); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon navy me-3">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase mb-1 small fw-semibold">Peminjam</p>
                                <h6 class="mb-0 text-navy fw-bold"><?= htmlspecialchars($data['detail']['nama_peminjam'] ?? '-'); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon navy me-3">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase mb-1 small fw-semibold">Tanggal Pinjam</p>
                                <h6 class="mb-0 text-navy fw-bold"><?= date('d M Y', strtotime($data['detail']['tanggal_peminjaman'])); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card shadow-sm border-0 h-100 <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'active' : ''; ?>">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'gold' : 'navy'; ?> me-3">
                                <i class="fas <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'fa-check-circle' : 'fa-clock'; ?>"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase mb-1 small fw-semibold">Status</p>
                                <h6 class="mb-0 fw-bold" style="color: <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'var(--accent-gold)' : 'var(--navy-primary)'; ?>">
                                    <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'Selesai' : 'Berjalan'; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 text-navy fw-bold">
                            <i class="fas fa-boxes me-2 icon-gold"></i>
                            Rincian Barang & Kondisi
                        </h5>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <div class="search-box-custom">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="detailSearch" class="form-control" placeholder="Cari nama atau kode barang...">
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
                                <th width="15%">Kode Barang</th>
                                <th width="25%">Nama Barang</th>
                                <th width="10%" class="text-center">Jumlah</th>
                                <th width="15%">Kondisi Akhir</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($data['items_kembali'])):
                                $no = 1;
                                foreach ($data['items_kembali'] as $item):
                                    // 1. Logic Kondisi
                                    // Jika kondisi_barang kosong (belum dikembalikan), set 'Dipinjam'
                                    $kondisi = !empty($item['kondisi_barang']) ? $item['kondisi_barang'] : 'Dipinjam';

                                    // 2. Logic Jumlah
                                    // Jika jumlah_kembali kosong, tampilkan jumlah_pinjam
                                    $jumlah = !empty($item['jumlah_kembali']) ? $item['jumlah_kembali'] : $item['jumlah_pinjam'];

                                    // 3. Styling Badge
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
                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                        <td>
                                            <code class="text-muted"><?= htmlspecialchars($item['kode_barang'] ?? '-'); ?></code>
                                        </td>
                                        <td class="fw-bold text-dark"><?= htmlspecialchars($item['nama_barang']); ?></td>
                                        <td class="text-center">
                                            <span class="badge"><?= htmlspecialchars($jumlah); ?></span>
                                        </td>
                                        <td>
                                            <span class="status-badge text-black <?= $statusClass ?>">
                                                <i class="fas <?= $icon ?>"></i>
                                                <?= ucfirst($kondisi); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($item['keterangan_kondisi']) && $item['keterangan_kondisi'] != '-'): ?>
                                                <small><?= htmlspecialchars($item['keterangan_kondisi']); ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if (!empty($data['detail']['id_pengembalian'])): ?>
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <div class="info-box">
                        <div class="row">

                            <div class="col-md-7 mb-4 mb-md-0">
                                <p class="text-uppercase text-muted mb-3 fw-semibold small">
                                    <i class="fas fa-history me-1"></i> Riwayat Pemeriksaan Asisten
                                </p>

                                <?php if (!empty($data['detail']['log_history'])): ?>
                                    <div class="d-flex flex-column gap-3">
                                        <?php
                                        // Pecah string history
                                        $logs = explode('||', $data['detail']['log_history']);
                                        foreach ($logs as $log):
                                            // Format: "25 January 2026 10:00 - Huzain"
                                            // Kita pisahkan Waktu dan Nama agar bisa di-styling
                                            $parts = explode(' - ', $log);
                                            $waktu = $parts[0] ?? '-';
                                            $nama  = $parts[1] ?? 'Asisten';
                                        ?>
                                            <div class="d-flex align-items-center p-2 rounded petugas-info-card">
                                                <div class="petugas-avatar sm">
                                                    <i class="fas fa-user-check"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 text-navy fw-bold small"><?= htmlspecialchars($nama); ?></h6>
                                                    <small class="text-muted date-info">
                                                        <i class="fas fa-clock me-1"></i> <?= $waktu; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted small font-italic">Belum ada riwayat pemeriksaan.</div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-5 text-md-end border-start-md ps-md-4">
                                <div class="mb-4">
                                    <p class="text-muted mb-1 small">Status Terakhir:</p>
                                    <h5 class="text-navy fw-bold">
                                        <?= !empty($data['detail']['status_pengembalian']) ? $data['detail']['status_pengembalian'] : '-'; ?>
                                    </h5>
                                </div>

                                <div class="mb-3">
                                    <p class="text-muted mb-1 small">Tanggal Pengembalian:</p>
                                    <h5 class="fw-bold mb-0 text-dark">
                                        <i class="fas fa-calendar-day me-2 icon-gold"></i>
                                        <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? date('d F Y', strtotime($data['detail']['tgl_pengembalian_aktual'])) : '-'; ?>
                                    </h5>
                                </div>

                                <?php if (!empty($data['detail']['bukti_foto'])): ?>
                                    <div class="mt-3">
                                        <a href="<?= BASEURL; ?>public/<?= $data['detail']['bukti_foto']; ?>" target="_blank" class="btn btn-outline-navy btn-sm w-100">
                                            <i class="fas fa-image me-1"></i> Lihat Bukti Foto
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($data['detail']['detail_masalah']) && $data['detail']['detail_masalah'] != '-'): ?>
                            <hr class="my-4">
                            <div class="alert alert-warning mb-0 d-flex align-items-start border-0 shadow-sm">
                                <i class="fas fa-exclamation-triangle me-3 mt-1 text-warning"></i>
                                <div>
                                    <strong class="text-dark">Catatan Masalah:</strong><br>
                                    <span class="text-dark opacity-75"><?= htmlspecialchars($data['detail']['detail_masalah']); ?></span>
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
                    if (row.cells.length === 1) {
                        row.style.display = 'none';
                        return;
                    }

                    const kode = row.cells[1]?.textContent.toLowerCase() || '';
                    const nama = row.cells[2]?.textContent.toLowerCase() || '';

                    if (nama.indexOf(filter) > -1 || kode.indexOf(filter) > -1) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show no result message
                const tbody = table.querySelector('tbody');
                let noResultRow = tbody.querySelector('.no-result-row');

                if (visibleCount === 0 && filter !== '') {
                    if (!noResultRow) {
                        noResultRow = tbody.insertRow();
                        noResultRow.className = 'no-result-row';
                        noResultRow.innerHTML = `
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada hasil untuk "<strong>${filter}</strong>"</p>
                        </td>
                    `;
                    }
                } else {
                    if (noResultRow) {
                        noResultRow.remove();
                    }
                }
            });

            // Clear search on ESC
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    searchInput.dispatchEvent(new Event('keyup'));
                }
            });
        }
    });
</script>