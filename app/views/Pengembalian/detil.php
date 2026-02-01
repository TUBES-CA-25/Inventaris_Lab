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
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
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
        border-radius: 8px;
        font-weight: 600;
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
        border-radius: 12px;
        background: white;
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

    /* --- [PERBAIKAN UTAMA: TABLE STYLING] --- */
    .table-responsive {
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        background: white;
        overflow: hidden;
    }

    .table-navy {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-navy thead {
        background: var(--navy-primary);
        color: white;
    }

    .table-navy thead th {
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        /* Padding header dibuat lega */
        padding: 1.2rem 1rem;
        vertical-align: middle;
    }

    .table-navy tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f2f5;
    }

    .table-navy tbody td {
        /* Padding body disamakan dengan header agar simetris */
        padding: 1.2rem 1rem;
        /* Rata tengah vertikal agar teks tidak menggantung */
        vertical-align: middle;
        border-bottom: 1px solid #f0f2f5;
        font-size: 0.95rem;
    }

    .table-navy tbody tr:last-child td {
        border-bottom: none;
    }

    .table-navy tbody tr:hover {
        background-color: #f8faff;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .badge-baik {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .badge-rusak {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .badge-hilang {
        background: #f3f4f6;
        color: #1f2937;
        border: 1px solid #e5e7eb;
    }

    .badge-pinjam {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
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
        height: 45px;
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

    /* Info Box & Log */
    .info-box {
        background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 2rem;
    }

    .petugas-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--navy-primary), var(--navy-secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 12px rgba(13, 27, 62, 0.2);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }

        .info-box {
            padding: 1.5rem;
        }
    }
</style>

<div class="content">
    <div class="container-fluid">

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden;">
            <div class="card-body bg-navy-gradient text-white p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-1 opacity-75 fw-medium">
                            <i class="fas fa-hashtag me-1"></i> ID Transaksi: <?= $data['detail']['id_peminjaman']; ?>
                        </p>
                        <h3 class="mb-0 fw-bold">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Detail & Status Pengembalian
                        </h3>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="<?= BASEURL; ?>Pengembalian" class="btn btn-light text-navy fw-bold px-4 py-2" style="border-radius: 50px;">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
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
                                <h6 class="mb-0 text-navy fw-bold" style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; line-height: 1.4;">
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
                                    <h6 class="mb-0 text-navy fw-bold" style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; line-height: 1.4;">
                                        <?= htmlspecialchars($data['detail']['nama_peminjam'] ?? '-'); ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card shadow-sm border-0 h-100 <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'active' : ''; ?>">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'gold' : 'navy'; ?> me-3">
                                <i class="fas <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'fa-check-circle' : 'fa-clock'; ?>"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase mb-1 small fw-bold">Status</p>
                                <h6 class="mb-0 fw-bold" style="color: <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? 'var(--accent-gold)' : 'var(--navy-primary)'; ?>">
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
                                    // Logic Kondisi
                                    $kondisi = !empty($item['kondisi_barang']) ? $item['kondisi_barang'] : 'Dipinjam';

                                    // Logic Jumlah
                                    $jumlah = !empty($item['jumlah_kembali']) ? $item['jumlah_kembali'] : $item['jumlah_pinjam'];

                                    // Logic Badge Warna
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
                                                <code class="text-navy fw-bold" style="background-color: #eef2ff; padding: 6px 10px; border-radius: 6px; border: 1px dashed #a5b4fc; display: inline-block;">
                                                    <?= htmlspecialchars($item['kode_barang'] . '/' . $item['jumlah_total'] . '/' . $item['urutan_unit']); ?>
                                                </code>
                                            <?php else: ?>
                                                <code class="text-muted"><?= htmlspecialchars($item['kode_barang'] ?? '-'); ?></code>
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
                                                <span class="text-dark"><i class="fas fa-info-circle me-1 text-muted"></i> <?= htmlspecialchars($item['keterangan_kondisi']); ?></span>
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
                                Status Terakhir: <?= !empty($data['detail']['status_pengembalian']) ? $data['detail']['status_pengembalian'] : '-'; ?>
                            </span>
                        </div>

                        <?php if (!empty($data['logs'])): ?>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach ($data['logs'] as $log): ?>
                                    <div class="d-flex align-items-center justify-content-between p-3 rounded bg-white shadow-sm border border-light">
                                        
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
                                                   title="Lihat Bukti Foto"
                                                   style="border-radius: 50px;">
                                                   <i class="fas fa-image"></i>
                                                   <span class="d-none d-sm-inline" style="font-size: 0.8rem;">Lihat Bukti</span>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small font-italic ms-2" style="font-size: 0.8rem; opacity: 0.6;">
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
                            <div class="alert alert-warning mb-0 d-flex align-items-center border-0 shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-exclamation-triangle me-3 text-warning" style="font-size: 1.5rem;"></i>
                                <div>
                                    <strong class="text-dark d-block mb-1">Catatan Masalah Terakhir:</strong>
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
                    // Skip jika baris 'no result'
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

                // Show no result message
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