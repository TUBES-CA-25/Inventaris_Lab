<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>



<div class="content">
    <div class="content-beranda">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3" style="padding: 20px 0;">
            <h3 class="fw-bold" style="color: #0d1b3e; font-size: 28px; margin: 0;">Pengembalian</h3>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Tombol Filter - mirip btn-custom-filter -->


                <!-- Search Box -->
                <div class="search-box position-relative" style="min-width: 320px;">
                    <i class="fa-solid fa-magnifying-glass position-absolute"
                        style="left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280;"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Cari Judul / Status..." style="
                padding-left: 38px;
                height: 38px;
                border-radius: 6px;
            ">
                </div>
            </div>
        </div>

        <button type="button" class="left-buttons btn-custom-filter" id="btnFilterToggle" onclick="toggleFilter()">
            <i class="fa-solid fa-filter me-1"></i> Filter
        </button>

        <div id="filterSection" class="card p-3 my-4"
            style="border-radius: 10px; display: none; border: 1px solid #e0e0e0; background: #fff;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 style="color: var(--primary-blue, #0d1b3e); margin: 0; font-weight: 600;">
                    <i class="fa-solid fa-filter me-2" style="font-size: 0.9rem;"></i> Filter Data
                </h6>
                <button type="button" id="btnReset" class="btn btn-sm btn-link text-danger p-0 text-decoration-none">
                    Reset Filter
                </button>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <label class="form-label small fw-bold text-muted mb-1">Status Pengembalian</label>
                    <select id="filterStatus" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="selesai periksa">Selesai Periksa</option>
                        <option value="periksa">Periksa</option>
                        <option value="periksa ulang">Periksa Ulang</option>
                        <option value="belum diperiksa">Belum Diperiksa</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-8">
                    <label class="form-label small fw-bold text-muted mb-1">Tanggal Pengembalian (Rentang)</label>
                    <div class="d-flex gap-2">
                        <input type="date" id="startDate" class="form-control form-control-sm">
                        <span class="align-self-center">s/d</span>
                        <input type="date" id="endDate" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="flash mb-3">
            <?php Flasher::flash(); ?>
        </div>

        <div class="table-responsive" style="border-radius: 10px; overflow: hidden;">
            <table class="table table-hover align-middle" id="pengembalianTable" style="margin-bottom: 0;">
                <thead style="background-color: #0d1b3e; color: white;">
                    <tr>
                        <th class="py-3 ps-3" style="font-weight: 500;">No</th>
                        <th style="font-weight: 500;">Judul kegiatan</th>
                        <th style="font-weight: 500;">Tgl pengajuan</th>
                        <th style="font-weight: 500;">Tgl mulai peminjaman</th>
                        <th style="font-weight: 500;">Tgl akhir peminjaman</th>
                        <th class="text-center" style="font-weight: 500;">Status</th>
                        <th class="text-center" style="font-weight: 500;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if (!empty($data['riwayat'])):
                        foreach ($data['riwayat'] as $r):
                            // Default status setup
                            $status_display = 'Belum Diperiksa';
                            $status_class = 'bg-secondary text-white';

                            if (!empty($r['status_pengembalian'])) {
                                $status_display = $r['status_pengembalian'];
                                switch ($r['status_pengembalian']) {
                                    case 'Selesai Periksa':
                                        $status_class = 'bg-success text-white';
                                        break;
                                    case 'Periksa':
                                        $status_class = 'bg-primary text-white';
                                        break;
                                    case 'Periksa Ulang':
                                        $status_class = 'bg-danger text-white';
                                        break;
                                    default:
                                        $status_class = 'bg-warning text-dark';
                                        break;
                                }
                            }
                            // Format Tanggal untuk JS Filtering (YYYY-MM-DD)
                            $data_tgl_kembali = date('Y-m-d', strtotime($r['tanggal_pengembalian']));
                            ?>
                            <tr class="data-row" data-status="<?= strtolower($status_display); ?>"
                                data-date="<?= $data_tgl_kembali; ?>"
                                style="cursor: pointer; transition: background-color 0.2s ease;">

                                <td class="ps-3"><?= $i++; ?></td>
                                <td class="col-judul"><?= htmlspecialchars($r['judul_kegiatan']); ?></td>
                                <td><?= date('d/m/Y', strtotime($r['tanggal_pengajuan'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($r['tanggal_peminjaman'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($r['tanggal_pengembalian'])); ?></td>
                                <td class="text-center col-status">
                                    <span class="badge <?= $status_class ?> rounded-pill px-3 py-2"
                                        style="font-size: 12px; font-weight: 500;">
                                        <?= $status_display ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= BASEURL; ?>Pengembalian/edit/<?= $r['id_peminjaman']; ?>"
                                            class="btn btn-sm" title="Edit Status Pengembalian"
                                            style="background: none; border: none; padding: 5px;">
                                            <i class="fa-solid fa-pen-to-square" style="color: #30cc30; font-size: 18px;"></i>
                                        </a>

                                        <a href="<?= BASEURL; ?>Pengembalian/detail/<?= $r['id_peminjaman']; ?>"
                                            class="btn btn-sm" title="Detail Pengembalian"
                                            style="background: none; border: none; padding: 5px;">
                                            <i class="fa-solid fa-eye" style="color: #1250ba; font-size: 18px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    else:
                        ?>
                        <tr id="noDataRow">
                            <td colspan="7" class="text-center py-4" style="color: #666;">
                                <i class="fas fa-inbox fa-3x mb-3" style="color: #ddd;"></i>
                                <p class="mb-0">Tidak ada data peminjaman</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr id="filterNoData" style="display: none;">
                        <td colspan="7" class="text-center py-4" style="color: #666;">
                            <i class="fas fa-search fa-3x mb-3" style="color: #ddd;"></i>
                            <p class="mb-0">Data tidak ditemukan sesuai filter</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php if ($_SESSION['id_role'] == 3): ?>
            <!-- Recent Activity Log Section -->
            <div class="card shadow-lg border-0 mt-5 mb-5" style="border-radius: 16px; overflow: hidden; background: #fff;">
                <div class="card-header py-4 px-4" style="background: linear-gradient(135deg, #0d1b3e 0%, #1a2a6c 100%); border-bottom: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold d-flex align-items-center" style="color: #ffffff; letter-spacing: 0.5px;">
                            <div class="bg-white bg-opacity-10 p-2 rounded-lg me-3">
                                <i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i>
                            </div>
                            Log Aktivitas Pengembalian
                        </h5>
                        <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 rounded-pill" style="font-size: 0.8rem; font-weight: 500;">
                            Terbaru
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr style="background-color: #f8fafc;">
                                    <th class="ps-4 py-3 text-uppercase small fw-bold" style="color: #64748b; width: 80px; border-bottom: 2px solid #f1f5f9;">No</th>
                                    <th class="py-3 text-uppercase small fw-bold" style="color: #64748b; border-bottom: 2px solid #f1f5f9;">Nama Asisten</th>
                                    <th class="py-3 text-uppercase small fw-bold" style="color: #64748b; border-bottom: 2px solid #f1f5f9;">Peminjaman</th>
                                    <th class="py-3 text-uppercase small fw-bold" style="color: #64748b; border-bottom: 2px solid #f1f5f9;">Waktu Periksa</th>
                                    <th class="text-center py-3 text-uppercase small fw-bold" style="color: #64748b; width: 150px; border-bottom: 2px solid #f1f5f9;">Kontrol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $j = 1;
                                if (!empty($data['log_aktivitas'])):
                                    foreach ($data['log_aktivitas'] as $log):
                                ?>
                                        <tr class="activity-row" style="transition: all 0.2s ease;">
                                            <td class="ps-4 fw-medium text-muted"><?= $j++; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar-circle d-flex align-items-center justify-content-center text-white shadow-sm" 
                                                         style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); font-weight: 700; font-size: 16px;">
                                                        <?= strtoupper(substr($log['nama_asisten'], 0, 1)); ?>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark" style="font-size: 0.95rem;"><?= htmlspecialchars($log['nama_asisten']); ?></div>
                                                        <div class="small text-muted">Petugas Verifikasi</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark"><?= htmlspecialchars($log['nama_peminjaman']); ?></div>
                                                <div class="small text-muted mt-1">
                                                    <i class="fa-solid fa-hashtag me-1 opacity-50"></i>ID: <?= $log['id_peminjaman']; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-medium"><?= date('d M Y', strtotime($log['waktu_periksa'])); ?></span>
                                                    <span class="small text-muted">
                                                        <i class="fa-regular fa-clock me-1 opacity-50"></i><?= date('H:i', strtotime($log['waktu_periksa'])); ?> WIB
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= BASEURL; ?>Pengembalian/detail/<?= $log['id_peminjaman']; ?>" 
                                                   class="btn btn-action-view"
                                                   style="
                                                        padding: 8px 16px; 
                                                        border-radius: 10px; 
                                                        background-color: #eff6ff; 
                                                        color: #2563eb; 
                                                        font-weight: 600; 
                                                        font-size: 13px;
                                                        border: none;
                                                        transition: all 0.2s;
                                                        display: inline-flex;
                                                        align-items: center;
                                                        gap: 8px;
                                                        text-decoration: none;
                                                   ">
                                                    <i class="fa-solid fa-circle-info"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="py-4">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                                    <i class="fa-solid fa-clipboard-list fa-2x text-muted opacity-50"></i>
                                                </div>
                                                <h6 class="text-dark fw-bold mb-1">Belum Ada Aktivitas</h6>
                                                <p class="text-muted small mb-0">Semua aktivitas pemeriksaan pengembalian akan muncul di sini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <style>
                .activity-row:hover {
                    background-color: #f8fafc !important;
                }
                .btn-action-view:hover {
                    background-color: #dbeafe !important;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.1);
                }
                .avatar-circle {
                    transition: transform 0.2s ease;
                }
                .activity-row:hover .avatar-circle {
                    transform: scale(1.05);
                }
            </style>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Definisi Elemen
        const btnFilterToggle = document.getElementById('btnFilterToggle');
        const filterSection = document.getElementById('filterSection');
        const btnReset = document.getElementById('btnReset');
        const searchInput = document.getElementById('searchInput');
        const filterStatus = document.getElementById('filterStatus');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');

        const tableRows = document.querySelectorAll('.data-row');
        const filterNoData = document.getElementById('filterNoData');
        const noDataRow = document.getElementById('noDataRow');

        // 1. Fungsi Toggle Tampilan Filter
        window.toggleFilter = function () { // Dijadikan window function agar onclick di HTML jalan
            if (filterSection.style.display === 'none' || filterSection.style.display === '') {
                filterSection.style.display = 'block';
            } else {
                filterSection.style.display = 'none';
            }
        };

        // 2. Fungsi Utama Filter
        function applyFilters() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            const selectedStatus = filterStatus.value.toLowerCase().trim();
            const start = startDate.value;
            const end = endDate.value;

            let visibleCount = 0;

            tableRows.forEach(row => {
                const rowStatus = row.getAttribute('data-status')?.toLowerCase() || '';
                const rowDate = row.getAttribute('data-date') || '';
                const rowText = row.innerText.toLowerCase(); // Mengambil semua teks dalam row

                // Logika Pencarian Teks
                const matchSearch = !searchTerm || rowText.includes(searchTerm);

                // Logika Status
                const matchStatus = !selectedStatus || rowStatus === selectedStatus;

                // Logika Rentang Tanggal
                let matchDate = true;
                if (start && end) {
                    matchDate = rowDate >= start && rowDate <= end;
                } else if (start) {
                    matchDate = rowDate >= start;
                } else if (end) {
                    matchDate = rowDate <= end;
                }

                // Gabungkan semua kondisi
                if (matchSearch && matchStatus && matchDate) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Tampilkan pesan jika tidak ada hasil
            if (filterNoData) {
                filterNoData.style.display = (visibleCount === 0 && tableRows.length > 0) ? '' : 'none';
            }

            // Sembunyikan "Tidak ada data" awal jika kita sedang memfilter
            if (noDataRow && tableRows.length > 0) {
                noDataRow.style.display = 'none';
            }
        }

        // 3. Event Listeners untuk Otomatisasi

        // Input teks (Search)
        searchInput.addEventListener('input', applyFilters);

        // Dropdown Status
        filterStatus.addEventListener('change', applyFilters);

        // Date pickers
        startDate.addEventListener('change', applyFilters);
        endDate.addEventListener('change', applyFilters);

        // Reset Button
        if (btnReset) {
            btnReset.addEventListener('click', function () {
                filterStatus.value = '';
                startDate.value = '';
                endDate.value = '';
                searchInput.value = '';
                applyFilters();
            });
        }

        // Jalankan filter saat halaman dimuat (untuk kondisi default)
        applyFilters();
    }); // Penutup DOMContentLoaded yang benar
</script>