// Ambil elemen filter
const filterMode = document.getElementById('filterMode');
const filterBulan = document.getElementById('filterBulan'); // Select-nya
const filterBulanWrapper = document.getElementById('filterBulanWrapper'); // Wrapper-nya (yang ada class d-none)
const filterTahun = document.getElementById('filterTahun');

// Event Listener untuk Filter Mode
filterMode.addEventListener('change', function() {
    // Reset tampilan
    if (filterBulanWrapper) filterBulanWrapper.classList.add('d-none');
    
    // Logika Tampilan
    if (this.value === 'harian') {
        // Tampilkan Wrapper Bulan
        if (filterBulanWrapper) filterBulanWrapper.classList.remove('d-none');
    } 
    // Jika 'bulanan' atau 'tahunan', wrapper bulan tetap hidden (d-none)
});

// Setup Chart.js
let charts = {}; 
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } }, 
    scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } }
    }
};

function initChart(id, label, color) {
    const canvas = document.getElementById(id);
    if (!canvas) return null; // Safety check jika elemen tidak ada

    const ctx = canvas.getContext('2d');
    return new Chart(ctx, {
        type: 'line', // Tetap menggunakan Line sesuai file asli Anda
        data: {
            labels: [],
            datasets: [{
                label: label,
                data: [],
                borderColor: color,
                backgroundColor: color.replace('rgb', 'rgba').replace(')', ', 0.2)'),
                tension: 0.4,
                fill: true
            }]
        },
        options: commonOptions
    });
}

// Inisialisasi Chart
charts.peminjaman = initChart('chartPeminjaman', 'Peminjaman', 'rgb(147, 112, 219)');
charts.pengembalian = initChart('chartPengembalian', 'Pengembalian', 'rgb(255, 159, 64)');
charts.bagus = initChart('chartBarangBagus', 'Barang Bagus', 'rgb(75, 192, 192)');
charts.rusak = initChart('chartBarangRusak', 'Barang Rusak', 'rgb(255, 99, 132)');

// Fungsi Update Data
function updateCharts() {
    const payload = {
        mode: filterMode.value,
        tahun: filterTahun.value,
        bulan: filterBulan.value
    };

    // PENTING: Gunakan variabel global 'baseUrl' yang didefinisikan di View
    // Jika variabel belum ada, fallback ke string kosong (biar tidak error fatal)
    const url = (typeof baseUrl !== 'undefined' ? baseUrl : '') + 'Beranda/getAjaxStats';

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        // Update Semua Chart jika object chart berhasil dibuat
        const labels = data.labels;

        if (charts.peminjaman) {
            charts.peminjaman.data.labels = labels;
            charts.peminjaman.data.datasets[0].data = data.peminjaman;
            charts.peminjaman.update();
        }

        if (charts.pengembalian) {
            charts.pengembalian.data.labels = labels;
            charts.pengembalian.data.datasets[0].data = data.pengembalian;
            charts.pengembalian.update();
        }

        if (charts.bagus) {
            charts.bagus.data.labels = labels;
            charts.bagus.data.datasets[0].data = data.bagus;
            charts.bagus.update();
        }

        if (charts.rusak) {
            charts.rusak.data.labels = labels;
            charts.rusak.data.datasets[0].data = data.rusak;
            charts.rusak.update();
        }
    })
    .catch(err => console.error('Gagal mengambil data:', err));
}

// Load awal saat halaman siap
document.addEventListener('DOMContentLoaded', function() {
    // Trigger change event manual agar tampilan filter sesuai state awal
    filterMode.dispatchEvent(new Event('change'));
    updateCharts();
});