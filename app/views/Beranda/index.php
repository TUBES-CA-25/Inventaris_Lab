<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<!-- Modal keluar -->
<div class="modal fade" id="konfirmasiKeluar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-keluar">
            <div class="modal-body modal-body-keluar">
                <lottie-player 
                    src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json"
                    background="##FFFFFF" 
                    speed="1" 
                    class="lottie-animation" 
                    loop 
                    autoplay 
                    direction="1"
                    mode="normal">
                </lottie-player>
                <p class="modal-confirmation-text">Apakah anda yakin ingin keluar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-modal-cancel" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-modal-logout" onclick="location.href='<?= BASEURL; ?>Logout'">Keluar</button>
            </div>
        </div>
    </div>
</div>

<!-- Content Area -->
<div class="content">
    <div class="beranda-container">
        
        <div class="beranda-header">
            <h2>Beranda</h2>
        </div>

        <div class="card mb-4" style="border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <div class="card-body d-flex align-items-center flex-wrap" style="gap: 15px;">
                <h5 class="mb-0 mr-3">Filter Grafik:</h5>
                
                <select id="filterMode" class="form-control" style="width: 150px;">
                    <option value="harian">Harian</option>
                    <option value="bulanan" selected>Bulanan</option>
                    <option value="tahunan">Tahunan</option>
                </select>

                <select id="filterTahun" class="form-control" style="width: 120px;">
                    <?php 
                    $currentYear = date('Y');
                    for($i = $currentYear; $i >= $currentYear - 4; $i--): ?>
                        <option value="<?= $i; ?>"><?= $i; ?></option>
                    <?php endfor; ?>
                </select>

                <select id="filterBulan" class="form-control" style="width: 150px; display: none;">
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>

                <button class="btn btn-primary" onclick="updateCharts()">Tampilkan</button>
            </div>
        </div>
        
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header"><h3>Total Peminjaman</h3></div>
                <div class="chart-container"><canvas id="chartPeminjaman"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-header"><h3>Total Pengembalian</h3></div>
                <div class="chart-container"><canvas id="chartPengembalian"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-header"><h3>Total Barang Bagus</h3></div>
                <div class="chart-container"><canvas id="chartBarangBagus"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-header"><h3>Total Barang Rusak</h3></div>
                <div class="chart-container"><canvas id="chartBarangRusak"></canvas></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const filterMode = document.getElementById('filterMode');
    const filterBulan = document.getElementById('filterBulan');
    const filterTahun = document.getElementById('filterTahun');

    // Tampilkan/Sembunyikan dropdown bulan berdasarkan mode
    filterMode.addEventListener('change', function() {
        if (this.value === 'harian') {
            filterBulan.style.display = 'block';
            filterTahun.style.display = 'block';
        } else if (this.value === 'bulanan') {
            filterBulan.style.display = 'none';
            filterTahun.style.display = 'block';
        } else { // tahunan
            filterBulan.style.display = 'none';
            // filterTahun.style.display = 'none'; // Bisa dihide jika tahunan otomatis ambil range 5 tahun
        }
    });

    let charts = {}; 

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }, // Hide legend karena cuma 1 dataset per grafik
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    };

    function initChart(id, label, color) {
        const ctx = document.getElementById(id).getContext('2d');
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: label,
                    data: [],
                    borderColor: color,
                    backgroundColor: color.replace(')', ', 0.2)').replace('rgb', 'rgba'),
                    tension: 0.4,
                    fill: true
                }]
            },
            options: commonOptions
        });
    }

    charts.peminjaman = initChart('chartPeminjaman', 'Peminjaman', 'rgb(147, 112, 219)');
    charts.pengembalian = initChart('chartPengembalian', 'Pengembalian', 'rgb(255, 159, 64)');
    charts.bagus = initChart('chartBarangBagus', 'Barang Bagus', 'rgb(75, 192, 192)');
    charts.rusak = initChart('chartBarangRusak', 'Barang Rusak', 'rgb(255, 99, 132)');

    function updateCharts() {
        const payload = {
            mode: filterMode.value,
            tahun: filterTahun.value,
            bulan: filterBulan.value
        };

        // Fetch ke Controller
        fetch('<?= BASEURL; ?>Beranda/getAjaxStats', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            // Update Labels (Sumbu X) untuk semua chart
            const labels = data.labels;

            // Update Data Chart 1
            charts.peminjaman.data.labels = labels;
            charts.peminjaman.data.datasets[0].data = data.peminjaman;
            charts.peminjaman.update();

            // Update Data Chart 2
            charts.pengembalian.data.labels = labels;
            charts.pengembalian.data.datasets[0].data = data.pengembalian;
            charts.pengembalian.update();

            // Update Data Chart 3
            charts.bagus.data.labels = labels;
            charts.bagus.data.datasets[0].data = data.bagus;
            charts.bagus.update();

            // Update Data Chart 4
            charts.rusak.data.labels = labels;
            charts.rusak.data.datasets[0].data = data.rusak;
            charts.rusak.update();
        })
        .catch(err => console.error('Gagal mengambil data:', err));
    }

    document.addEventListener('DOMContentLoaded', updateCharts);

</script>