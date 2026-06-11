<?php
// app/views/Beranda/Dosen.php
?>

<div class="content">
    <div class="content-beranda">
        <!-- Welcome Banner -->
        <div class="welcome-banner text-white" style="background: linear-gradient(135deg, #0c1740 0%, #1e293b 100%);">
            <h1>Halo,
                <?= $data['profile']['nama_user']; ?>!
            </h1>
            <p>Selamat datang di Dashboard Dosen. Pantau progres peminjaman mahasiswa bimbingan Anda di sini.</p>
        </div>

        <div class="stats-grid">
            <!-- Total Loans Overseen -->
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #0c1740; color: #ffffff;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total Peminjaman Siswa</span>
                    <span class="stat-value">
                        <?= $data['total_supervised_loans']; ?>
                    </span>
                </div>
            </div>

            <!-- Pending Validations -->
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #0c1740; color: #ffffff;">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Validasi Menunggu</span>
                    <span class="stat-value">
                        <?= $data['pending_validations']; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="dashboard-card mb-4 mt-4">
            <div class="row align-items-center g-3 mb-4">
                <div class="col-auto">
                    <h5 class="mb-0 font-weight-bold" style="color: var(--primary);">Statistik Peminjaman Mahasiswa</h5>
                </div>

                <!-- Filters -->
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

                <div class="col-auto" id="filterTahunWrapper">
                    <div class="custom-select-wrapper" id="wrapperFilterTahun">
                        <div class="custom-select-trigger">
                            <span id="labelFilterTahun">
                                <?= date('Y'); ?>
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="custom-options">
                            <?php
                            $currentYear = date('Y');
                            for ($i = $currentYear; $i >= $currentYear - 4; $i--): ?>
                                <div class="custom-option <?= ($i == $currentYear) ? 'selected' : ''; ?>"
                                    data-value="<?= $i; ?>">
                                    <?= $i; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <select id="filterTahun" class="d-none">
                            <?php for ($i = $currentYear; $i >= $currentYear - 4; $i--): ?>
                                <option value="<?= $i; ?>">
                                    <?= $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="col-auto d-none" id="filterBulanWrapper">
                    <div class="custom-select-wrapper" id="wrapperFilterBulan">
                        <div class="custom-select-trigger">
                            <span id="labelFilterBulan">
                                <?= ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"][date('m') - 1]; ?>
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="custom-options">
                            <?php
                            $months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                            foreach ($months as $k => $v): ?>
                                <div class="custom-option <?= (date('m') == $k + 1) ? 'selected' : ''; ?>"
                                    data-value="<?= $k + 1; ?>">
                                    <?= $v; ?>
                                </div>
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
                    <button class="btn btn-primary px-4" style="border-radius: 10px; height: 45px;"
                        onclick="refreshPremiumCharts()">
                        Update Grafik
                    </button>
                </div>
            </div>

            <div class="chart-container-premium">
                <div style="height: 350px;">
                    <canvas id="chartCombinedLoan"></canvas>
                </div>
            </div>
        </div>

        <div class="content-grid mt-4">
            <!-- Table Recent Student Requests -->
            <div class="main-content" style="flex: 1;">
                <div class="card-section h-100">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-user-clock text-warning"></i>
                            Validasi Mahasiswa Terbaru
                        </h2>
                    </div>

                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Judul Kegiatan</th>
                                    <th>Tgl Pengajuan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['recent_requests'])): ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state py-5">
                                                <i class="fas fa-check-circle fa-2x mb-3 text-success"></i>
                                                <p>Tidak ada permintaan validasi baru.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['recent_requests'] as $req): ?>
                                        <tr>
                                            <td class="font-weight-bold">
                                                <?= $req['mahasiswa']; ?>
                                            </td>
                                            <td>
                                                <?= $req['judul_kegiatan']; ?>
                                            </td>
                                            <td>
                                                <?= date('d M Y', strtotime($req['tanggal_pengajuan'])); ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= BASEURL; ?>ValidasiPeminjaman"
                                                    class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                                                    <i class="fas fa-eye"></i> Periksa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (!empty($data['recent_requests'])): ?>
                        <div class="text-right mt-3 text-end">
                            <a href="<?= BASEURL; ?>ValidasiPeminjaman"
                                class="text-primary font-weight-bold text-decoration-none">
                                Lihat Semua Permintaan <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Tip / Note -->
            <div class="sidebar-content" style="width: 320px;">
                <div class="card-section bg-light border-0" style="background-color: #f8fafc !important;">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-lightbulb text-warning"></i>
                            Tips Dosen
                        </h2>
                    </div>
                    <div class="p-2" style="font-size: 0.9rem; line-height: 1.6; color: #64748b;">
                        <p class="mb-3">Mahasiswa akan muncul di daftar validasi jika mereka memilih Anda sebagai
                            <b>Dosen Pembimbing</b> saat melakukan pengajuan Alat Khusus.
                        </p>
                        <p class="mb-0">Pastikan Anda memeriksa kelengkapan proposal atau tujuan kegiatan mahasiswa
                            sebelum memberikan tanda tangan digital/validasi.</p>
                    </div>
                    <div class="mt-4 p-3 rounded-lg" style="background-color: #e2e8f0; border-radius: 12px;">
                        <small class="text-muted d-block mb-1">Butuh bantuan?</small>
                        <a href="<?= BASEURL; ?>Kontak" class="btn btn-sm btn-secondary btn-block mt-1 w-100">Hubungi
                            Laboran</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js and Dashboard Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.baseUrl = '<?= BASEURL; ?>';
</script>
<script src="<?= BASEURL; ?>js/DashboardChartsPremium.js"></script>