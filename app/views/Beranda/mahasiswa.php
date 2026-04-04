<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>


<div class="content">
    <div class="container-fluid p-0">

        <!-- Header Section -->
        <div class="mahasiswa-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2>Halo,
                        <?= $data['profile']['nama_user']; ?>! 👋
                    </h2>
                    <p>Selamat datang kembali di Sistem Inventaris Laboratorium. Pantau dan kelola peminjaman barang
                        Anda dengan mudah.</p>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <img src="<?= BASEURL; ?>img/header_illustration.svg" alt=""
                        style="max-height: 120px; opacity: 0.8;">
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="stat-cards">
            <div class="stat-card">
                <div class="stat-icon icon-blue">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>Sedang Dipinjam</h3>
                    <div class="value">
                        <?= $data['peminjaman_berlangsung']; ?>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-red">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3>Melewati Batas</h3>
                    <div class="value">
                        <?= $data['melewati_batas']; ?>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Transaksi</h3>
                    <div class="value">
                        <?= count($data['recent_loans']); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="dashboard-grid">

            <!-- Recent Activity -->
            <div class="log-card">
                <div class="card-header">
                    <h3>Aktivitas Peminjaman Terbaru</h3>
                    <a href="<?= BASEURL; ?>Riwayat" class="btn-view-all">Lihat Semua <i
                            class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="log-table">
                    <?php if (empty($data['recent_loans'])): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">Belum ada riwayat peminjaman.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($data['recent_loans'] as $loan): ?>
                            <div class="log-item">
                                <div class="log-date text-center">
                                    <div class="date-badge">
                                        <?= date('d M Y', strtotime($loan['tanggal_peminjaman'])); ?>
                                    </div>
                                </div>
                                <div class="log-details px-3">
                                    <div class="log-title">
                                        <?= $loan['judul_kegiatan']; ?>
                                    </div>
                                    <div class="log-status">
                                        <?php
                                        $badgeClass = 'bg-primary';
                                        if ($loan['status'] == 'Disetujui')
                                            $badgeClass = 'bg-success';
                                        if ($loan['status'] == 'Ditolak Peminjaman')
                                            $badgeClass = 'bg-danger';
                                        if ($loan['status'] == 'Dikembalikan')
                                            $badgeClass = 'bg-info text-dark';
                                        ?>
                                        <span class="badge <?= $badgeClass; ?>">
                                            <?= $loan['status']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="log-action">
                                    <a href="<?= BASEURL; ?>Peminjaman/detail/<?= $loan['id_peminjaman']; ?>"
                                        class="btn btn-sm btn-light border"><i class="fas fa-eye"></i></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SOP Information -->
            <div class="sop-card">
                <div class="card-header">
                    <h3>Prosedur Peminjaman (SOP)</h3>
                </div>
                <div class="sop-list">
                    <div class="sop-step">
                        <div class="step-num">1</div>
                        <div class="step-text">Pilih barang yang ingin dipinjam dan isi <strong>Form Pengajuan</strong>
                            dengan lengkap.</div>
                    </div>
                    <div class="sop-step">
                        <div class="step-num">2</div>
                        <div class="step-text"><strong>Unduh Surat Peminjaman</strong> yang telah digenerate oleh sistem
                            secara otomatis.</div>
                    </div>
                    <div class="sop-step">
                        <div class="step-num">3</div>
                        <div class="step-text">Minta tanda tangan dari <strong>Dosen Pembimbing</strong> pada surat
                            tersebut.</div>
                    </div>
                    <div class="sop-step">
                        <div class="step-num">4</div>
                        <div class="step-text"><strong>Unggah kembali</strong> surat yang telah ditandatangani melalui
                            sistem.</div>
                    </div>
                    <div class="sop-step">
                        <div class="step-num">5</div>
                        <div class="step-text">Tunggu <strong>Validasi Laboran / Dosen Pembimbing</strong> dan ambil barang di ruang
                            Laboratorium.</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Loan Graph -->
        <div class="chart-section">
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h3 class="mb-0 fw-bold">Grafik Riwayat Peminjaman</h3>
                </div>
                <div class="col-md-6">
                    <div class="row g-2 justify-content-end">
                        <div class="col-auto">
                            <select id="filterMode" class="filter-select" onchange="toggleFilterBulan()">
                                <option value="harian">Harian</option>
                                <option value="bulanan" selected>Bulanan</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select id="filterTahun" class="filter-select">
                                <?php
                                $currentYear = date('Y');
                                for ($i = $currentYear; $i >= $currentYear - 4; $i--): ?>
                                    <option value="<?= $i; ?>">
                                        <?= $i; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-auto d-none" id="filterBulanWrapper">
                            <select id="filterBulan" class="filter-select">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?= $m; ?>" <?= $m == date('m') ? 'selected' : ''; ?>>
                                        <?= date('F', mktime(0, 0, 0, $m, 1)); ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button class="btn-filter-action" onclick="updateStudentCharts()">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div style="height: 350px;">
                <canvas id="studentLoanChart"></canvas>
            </div>
        </div>

    </div>
</div>

<script>
    let studentChart;

    document.addEventListener('DOMContentLoaded', function () {
        updateStudentCharts();
    });

    function toggleFilterBulan() {
        const mode = document.getElementById('filterMode').value;
        const bulanWrapper = document.getElementById('filterBulanWrapper');
        const tahunWrapper = document.getElementById('filterTahun').parentElement;

        // Toggle Bulan
        if (mode === 'harian') {
            bulanWrapper.classList.remove('d-none');
        } else {
            bulanWrapper.classList.add('d-none');
        }

        // Toggle Tahun
        if (mode === 'tahunan') {
            tahunWrapper.classList.add('d-none');
        } else {
            tahunWrapper.classList.remove('d-none');
        }
    }

    function updateStudentCharts() {
        const mode = document.getElementById('filterMode').value;
        const tahun = document.getElementById('filterTahun').value;
        const bulan = document.getElementById('filterBulan').value;

        fetch('<?= BASEURL; ?>Beranda/getAjaxStats', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mode, tahun, bulan })
        })
            .then(res => res.json())
            .then(data => {
                const maxVal = Math.max(...data.peminjaman, ...data.pengembalian, 0);

                if (!studentChart) {
                    const ctx = document.getElementById('studentLoanChart').getContext('2d');
                    studentChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'Peminjaman',
                                    data: data.peminjaman,
                                    borderColor: '#2563eb',
                                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 3,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#fff',
                                    pointHoverRadius: 6
                                },
                                {
                                    label: 'Pengembalian',
                                    data: data.pengembalian,
                                    borderColor: '#10b981',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 3,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#fff',
                                    pointHoverRadius: 6
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 1000,
                                easing: 'easeInOutQuart'
                            },
                            plugins: {
                                legend: { position: 'top', labels: { usePointStyle: true, padding: 20 } },
                                tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 10 }
                            },
                            scales: {
                                y: { 
                                    beginAtZero: true, 
                                    max: maxVal + 5,
                                    ticks: { stepSize: 1, color: '#94a3b8' }, 
                                    grid: { borderDash: [5, 5] } 
                                },
                                x: { ticks: { color: '#94a3b8' }, grid: { display: false } }
                            },
                            interaction: { intersect: false, mode: 'index' }
                        }
                    });
                } else {
                    studentChart.data.labels = data.labels;
                    studentChart.data.datasets[0].data = data.peminjaman;
                    studentChart.data.datasets[1].data = data.pengembalian;
                    studentChart.options.scales.y.max = maxVal + 5;
                    studentChart.update();
                }
            });
    }
</script>