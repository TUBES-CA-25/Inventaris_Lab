<?php
// app/views/Beranda/Laboran.php
?>

<div class="content">
    <div class="content-beranda">
        <div class="header-section mb-4">
            <h1 class="h3 font-weight-bold">Dashboard Laboran</h1>
            <p class="text-muted">Pantau grafik peminjaman dan kelola antrean validasi.</p>
        </div>

        <!-- Quick Stats -->
        <div class="stats-grid mb-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Barang Terdaftar</span>
                    <span class="stat-value"><?= $data['total_barang']; ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Barang Rusak</span>
                    <span class="stat-value"><?= $data['total_damaged'] ?? 0; ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Merek Barang</span>
                    <span class="stat-value"><?= $data['total_brands']; ?></span>
                </div>
            </div>
        </div>

        <!-- Charts Section (Reusing Filter logic from index) -->
        <div class="dashboard-card">
            <div class="row align-items-center g-3 mb-4">
                <div class="col-auto">
                    <h5 class="mb-0 font-weight-bold" style="color: var(--primary);">Filter Grafik</h5>
                </div>
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
                                <option value="<?= $k + 1; ?>" <?= (date('m') == $k + 1) ? 'selected' : ''; ?>><?= $v; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn-filter-action" onclick="updateCharts()">
                        <i class="fas fa-sync-alt mr-2"></i> Tampilkan
                    </button>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <h6 class="text-center mb-2">Peminjaman Keseluruhan</h6>
                        <canvas id="chartPeminjaman"></canvas>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <h6 class="text-center mb-2">Pengembalian Keseluruhan</h6>
                        <canvas id="chartPengembalian"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Row -->
        <div class="row g-4">
            <!-- Pending Peminjaman -->
            <div class="col-12 col-lg-6">
                <div class="dashboard-card" style="height: 100%;">
                    <div class="table-title">
                        <div class="icon-box icon-pending"><i class="fas fa-file-contract"></i></div>
                        Peminjaman Perlu Diproses
                    </div>
                    <div class="table-responsive">
                        <table class="laboran-table">
                            <thead>
                                <tr>
                                    <th>Peminjam</th>
                                    <th>Kegiatan</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['pending_loans'])): ?>
                                    <tr>
                                        <td colspan="4" class="empty-state">Tidak ada antrean validasi peminjaman.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['pending_loans'] as $loan): ?>
                                        <tr>
                                            <td><strong><?= $loan['peminjam']; ?></strong></td>
                                            <td><?= $loan['judul_kegiatan']; ?></td>
                                            <td><?= date('d/m/Y', strtotime($loan['tanggal_pengajuan'])); ?></td>
                                            <td class="text-center">
                                                <a href="<?= BASEURL; ?>ValidasiPeminjaman/detail/<?= IdObfuscator::encode($loan['id_peminjaman']); ?>"
                                                    class="btn-action btn-process">Detail</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Verified Pengembalian -->
            <div class="col-12 col-lg-6">
                <div class="dashboard-card" style="height: 100%;">
                    <div class="table-title">
                        <div class="icon-box icon-verified"><i class="fas fa-check-double"></i></div>
                        Pengembalian Terverifikasi
                    </div>
                    <div class="table-responsive">
                        <table class="laboran-table">
                            <thead>
                                <tr>
                                    <th>Peminjam</th>
                                    <th>Kegiatan</th>
                                    <th>Tgl Kembali</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['verified_returns'])): ?>
                                    <tr>
                                        <td colspan="4" class="empty-state">Tidak ada pengembalian yang baru diverifikasi.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['verified_returns'] as $ret): ?>
                                        <tr>
                                            <td><strong><?= $ret['peminjam']; ?></strong></td>
                                            <td><?= $ret['judul_kegiatan']; ?></td>
                                            <td><?= date('d/m/Y', strtotime($ret['tgl_pengembalian_aktual'])); ?></td>
                                            <td class="text-center">
                                                <a href="<?= BASEURL; ?>ValidasiPeminjaman/detail/<?= IdObfuscator::encode($ret['id_peminjaman']); ?>"
                                                    class="btn-action btn-finalize">Finalasi</a>
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
    </div>
</div>

<!-- Extra row for status items if needed later -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdowns = document.querySelectorAll('.custom-select-wrapper');

        dropdowns.forEach(wrapper => {
            const trigger = wrapper.querySelector('.custom-select-trigger');
            const options = wrapper.querySelectorAll('.custom-option');
            const hiddenSelect = wrapper.querySelector('select');
            const label = wrapper.querySelector('span');

            // Toggle dropdown
            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                // Close other dropdowns
                dropdowns.forEach(dw => {
                    if (dw !== wrapper) dw.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });

            // Select option
            options.forEach(option => {
                option.addEventListener('click', function () {
                    const value = this.getAttribute('data-value');
                    const text = this.textContent;

                    // Update label and hidden select
                    label.textContent = text;
                    hiddenSelect.value = value;

                    // Update selected class
                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');

                    // Close dropdown
                    wrapper.classList.remove('open');

                    // Trigger change event on hidden select
                    const event = new Event('change', { bubbles: true });
                    hiddenSelect.dispatchEvent(event);

                    // If it's the mode selector, toggle year/month visibility
                    if (hiddenSelect.id === 'filterMode') {
                        const bulanWrapper = document.getElementById('filterBulanWrapper');
                        const tahunWrapper = document.getElementById('filterTahunWrapper');

                        if (bulanWrapper) {
                            if (value === 'harian') bulanWrapper.classList.remove('d-none');
                            else bulanWrapper.classList.add('d-none');
                        }

                        if (tahunWrapper) {
                            if (value === 'tahunan') tahunWrapper.classList.add('d-none');
                            else tahunWrapper.classList.remove('d-none');
                        }
                    }
                });
            });
        });

        // Close on click outside
        document.addEventListener('click', function () {
            dropdowns.forEach(dw => dw.classList.remove('open'));
        });
    });
</script>