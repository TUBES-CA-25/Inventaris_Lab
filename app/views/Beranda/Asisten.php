<?php
// app/views/Beranda/Asisten.php
?>

<div class="content">
    <div class="content-beranda">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h1>Halo, <?= $data['profile']['nama_user']; ?>!</h1>
            <p>Sistem mencatat aktivitas Anda dengan baik hari ini. Tetap produktif!</p>
        </div>

        <div class="stats-grid">
            <?php if ($data['id_role'] == 3): ?>
                <!-- Korlab Stats -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Barang Terdaftar</span>
                        <span class="stat-value"><?= $data['total_items']; ?></span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Barang Rusak</span>
                        <span class="stat-value"><?= $data['total_damaged']; ?></span>
                    </div>
                </div>

            <?php else: ?>
                <!-- Assistant Stats -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Peminjaman Berlangsung</span>
                        <span class="stat-value"><?= $data['peminjaman_berlangsung']; ?></span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Melewati Batas</span>
                        <span class="stat-value"><?= $data['melewati_batas']; ?></span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Total Barang Rusak</span>
                        <span class="stat-value"><?= $data['total_damaged']; ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($data['id_role'] == 3): ?>
            <!-- Charts Section (Korlab Only) -->
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
                                    <option value="<?= $k + 1; ?>" <?= (date('m') == $k + 1) ? 'selected' : ''; ?>><?= $v; ?>
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
                            <h6 class="font-weight-bold mb-3 text-center"><i class="fas fa-chart-line mr-1"></i>Peminjaman &
                                Pengembalian</h6>
                            <div style="height: 300px;">
                                <canvas id="chartCombinedLoan"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5">
                        <div class="chart-container-premium">
                            <h6 class="font-weight-bold mb-3 text-center"><i class="fas fa-plus-circle mr-1"></i>Pengadaan
                                Barang Baru</h6>
                            <div style="height: 300px;">
                                <canvas id="chartNewItems"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <!-- Main Content: Damaged Goods Table -->
            <div class="main-content">
                <div class="card-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-exclamation-triangle"></i>
                            Daftar Barang Rusak
                        </h2>
                    </div>

                    <div class="table-responsive">
                        <table class="custom-table" id="damagedGoodsTable">
                            <thead>
                                <tr>
                                    <th>Unit / Code</th>
                                    <th>Nama Barang</th>
                                    <th>Spesifikasi</th>
                                    <th class="text-center">Kondisi</th>
                                </tr>
                            </thead>
                            <tbody id="damagedGoodsBody">
                                <?php if (empty($data['damaged_goods'])): ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">
                                                <i class="fas fa-check-circle fa-2x mb-3 text-success"></i>
                                                <p>Tidak ada barang rusak terdeteksi.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['damaged_goods'] as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="font-weight-bold"><?= $item['urutan_unit']; ?></div>
                                                <div class="small text-muted"><?= $item['kode_barang']; ?></div>
                                            </td>
                                            <td class="font-weight-bold"><?= $item['sub_barang']; ?></td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;">
                                                    <?= $item['spesifikasi_barang']; ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-rusak">
                                                    <?= $item['kondisi_barang']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Container -->
                    <div class="pagination-container" id="damagedPagination">
                        <?php if ($data['total_damaged'] > 5): ?>
                            <?php $totalPages = ceil($data['total_damaged'] / 5); ?>
                            <button class="btn-page" disabled id="prevPage" onclick="changePage(currentPage - 1)">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span id="pageNumbers" class="d-flex gap-1">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <button class="btn-page <?= ($i == 1) ? 'active' : ''; ?>" onclick="changePage(<?= $i; ?>)">
                                        <?= $i; ?>
                                    </button>
                                <?php endfor; ?>
                            </span>
                            <button class="btn-page" id="nextPage" onclick="changePage(currentPage + 1)" <?= ($totalPages <= 1) ? 'disabled' : ''; ?>>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Activity Log -->
            <div class="sidebar-content">
                <div class="card-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-history"></i>
                            <?= ($data['id_role'] == 3) ? 'Aktivitas Asisten' : 'Aktivitas Anda'; ?>
                        </h2>
                    </div>
                    <div class="activity-timeline">
                        <?php if (empty($data['activity_logs'])): ?>
                            <div class="empty-state">
                                <p>Belum ada aktivitas.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($data['activity_logs'] as $log): ?>
                                <div class="activity-item">
                                    <span class="activity-time">
                                        <?= date('d M Y, H:i', strtotime($log['created_at'])); ?>
                                    </span>
                                    <div class="activity-content">
                                        <?php if ($data['id_role'] == 3): ?>
                                            <div class="font-weight-bold text-primary mb-1" style="font-size: 0.85rem;">
                                                <?= $log['nama_user']; ?>
                                            </div>
                                        <?php endif; ?>
                                        <span class="activity-type">
                                            <?= str_replace('_', ' ', $log['action_type']); ?>:
                                        </span>
                                        <?= $log['details']; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;

    function changePage(page) {
        if (page < 1) return;

        const formData = new FormData();
        formData.append('page', page);

        fetch('<?= BASEURL; ?>Beranda/getDamagedGoodsAjax', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(res => {
                currentPage = res.current_page;
                updateTable(res.damaged_goods);
                updatePagination(res.total_pages);
            });
    }

    function updateTable(items) {
        const body = document.getElementById('damagedGoodsBody');
        body.innerHTML = '';

        if (items.length === 0) {
            body.innerHTML = '<tr><td colspan="4" class="text-center py-4">Tidak ada barang rusak terdeteksi.</td></tr>';
            return;
        }

        items.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="font-weight-bold">${item.urutan_unit}</div>
                    <div class="small text-muted">${item.kode_barang}</div>
                </td>
                <td>${item.sub_barang}</td>
                <td><div class="text-truncate" style="max-width: 200px;">${item.spesifikasi_barang}</div></td>
                <td><span class="badge-rusak">${item.kondisi_barang}</span></td>
            `;
            body.appendChild(tr);
        });
    }

    function updatePagination(totalPages) {
        const pageNumbers = document.getElementById('pageNumbers');
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');

        if (!pageNumbers) return;

        pageNumbers.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.className = `btn-page ${i === currentPage ? 'active' : ''}`;
            btn.innerText = i;
            btn.onclick = () => changePage(i);
            pageNumbers.appendChild(btn);
        }

        prevBtn.disabled = (currentPage === 1);
        nextBtn.disabled = (currentPage === totalPages);
    }

</script>