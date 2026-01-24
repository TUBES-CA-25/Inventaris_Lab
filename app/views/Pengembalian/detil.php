<?php
if (!isset($_SESSION['login']) && !in_array($_SESSION['id_role'], ['3', '4'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<style>
    :root {
        --navy-primary: #0d1b3e;
        --navy-secondary: #1a2d5a;
        --navy-light: #2a3f6f;
        --accent-gold: #f39c12;
        --accent-orange: #e67e22;
    }

    .page-wrapper {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 20px 0;
    }

    /* Custom Navy Theme */
    .bg-navy-gradient {
        background: linear-gradient(135deg, var(--navy-primary) 0%, var(--navy-secondary) 100%);
    }

    .text-navy {
        color: var(--navy-primary) !important;
    }

    .border-navy {
        border-color: var(--navy-primary) !important;
    }

    .btn-navy {
        background: var(--navy-primary);
        color: white;
        border: none;
    }

    .btn-navy:hover {
        background: var(--navy-secondary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 27, 62, 0.3);
    }

    .btn-outline-navy {
        border: 2px solid var(--navy-primary);
        color: var(--navy-primary);
        background: transparent;
    }

    .btn-outline-navy:hover {
        background: var(--navy-primary);
        color: white;
    }

    /* Card Styling */
    .stat-card {
        border-left: 4px solid var(--navy-primary);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-card.active {
        border-left-color: var(--accent-gold);
        background: linear-gradient(145deg, #fff9e6 0%, #ffffff 100%);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon.navy {
        background: linear-gradient(135deg, var(--navy-primary), var(--navy-secondary));
        color: white;
    }

    .stat-icon.gold {
        background: linear-gradient(135deg, var(--accent-gold), var(--accent-orange));
        color: white;
    }

    /* Table Custom */
    .table-navy thead {
        background: var(--navy-primary);
        color: white;
    }

    .table-navy thead th {
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem;
    }

    .table-navy tbody tr {
        transition: all 0.2s ease;
    }

    .table-navy tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-baik {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .badge-rusak {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .badge-hilang {
        background: #e2e3e5;
        color: #383d41;
        border: 1px solid #d6d8db;
    }

    .badge-pinjam {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    /* Search Box */
    .search-box-custom {
        position: relative;
    }

    .search-box-custom .form-control {
        padding-left: 2.5rem;
        border-radius: 50px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .search-box-custom .form-control:focus {
        border-color: var(--navy-primary);
        box-shadow: 0 0 0 0.2rem rgba(13, 27, 62, 0.1);
    }

    .search-box-custom .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    /* Info Box */
    .info-box {
        background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 2rem;
    }

    .petugas-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--navy-primary), var(--navy-secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(13, 27, 62, 0.2);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }

        .table-responsive {
            border-radius: 8px;
        }

        .info-box {
            padding: 1.5rem;
        }

        .petugas-avatar {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
    }
</style>

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
                            <i class="fas fa-boxes me-2" style="color: var(--accent-gold);"></i>
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

        <!-- Petugas Info -->
        <?php if (!empty($data['detail']['id_pengembalian'])): ?>
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <div class="info-box">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <p class="text-uppercase text-muted mb-3 fw-semibold small">
                                    <i class="fas fa-info-circle me-1"></i> Informasi Validasi
                                </p>
                                <div class="d-flex align-items-center">
                                    <div class="petugas-avatar me-3">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 small">Diverifikasi oleh:</p>
                                        <h5 class="mb-0 text-navy fw-bold"><?= htmlspecialchars($data['detail']['nama_petugas'] ?? 'Petugas'); ?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="text-muted mb-2 small">Tanggal Pengembalian Aktual:</p>
                                <h4 class="text-navy fw-bold mb-3">
                                    <i class="fas fa-calendar-check me-2" style="color: var(--accent-gold);"></i>
                                    <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? date('d F Y', strtotime($data['detail']['tgl_pengembalian_aktual'])) : '-'; ?>
                                </h4>
                                <?php if (!empty($data['detail']['bukti_foto'])): ?>
                                    <a href="<?= BASEURL; ?>public/<?= $data['detail']['bukti_foto']; ?>" target="_blank" class="btn btn-outline-navy btn-sm">
                                        <i class="fas fa-image me-1"></i> Lihat Bukti Foto
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($data['detail']['detail_masalah']) && $data['detail']['detail_masalah'] != '-'): ?>
                            <hr class="my-4">
                            <div class="alert alert-warning mb-0 d-flex align-items-start">
                                <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                                <div>
                                    <strong>Catatan Masalah:</strong><br>
                                    <?= htmlspecialchars($data['detail']['detail_masalah']); ?>
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