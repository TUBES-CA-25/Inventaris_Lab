// Chart configuration and initialization
let filterMode, filterBulan, filterTahun;
let charts = {}; 
let baseUrl = '';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize elements
    filterMode = document.getElementById('filterMode');
    filterBulan = document.getElementById('filterBulan');
    filterTahun = document.getElementById('filterTahun');
    
    // Get baseUrl from meta tag or window object
    baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || 
              (window.BASEURL || '');

    // Tampilkan/Sembunyikan dropdown bulan berdasarkan mode
    filterMode.addEventListener('change', function() {
        if (this.value === 'harian') {
            document.getElementById('filterBulanWrapper').classList.remove('d-none');
            filterTahun.style.display = 'block';
        } else if (this.value === 'bulanan') {
            document.getElementById('filterBulanWrapper').classList.add('d-none');
            filterTahun.style.display = 'block';
        } else { // tahunan
            document.getElementById('filterBulanWrapper').classList.add('d-none');
            // filterTahun.style.display = 'none'; // Bisa dihide jika tahunan otomatis ambil range 5 tahun
        }
    });

    // Initialize charts
    charts.peminjaman = initChart('chartPeminjaman', 'Peminjaman', 'rgb(147, 112, 219)');
    charts.pengembalian = initChart('chartPengembalian', 'Pengembalian', 'rgb(255, 159, 64)');
    charts.bagus = initChart('chartBarangBagus', 'Barang Bagus', 'rgb(75, 192, 192)');
    charts.rusak = initChart('chartBarangRusak', 'Barang Rusak', 'rgb(255, 99, 132)');

    // Load initial data
    updateCharts();
});

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

function updateCharts() {
    const payload = {
        mode: filterMode.value,
        tahun: filterTahun.value,
        bulan: filterBulan.value
    };

    // Fetch ke Controller
    fetch(baseUrl + 'Beranda/getAjaxStats', {
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
