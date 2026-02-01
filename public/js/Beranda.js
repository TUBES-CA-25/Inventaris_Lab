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