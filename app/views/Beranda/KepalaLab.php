<?php
// app/views/Beranda/KepalaLab.php
?>

<div class="content">
    <div class="content-beranda">
        <div class="header-section mb-4">
            <h1 class="h3 font-weight-bold">Dashboard Kepala Lab</h1>
            <p class="text-muted">Pantau grafik peminjaman dan pengadaan inventaris laboratorium.</p>
        </div>

        <!-- Quick Stats -->
        <div class="stats-grid mb-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total Barang</span>
                    <span class="stat-value"><?= $data['total_items']; ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: var(--primary);">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Peminjaman Perlu ACC</span>
                    <span class="stat-value"><?= $data['pending_loans_count']; ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: var(--primary);">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total Peminjaman</span>
                    <span class="stat-value">
                        <?= $data['weekly_loans_count']; ?>
                    </span>
                    <small class="text-muted" style="font-size: 0.7rem; margin-top: -5px;">Minggu Ini</small>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="dashboard-card mb-4">
            <div class="row align-items-center g-3 mb-4">
                <div class="col-auto">
                    <h5 class="mb-0 font-weight-bold" style="color: var(--primary);">Filter Statistik</h5>
                </div>

                <!-- Filter Mode -->
                <div class="col-auto">
                    <div class="custom-select-wrapper" id="wrapperFilterMode">
                        <div class="custom-select-trigger">
                            <span id="labelFilterMode">Bulanan</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="custom-options">
                            <div class="custom-option" data-value="harian">Harian</div>
                            <div class="custom-option selected" data-value="bulanan">Bulanan</div>
                            <div class="custom-option" data-value="tahunan">Tahunan</div>
                        </div>
                        <select id="filterMode" class="d-none">
                            <option value="harian">Harian</option>
                            <option value="bulanan" selected>Bulanan</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Tahun -->
                <div class="col-auto" id="filterTahunWrapper">
                    <div class="custom-select-wrapper" id="wrapperFilterTahun">
                        <div class="custom-select-trigger">
                            <span id="labelFilterTahun"><?= date('Y'); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="custom-options">
                            <?php
                            $currentYear = date('Y');
                            for ($i = $currentYear; $i >= $currentYear - 4; $i--): ?>
                                <div class="custom-option <?= ($i == $currentYear) ? 'selected' : ''; ?>"
                                    data-value="<?= $i; ?>"><?= $i; ?></div>
                            <?php endfor; ?>
                        </div>
                        <select id="filterTahun" class="d-none">
                            <?php for ($i = $currentYear; $i >= $currentYear - 4; $i--): ?>
                                <option value="<?= $i; ?>"><?= $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <!-- Filter Bulan -->
                <div class="col-auto d-none" id="filterBulanWrapper">
                    <div class="custom-select-wrapper" id="wrapperFilterBulan">
                        <div class="custom-select-trigger">
                            <span
                                id="labelFilterBulan"><?= ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"][date('m') - 1]; ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="custom-options">
                            <?php
                            $months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                            foreach ($months as $k => $v): ?>
                                <div class="custom-option <?= (date('m') == $k + 1) ? 'selected' : ''; ?>"
                                    data-value="<?= $k + 1; ?>"><?= $v; ?></div>
                            <?php endforeach; ?>
                        </div>
                        <select id="filterBulan" class="d-none">
                            <?php foreach ($months as $k => $v): ?>
                                <option value="<?= $k + 1; ?>" <?= (date('m') == $k + 1) ? 'selected' : ''; ?>>
                                    <?= $v; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-auto">
                    <button class="btn-back" onclick="refreshPremiumCharts()">
                        Tampilkan
                    </button>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-7">
                    <div class="chart-container-premium">
                        <h6 class="font-weight-bold mb-3 text-center"><i class="fas fa-chart-line mr-2"></i>
                            Peminjaman & Pengembalian</h6>
                        <div style="height: 350px;">
                            <canvas id="chartCombinedLoan"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="chart-container-premium">
                        <h6 class="font-weight-bold mb-3 text-center"><i class="fas fa-plus-circle mr-2"></i>Pengadaan
                            Barang Baru</h6>
                        <div style="height: 350px;">
                            <canvas id="chartNewItems"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Table Section (Full Width Below) -->
        <div class="dashboard-card">
            <div class="table-title d-flex justify-content-between align-items-center mb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box"><i class="fas fa-file-contract"></i></div>
                    <span>Daftar Peminjaman Perlu Validasi</span>
                </div>
                <a href="<?= BASEURL; ?>ValidasiPeminjaman" class="btn-back no-loader text-decoration-none"
                    style="font-size: 0.75rem; padding: 0.4rem 1rem;">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="table-responsive mt-3">
                <table class="laboran-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Judul Kegiatan</th>
                            <th>Status saat ini</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['pending_loans'])): ?>
                            <tr>
                                <td colspan="5" class="empty-state">Tidak ada antrean validasi peminjaman.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1;
                            foreach ($data['pending_loans'] as $loan): ?>
                                <tr>
                                    <td>
                                        <?= $no++; ?>
                                    </td>
                                    <td><strong><?= $loan['peminjam']; ?></strong></td>
                                    <td><?= $loan['judul_kegiatan']; ?></td>
                                    <td><span class="badge bg-warning text-dark">Menunggu ACC</span></td>
                                    <td class="text-center">
                                        <a href="<?= BASEURL; ?>ValidasiPeminjaman/detail/<?= IdObfuscator::encode($loan['id_peminjaman']); ?>"
                                            class="btn-action btn-process">Detail Validasi</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .chart-container-premium {
        background: #fff;
        padding: 1.5rem;
        border-radius: 16px;
        border: 1px solid #eef2f7;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        height: 100%;
    }

    /* Ensure consistency with Laboran colors */
    :root {
        --primary: #0c1740;
        --warning: #f59e0b;
        --info: #3b82f6;
    }
</style>

<!-- <script src="<?= BASEURL; ?>js/DashboardChartsPremium.js?v=<?= APP_VERSION; ?>"></script> -->